<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\TourismPermit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class SendPermitExpiryReminders extends Command
{
    protected $signature = 'permits:send-expiry-reminders {--date= : Simulated today in YYYY-MM-DD format}';

    protected $description = 'Notify permit owners when their tourism permits expire in one month';

    public function handle(): int
    {
        if (!Schema::hasColumn('tourism_permits', 'user_id')) {
            $this->error('The tourism_permits table has no user_id column.');
            return self::FAILURE;
        }

        if (!Schema::hasTable('notifications')) {
            $this->error('The notifications table does not exist.');
            return self::FAILURE;
        }

        try {
            $dateOption = $this->option('date');

            if ($dateOption) {
                $today = Carbon::createFromFormat('!Y-m-d', $dateOption);

                if (!$today || $today->format('Y-m-d') !== $dateOption) {
                    $this->error('Invalid date. Use YYYY-MM-DD.');
                    return self::FAILURE;
                }
            } else {
                $today = Carbon::today();
            }

            $targetExpiry = $today->copy()->addMonthNoOverflow();
        } catch (\Throwable $e) {
            $this->error('Invalid date. Use YYYY-MM-DD.');
            return self::FAILURE;
        }

        $this->info('Checking permits expiring on: ' . $targetExpiry->toDateString());

        $permits = TourismPermit::query()
            ->whereDate('expiry_date', $targetExpiry->toDateString())
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->get();

        $sent = 0;
        $skipped = 0;

        foreach ($permits as $permit) {
            $expiryDate = Carbon::parse($permit->expiry_date);

            $title = 'Tourism Permit Expiry Reminder';

            $message = sprintf(
                'Your tourism permit for %s (Certificate No. %s) expires on %s. Please renew your permit before the expiry date.',
                $permit->business_name,
                $permit->certificate_number,
                $expiryDate->format('j F Y')
            );

            $alreadySent = Notification::query()
                ->where('user_id', $permit->user_id)
                ->where('type', 'permit_expiry')
                ->where('message', $message)
                ->exists();

            if ($alreadySent) {
                $skipped++;
                continue;
            }

            Notification::create([
                'user_id' => $permit->user_id,
                'title' => $title,
                'message' => $message,
                'type' => 'permit_expiry',
                'link' => route('dashboard.permits.show', ['id' => $permit->id]),
                'is_read' => false,
            ]);

            $sent++;
        }

        $this->info("Permit expiry reminders created: {$sent}");
        $this->info("Existing reminders skipped: {$skipped}");

        return self::SUCCESS;
    }
}
