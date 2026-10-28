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
        
        // Fetch all facilities owned by this user
        $facilities = collect();
        
        if ($user->role === 'Hotel') {
            $facilities = Hotel::where('user_id', $user->id)->get()->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Hotel']);
        } elseif ($user->role === 'Restaurant') {
            $facilities = Restaurant::where('user_id', $user->id)->get()->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Restaurant']);
        } elseif ($user->role === 'Club/Lounge') {
            $facilities = ClubLounge::where('user_id', $user->id)->get()->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Club']);
        } elseif ($user->role === 'Tour Operator') {
            $facilities = TourOperator::where('user_id', $user->id)->get()->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'type' => 'Tour Operator']);
        }

        // If no facilities, create a dummy one so they can still apply (or redirect with error)
        if ($facilities->isEmpty()) {
            $facilities = collect([['id' => 0, 'name' => $user->first_name . ' ' . $user->last_name . ' Business', 'type' => $user->role]]);
        }

        // Fetch CMS prices
        $prices = [
            'Hotel' => \App\Models\CmsSetting::where('key', 'permit_price_hotel')->first()->value ?? 50000,
            'Restaurant' => \App\Models\CmsSetting::where('key', 'permit_price_restaurant')->first()->value ?? 25000,
            'Club' => \App\Models\CmsSetting::where('key', 'permit_price_club')->first()->value ?? 30000,
            'Tour Operator' => 20000,
        ];

        return view('dashboard.permits.apply', compact('facilities', 'prices'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'facility_id' => 'required',
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'address' => 'required|string',
            'amount_paid' => 'required|numeric',
        ]);

        $prefix = strtoupper(substr($request->business_type, 0, 3));
        $certNum = 'EDSTA-' . $prefix . '-' . date('Y') . '-' . str_pad(TourismPermit::count() + 1, 4, '0', STR_PAD_LEFT);

        TourismPermit::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'facility_type' => $request->business_type,
            'certificate_number' => $certNum,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'owner_name' => Auth::user()->full_name,
            'address' => $request->address,
            'amount_paid' => $request->amount_paid,
            'issue_date' => now(),
            'expiry_date' => now()->addYear(),
            'status' => 'active',
        ]);

        return redirect()->route('dashboard.permits.index')->with('success', 'Payment successful! Your certificate has been generated.');
    }

    public function show(int $id)
    {
        $permit = TourismPermit::where('user_id', Auth::id())->findOrFail($id);
        return view('dashboard.permits.show', compact('permit'));
    }
}