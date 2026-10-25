@extends('layouts.app')
@section('title', 'Verify Certificate - Edo Tourism')
@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            @if($status === 'valid')
                <div class="card border-0 shadow-lg p-5" style="border-radius: 20px;">
                    <i class="bi bi-patch-check-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="fw-bold text-success mt-3">Valid Certificate</h2>
                    <p class="lead">{{ $permit->business_name }}</p>
                    <p class="text-muted">This establishment is a registered <strong>{{ $permit->business_type }}</strong> in Edo State.</p>
                    <div class="alert alert-light border mt-4 text-start">
                        <p class="mb-1"><strong>Certificate No:</strong> {{ $permit->certificate_number }}</p>
                        <p class="mb-1"><strong>Issued:</strong> {{ $permit->issue_date->format('M d, Y') }}</p>
                        <p class="mb-0"><strong>Expires:</strong> {{ $permit->expiry_date->format('M d, Y') }}</p>
                    </div>
                </div>
            @elseif($status === 'expired')
                <div class="card border-0 shadow-lg p-5" style="border-radius: 20px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 5rem;"></i>
                    <h2 class="fw-bold text-danger mt-3">Expired Certificate</h2>
                    <p class="lead">{{ $permit->business_name }}</p>
                    <p class="text-muted">This certificate expired on {{ $permit->expiry_date->format('M d, Y') }}. The establishment must renew their permit.</p>
                </div>
            @else
                <div class="card border-0 shadow-lg p-5" style="border-radius: 20px;">
                    <i class="bi bi-x-octagon-fill text-danger" style="font-size: 5rem;"></i>
                    <h2 class="fw-bold text-danger mt-3">Invalid Certificate</h2>
                    <p class="text-muted">No certificate matches this number in our database.</p>
                    <a href="{{ route('main.home') }}" class="btn btn-gold mt-3">Return to Home</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection