<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourismPermit;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\ClubLounge;
use App\Models\TourOperator;
use Illuminate\Support\Facades\Auth;

class PermitApplicationController extends Controller
{
    public function index()
    {
        $permits = TourismPermit::where('user_id', Auth::id())->orderByDesc('id')->get();
        return view('dashboard.permits.index', compact('permits'));
    }

    public function create()
    {
        $user = Auth::user();

        // Permit applications require an authenticated user.
        if (!$user) {
            return redirect()->route('auth.login')
                ->with('error', 'Please log in to apply for a tourism permit.');
        }

        // Fetch facilities owned by the authenticated user.
        $facilities = collect();

        if ($user->role === 'Hotel') {
            $facilities = Hotel::where('user_id', $user->id)
                ->get()
                ->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Hotel']);
        } elseif ($user->role === 'Restaurant') {
            $facilities = Restaurant::where('user_id', $user->id)
                ->get()
                ->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Restaurant']);
        } elseif ($user->role === 'Club/Lounge') {
            $facilities = ClubLounge::where('user_id', $user->id)
                ->get()
                ->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Club']);
        } elseif ($user->role === 'Tour Operator') {
            $facilities = TourOperator::where('user_id', $user->id)
                ->get()
                ->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Tour Operator']);
        }

        // Fetch permit prices exclusively from CMS settings.
// Missing or invalid fees are returned as null; no fallback amounts are used.
        $feeSettings = [
            'Hotel' => 'permit_price_hotel',
            'Restaurant' => 'permit_price_restaurant',
            'Club' => 'permit_price_club',
            'Tour Operator' => 'permit_price_tour_operator',
        ];

        $prices = [];

        foreach ($feeSettings as $type => $settingKey) {
            $value = \App\Models\CmsSetting::where('key', $settingKey)->value('value');

            $prices[$type] = is_numeric($value)
                && is_finite((float) $value)
                && (float) $value > 0
                    ? (float) $value
                    : null;
        }

        return view('dashboard.permits.apply', compact('facilities', 'prices'));
    }

    /**
     * Legacy payment endpoint disabled.
     * All permit payments must use the verified Paystack workflow.
     */
    public function processPayment(Request $request)
    {
        abort(410, 'This payment endpoint has been retired. Please use the official permit payment process.');
    }

    public function show(int $id)
    {
        $permit = TourismPermit::where('user_id', Auth::id())->findOrFail($id);
        return view('dashboard.permits.show', compact('permit'));
    }
}
