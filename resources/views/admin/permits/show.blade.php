@extends('layouts.admin')
@section('title', 'Certificate')
@section('page_title', 'Certificate Details')
@section('content')
<div class="mb-3 d-flex justify-content-between">
    <a href="{{ route('admin.permits.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
    <button onclick="window.print()" class="btn btn-gold btn-sm"><i class="bi bi-printer"></i> Print Certificate</button>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width: 900px; background: #fffdf5; border: 10px solid #c9a227 !important; padding: 40px; position: relative;">
    <div class="text-center mb-5">
        <img src="{{ \App\Helpers\Helpers::imageUrl(\App\Helpers\Helpers::setting('site_logo') ?? '') }}" style="width:80px;" class="mb-3" alt="Logo">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #5B0000;">EDO STATE TOURISM AGENCY</h2>
        <h4 class="text-muted">Certificate of Permit</h4>
    </div>
    
    <div class="row mb-4">
        <div class="col-8">
            <p class="mb-1 text-muted small">This is to certify that</p>
            <h3 class="fw-bold text-dark mb-1">{{ $permit->business_name }}</h3>
            <p class="text-muted">{{ $permit->address }}</p>
            
            <p class="mt-4">Has been officially registered and granted permission to operate as a <strong>{{ $permit->business_type }}</strong> in Edo State for the period:</p>
            <p class="fw-bold fs-5">{{ $permit->issue_date->format('F d, Y') }} – {{ $permit->expiry_date->format('F d, Y') }}</p>
            
            <p class="mt-3"><strong>Certificate No:</strong> {{ $permit->certificate_number }}</p>
            <p><strong>Status:</strong> 
                @if($permit->isExpired()) <span class="text-danger fw-bold">EXPIRED - RENEWAL REQUIRED</span>
                @else <span class="text-success fw-bold">VALID</span> @endif
            </p>
        </div>
        <div class="col-4 text-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/verify/' . $permit->certificate_number)) }}" alt="QR Code" class="img-fluid border p-2 bg-white" style="width:150px; height:150px;">
            <p class="small text-muted mt-2">Scan to verify</p>
        </div>
    </div>

    <div class="row mt-5 pt-4 align-items-end">
        <div class="col-4 text-center">
            @php
    $stamp = \App\Models\CmsSetting::where('key', 'permit_agency_stamp')->first();
@endphp
            @if($stamp && $stamp->value)
                <img src="{{ \App\Helpers\Helpers::imageUrl($stamp->value) }}" style="width:120px; opacity:0.8;" alt="Stamp">
            @endif
        </div>
        <div class="col-4 text-center">
            @php
    $sig = \App\Models\CmsSetting::where('key', 'permit_dg_signature')->first();
@endphp
            @if($sig && $sig->value)
                <img src="{{ \App\Helpers\Helpers::imageUrl($sig->value) }}" style="width:150px; margin-bottom: 5px;" alt="Signature">
            @endif
            <hr style="width: 80%; margin: 0 auto; border-top: 2px solid #000;">
            <p class="small fw-bold mt-1 mb-0">Director General</p>
        </div>
        <div class="col-4 text-center">
            <p class="small text-muted mb-0">Official Use Only</p>
            <p class="small text-muted">Paid: ₦{{ number_format($permit->amount_paid) }}</p>
        </div>
    </div>
</div>
@endsection