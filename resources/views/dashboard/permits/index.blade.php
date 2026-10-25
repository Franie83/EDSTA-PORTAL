@extends('layouts.app')
@section('title', 'My Tourism Permits')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-blue">My Tourism Permits</h2>
        <a href="{{ route('dashboard.permits.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg"></i> Apply for New Permit</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-4">
        @forelse($permits as $permit)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold text-blue mb-0">{{ $permit->business_name }}</h5>
                        @if($permit->isExpired())
                            <span class="badge bg-danger">Expired</span>
                        @else
                            <span class="badge bg-success">Valid</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-1">Cert #: <strong>{{ $permit->certificate_number }}</strong></p>
                    <p class="text-muted small mb-1">Expires: {{ $permit->expiry_date->format('M d, Y') }}</p>
                    <a href="{{ route('dashboard.permits.show', $permit->id) }}" class="btn btn-outline-primary btn-sm mt-3 w-100">View / Print Certificate</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">No Permits Found</h4>
            <p class="text-muted">You have not applied for a tourism permit yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection