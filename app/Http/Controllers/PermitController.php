<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourismPermit;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    public function index()
    {
        $permits = TourismPermit::orderByDesc('id')->get();
        return view('admin.permits.index', compact('permits'));
    }

    public function create()
    {
        return view('admin.permits.form', ['permit' => new TourismPermit()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'owner_name' => 'nullable|string',
            'address' => 'nullable|string',
            'amount_paid' => 'required|numeric',
        ]);

        $prefix = strtoupper(substr($data['business_type'], 0, 3));
        $certNum = 'EDSTA-' . $prefix . '-' . date('Y') . '-' . str_pad(TourismPermit::count() + 1, 4, '0', STR_PAD_LEFT);

        $data['certificate_number'] = $certNum;
        $data['issue_date'] = now();
        $data['expiry_date'] = now()->addYear();
        $data['status'] = 'active';

        TourismPermit::create($data);
        return redirect()->route('admin.permits.index')->with('success', 'Certificate generated!');
    }

    public function show(int $id)
    {
        $permit = TourismPermit::findOrFail($id);
        return view('admin.permits.show', compact('permit'));
    }

    public function edit(int $id)
    {
        $permit = TourismPermit::findOrFail($id);
        return view('admin.permits.form', compact('permit'));
    }

    public function update(Request $request, int $id)
    {
        $permit = TourismPermit::findOrFail($id);
        $data = $request->validate([
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'owner_name' => 'nullable|string',
            'address' => 'nullable|string',
            'amount_paid' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $permit->update($data);
        return redirect()->route('admin.permits.index')->with('success', 'Certificate updated successfully.');
    }

    public function destroy(int $id)
    {
        TourismPermit::findOrFail($id)->delete();
        return redirect()->route('admin.permits.index')->with('success', 'Certificate revoked and deleted.');
    }

    public function expire(int $id)
    {
        $permit = TourismPermit::findOrFail($id);
        $permit->update(['expiry_date' => now()->subDay()]);
        return redirect()->route('admin.permits.index')->with('success', 'Certificate expired for testing.');
    }

    public function verify(string $number)
    {
        $permit = TourismPermit::where('certificate_number', $number)->first();
        
        if (!$permit) {
            return view('public.verify', ['status' => 'invalid']);
        }
        
        if ($permit->expiry_date->isPast()) {
            $permit->status = 'expired';
            $permit->save();
            return view('public.verify', ['status' => 'expired', 'permit' => $permit]);
        }

        return view('public.verify', ['status' => 'valid', 'permit' => $permit]);
    }
}