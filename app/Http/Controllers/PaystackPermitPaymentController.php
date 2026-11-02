<?php

namespace App\Http\Controllers;

use App\Models\TourismPermit;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\ClubLounge;
use App\Models\TourOperator;
use App\Models\CmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaystackPermitPaymentController extends Controller
{
    /**
     * Initialize a tourism permit generation or renewal payment.
     *
     * The CMS is the single source of truth for permit fees.
     */
    public function initialize(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|in:generate,renew',
            'facility_select' => 'required_if:action,generate|nullable|integer|min:1',
            'permit_id' => 'required_if:action,renew|nullable|integer|min:1',
            'address' => 'required_if:action,generate|nullable|string|max:500',
        ]);

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $secret = config('services.paystack.secret_key');
        $paymentUrl = config('services.paystack.payment_url');

        if (
            !$secret ||
            str_contains($secret, 'replace_me') ||
            !$paymentUrl
        ) {
            return back()->with(
                'error',
                'Paystack is not configured yet. Please contact the site administrator.'
            );
        }

        /*
         * Create the local transaction atomically.
         *
         * Facility and permit rows are locked to reduce the possibility
         * of simultaneous duplicate payment initialization.
         */
        $prepared = DB::transaction(function () use ($data, $user) {
            $permit = null;
            $payload = [];

            if ($data['action'] === 'renew') {
                $permit = TourismPermit::where('user_id', $user->id)
                    ->whereKey($data['permit_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$permit->isExpired()) {
                    throw ValidationException::withMessages([
                        'permit_id' => 'This permit has not expired and cannot be renewed yet.',
                    ]);
                }

                $pendingRenewal = DB::table('permit_payment_transactions')
                    ->where('tourism_permit_id', $permit->id)
                    ->where('action', 'renew')
                    ->whereIn('status', ['pending', 'verify_required'])
                    ->exists();

                if ($pendingRenewal) {
                    throw ValidationException::withMessages([
                        'permit_id' => 'A renewal payment is already pending or awaiting verification. Please complete it or contact support before starting another payment.',
                    ]);
                }

                $type = $permit->business_type;
                $amount = $this->feeFor($type);

                $payload = [
                    'permit_id' => $permit->id,
                ];
            } else {
                [$facility, $type, $name] = $this->ownedFacility(
                    (int) $data['facility_select'],
                    $user,
                    true
                );

                if (!$facility) {
                    throw ValidationException::withMessages([
                        'facility_select' => 'Select a facility owned by your account.',
                    ]);
                }

                /*
                 * Prevent generation when the facility already has
                 * a valid permit.
                 */
                $existingPermit = TourismPermit::where('user_id', $user->id)
                    ->where('facility_id', $facility->id)
                    ->where('facility_type', $type)
                    ->where('status', 'active')
                    ->whereDate('expiry_date', '>=', now()->toDateString())
                    ->exists();

                if ($existingPermit) {
                    throw ValidationException::withMessages([
                        'facility_select' => 'This facility already has a valid tourism permit. Please renew it when it expires.',
                    ]);
                }

                /*
                 * Prevent another generation payment while an earlier
                 * payment is pending or awaiting reconciliation.
                 */
                $pendingTransactions = DB::table('permit_payment_transactions')
                    ->where('user_id', $user->id)
                    ->where('action', 'generate')
                    ->whereIn('status', ['pending', 'verify_required'])
                    ->get(['id', 'payload']);

                foreach ($pendingTransactions as $pendingTransaction) {
                    $pendingPayload = json_decode(
                        $pendingTransaction->payload ?: '{}',
                        true
                    );

                    if (
                        is_array($pendingPayload) &&
                        (int) ($pendingPayload['facility_id'] ?? 0) === (int) $facility->id &&
                        ($pendingPayload['facility_type'] ?? '') === $type
                    ) {
                        throw ValidationException::withMessages([
                            'facility_select' => 'A permit payment is already pending or awaiting verification for this facility. Please complete it or contact support before starting another payment.',
                        ]);
                    }
                }

                $amount = $this->feeFor($type);

                $payload = [
                    'facility_id' => $facility->id,
                    'facility_type' => $type,
                    'business_name' => $name,
                    'business_type' => $type,
                    'owner_name' => trim(
                        ($user->first_name ?? '') . ' ' . ($user->last_name ?? '')
                    ),
                    'address' => $data['address'],
                ];
            }

            $reference = 'EDSTA-PERMIT-' . Str::upper(Str::random(24));

            $amountKobo = (int) round($amount * 100);

            $transaction = DB::table('permit_payment_transactions')->insertGetId([
                'user_id' => $user->id,
                'tourism_permit_id' => $permit?->id,
                'action' => $data['action'],
                'reference' => $reference,
                'amount_kobo' => $amountKobo,
                'currency' => 'NGN',
                'status' => 'pending',
                'payload' => json_encode($payload),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'reference' => $reference,
                'transaction' => $transaction,
                'amount' => $amount,
                'amount_kobo' => $amountKobo,
            ];
        });

        $reference = $prepared['reference'];
        $transaction = $prepared['transaction'];
        $amount = $prepared['amount'];
        $amountKobo = $prepared['amount_kobo'];

        try {
            $response = Http::withToken($secret)
                ->acceptJson()
                ->timeout(20)
                ->withOptions([
                    'verify' => config('services.paystack.ca_bundle') ?: true,
                ])
                ->post(
                    rtrim($paymentUrl, '/') . '/transaction/initialize',
                    [
                        'email' => $user->email,
                        'amount' => $amountKobo,
                        'currency' => 'NGN',
                        'reference' => $reference,
                        'callback_url' => route('dashboard.permits.paystack.callback'),
                        'metadata' => [
                            'purpose' => 'tourism_permit_' . $data['action'],
                            'transaction_id' => $transaction,
                            'user_id' => $user->id,
                        ],
                    ]
                );

            $authorizationUrl = $response->json('data.authorization_url');

            $authorizationScheme = is_string($authorizationUrl)
                ? parse_url($authorizationUrl, PHP_URL_SCHEME)
                : null;

            $authorizationHost = is_string($authorizationUrl)
                ? parse_url($authorizationUrl, PHP_URL_HOST)
                : null;

            $authorizationPort = is_string($authorizationUrl)
                ? parse_url($authorizationUrl, PHP_URL_PORT)
                : null;

            if (
                !$response->successful() ||
                !$response->json('status') ||
                !$authorizationUrl ||
                strtolower((string) $authorizationScheme) !== 'https' ||
                strtolower((string) $authorizationHost) !== 'checkout.paystack.com' ||
                !in_array($authorizationPort, [null, 443], true)
            ) {
                Log::warning('Paystack permit initialization rejected', [
                    'transaction_id' => $transaction,
                    'reference' => $reference,
                    'http_status' => $response->status(),
                    'paystack_status' => $response->json('status'),
                    'paystack_message' => $response->json('message'),
                    'amount_kobo' => $amountKobo,
                    'currency' => 'NGN',
                ]);

                $uncertain = !$response->successful()
                    ? !$response->clientError()
                    : (bool) $response->json('status');

                DB::table('permit_payment_transactions')
                    ->where('id', $transaction)
                    ->update([
                        'status' => $uncertain ? 'verify_required' : 'failed',
                        'updated_at' => now(),
                    ]);

                return back()->with(
                    'error',
                    $uncertain
                        ? 'Paystack responded, but the checkout link could not be safely validated. Contact support with your payment reference before trying again.'
                        : 'Paystack could not initialize payment. Please try again later.'
                );
            }

            return redirect()->away($authorizationUrl);
        } catch (\Throwable $e) {
            report($e);

            DB::table('permit_payment_transactions')
                ->where('id', $transaction)
                ->update([
                    'status' => 'verify_required',
                    'updated_at' => now(),
                ]);

            return back()->with(
                'error',
                'The payment request could not be confirmed. Please contact support with your payment reference before attempting another payment.'
            );
        }
    }

    /**
     * Verify a Paystack payment and generate or renew the permit.
     */
    public function callback(Request $request)
    {
        $reference = (string) $request->query('reference', '');

        if ($reference === '' || strlen($reference) > 100) {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Missing or invalid payment reference.');
        }

        $secret = config('services.paystack.secret_key');
        $paymentUrl = config('services.paystack.payment_url');

        if (!$secret || !$paymentUrl) {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Payment verification is not configured.');
        }

        try {
            $response = Http::withToken($secret)
                ->acceptJson()
                ->timeout(20)
                ->withOptions([
                    'verify' => config('services.paystack.ca_bundle') ?: true,
                ])
                ->get(
                    rtrim($paymentUrl, '/') .
                    '/transaction/verify/' .
                    rawurlencode($reference)
                );
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Could not verify payment yet. Refresh your permits page later.');
        }

        $verified = $response->json('data');

        if (
            !$response->successful() ||
            !$response->json('status') ||
            !is_array($verified) ||
            ($verified['status'] ?? '') !== 'success' ||
            (string) ($verified['reference'] ?? '') !== $reference
        ) {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Payment has not been confirmed by Paystack.');
        }

        $result = DB::transaction(function () use ($reference, $verified) {
            $tx = DB::table('permit_payment_transactions')
                ->where('reference', $reference)
                ->lockForUpdate()
                ->first();

            if (!$tx) {
                return 'invalid';
            }

            if (
                (int) ($verified['amount'] ?? 0) !== (int) $tx->amount_kobo ||
                strtoupper((string) ($verified['currency'] ?? '')) !== strtoupper($tx->currency)
            ) {
                return 'invalid';
            }

            if ($tx->status === 'paid') {
                return 'already';
            }

            if ($tx->status === 'refund_required') {
                return 'refund_required';
            }

            if ($tx->status === 'review_required') {
                return 'review_required';
            }

            if (!in_array($tx->status, ['pending', 'verify_required'], true)) {
                return 'invalid';
            }

            $metadata = $verified['metadata'] ?? [];

            if (!is_array($metadata)) {
                return 'invalid';
            }

            if (
                (int) ($metadata['user_id'] ?? 0) !== (int) $tx->user_id ||
                (int) ($metadata['transaction_id'] ?? 0) !== (int) $tx->id ||
                ($metadata['purpose'] ?? '') !== 'tourism_permit_' . $tx->action
            ) {
                return 'invalid';
            }

            if (!in_array($tx->action, ['generate', 'renew'], true)) {
                return 'invalid';
            }

            $payload = json_decode($tx->payload ?: '{}', true);

            if (!is_array($payload)) {
                return 'invalid';
            }

            if ($tx->action === 'generate') {
                if (
                    empty($payload['business_name']) ||
                    empty($payload['business_type']) ||
                    empty($payload['address']) ||
                    empty($payload['facility_id']) ||
                    empty($payload['facility_type'])
                ) {
                    return 'invalid';
                }

                $facilityModel = match ($payload['facility_type']) {
                    'Hotel' => Hotel::class,
                    'Restaurant' => Restaurant::class,
                    'Club' => ClubLounge::class,
                    'Tour Operator' => TourOperator::class,
                    default => null,
                };

                $facility = $facilityModel
                    ? $facilityModel::where('user_id', $tx->user_id)
                        ->whereKey($payload['facility_id'])
                        ->lockForUpdate()
                        ->first()
                    : null;

                if (!$facility) {
                    DB::table('permit_payment_transactions')
                        ->where('id', $tx->id)
                        ->update([
                            'status' => 'review_required',
                            'paid_at' => now(),
                            'updated_at' => now(),
                        ]);

                    return 'review_required';
                }

                /*
                 * Recheck for an existing valid permit at callback time.
                 * This protects against a second payment succeeding after
                 * another transaction has already generated the permit.
                 */
                $existingPermit = TourismPermit::where('user_id', $tx->user_id)
                    ->where('facility_id', $facility->id)
                    ->where('facility_type', $payload['facility_type'])
                    ->where('status', 'active')
                    ->whereDate('expiry_date', '>=', now()->toDateString())
                    ->lockForUpdate()
                    ->first();

                if ($existingPermit) {
                    DB::table('permit_payment_transactions')
                        ->where('id', $tx->id)
                        ->update([
                            'status' => 'refund_required',
                            'paid_at' => now(),
                            'updated_at' => now(),
                        ]);

                    return 'refund_required';
                }

                $number = 'EDSTA-' .
                    strtoupper(substr($payload['business_type'], 0, 3)) .
                    '-' . now()->format('Y') .
                    '-' . Str::upper(Str::random(8));

                $permit = TourismPermit::create([
                    'user_id' => $tx->user_id,
                    'facility_id' => $facility->id,
                    'facility_type' => $payload['facility_type'],
                    'certificate_number' => $number,
                    'business_name' => $facility->name,
                    'business_type' => $payload['business_type'],
                    'owner_name' => $payload['owner_name'] ?? null,
                    'address' => $payload['address'],
                    'amount_paid' => $tx->amount_kobo / 100,
                    'issue_date' => now()->toDateString(),
                    'expiry_date' => now()->addYear()->toDateString(),
                    'status' => 'active',
                ]);

                DB::table('permit_payment_transactions')
                    ->where('id', $tx->id)
                    ->update([
                        'tourism_permit_id' => $permit->id,
                    ]);
            } else {
                $permit = TourismPermit::where('user_id', $tx->user_id)
                    ->whereKey($tx->tourism_permit_id)
                    ->lockForUpdate()
                    ->first();

                if (!$permit) {
                    return 'invalid';
                }

                /*
                 * Do not extend a permit twice if another successful
                 * transaction has already renewed it.
                 */
                if (!$permit->isExpired()) {
                    DB::table('permit_payment_transactions')
                        ->where('id', $tx->id)
                        ->update([
                            'status' => 'refund_required',
                            'paid_at' => now(),
                            'updated_at' => now(),
                        ]);

                    return 'refund_required';
                }

                $base = now();

                $permit->update([
                    'issue_date' => now()->toDateString(),
                    'expiry_date' => $base->copy()->addYear()->toDateString(),
                    'amount_paid' => $tx->amount_kobo / 100,
                    'status' => 'active',
                ]);
            }

            DB::table('permit_payment_transactions')
                ->where('id', $tx->id)
                ->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

            return 'paid';
        });

        if ($result === 'invalid') {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Payment could not be matched to this permit transaction. Contact support before paying again.');
        }

        if ($result === 'already') {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('success', 'This payment was already processed.');
        }

        if ($result === 'refund_required') {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Paystack confirmed this payment, but your facility already has a valid permit. This transaction has been flagged for refund review. Please contact support and quote your payment reference.');
        }

        if ($result === 'review_required') {
            return redirect()
                ->route('dashboard.permits.index')
                ->with('error', 'Paystack confirmed your payment, but the facility could not be validated. Your transaction has been flagged for manual review. Please contact support and quote your payment reference.');
        }

        return redirect()
            ->route('dashboard.permits.index')
            ->with('success', 'Payment verified. Your tourism permit has been generated or renewed.');
    }

    /**
     * Retrieve the official permit fee from CMS.
     *
     * No hard-coded fallback fees are permitted.
     */
    private function feeFor(string $type): float
    {
        $key = match ($type) {
            'Hotel' => 'permit_price_hotel',
            'Restaurant' => 'permit_price_restaurant',
            'Club' => 'permit_price_club',
            'Tour Operator' => 'permit_price_tour_operator',
            default => null,
        };

        if (!$key) {
            throw ValidationException::withMessages([
                'action' => 'No permit fee configuration exists for this facility type.',
            ]);
        }

        $value = CmsSetting::where('key', $key)->value('value');

        if (
            !is_numeric($value) ||
            !is_finite((float) $value) ||
            (float) $value <= 0
        ) {
            throw ValidationException::withMessages([
                'action' => 'The official permit fee has not been configured. Please contact the site administrator.',
            ]);
        }

        return round((float) $value, 2);
    }

    /**
     * Retrieve a facility belonging to the authenticated user.
     *
     * When $lock is true, the facility row is locked for the
     * duration of the surrounding database transaction.
     */
    private function ownedFacility(int $id, $user, bool $lock = false): array
    {
        $model = match ($user->role) {
            'Hotel' => [Hotel::class, 'Hotel'],
            'Restaurant' => [Restaurant::class, 'Restaurant'],
            'Club/Lounge' => [ClubLounge::class, 'Club'],
            'Tour Operator' => [TourOperator::class, 'Tour Operator'],
            default => [null, null],
        };

        if (!$model[0]) {
            return [null, null, null];
        }

        $query = $model[0]::where('user_id', $user->id);

        if ($lock) {
            $query->lockForUpdate();
        }

        $facility = $query->find($id);

        return [
            $facility,
            $model[1],
            $facility?->name,
        ];
    }
}
