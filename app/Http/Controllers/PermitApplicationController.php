<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourismPermit;
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
        $active = TourismPermit::where('user_id', Auth::id())->where('expiry_date', '>', now())->first();
        if ($active) {
            return redirect()->route('dashboard.permits.index')->with('error', 'You already have an active permit.');
        }
        return view('dashboard.permits.apply');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'address' => 'required|string',
            'amount_paid' => 'required|numeric',
        ]);

        $prefix = strtoupper(substr($request->business_type, 0, 3));
        $certNum = 'EDSTA-' . $prefix . '-' . date('Y') . '-' . str_pad(TourismPermit::count() + 1, 4, '0', STR_PAD_LEFT);

        TourismPermit::create([
            'user_id' => Auth::id(),
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
        return view('admin.permits.show', compact('permit'));
    }
}