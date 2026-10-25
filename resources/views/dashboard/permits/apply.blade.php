@extends('layouts.app')
@section('title', 'Apply for Permit')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-blue mb-4">Apply for Annual Tourism Permit</h3>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dashboard.permits.process') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Name *</label>
                            <input type="text" name="business_name" class="form-control" value="{{ Auth::user()->first_name }}'s Business" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Type *</label>
                            <select name="business_type" class="form-select" required>
                                <option value="Hotel">Hotel</option>
                                <option value="Restaurant">Restaurant</option>
                                <option value="Club">Club / Lounge</option>
                                <option value="Tour Operator">Tour Operator</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Address *</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Amount to Pay (₦) *</label>
                            <input type="number" name="amount_paid" class="form-control form-control-lg" placeholder="e.g. 50000" required>
                            <small class="text-muted">Standard fee applies based on business type.</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-shield-check"></i> <strong>Secure Payment:</strong> Clicking the button below will simulate a successful payment and instantly generate your official certificate.
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg w-100">Pay & Generate Certificate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection