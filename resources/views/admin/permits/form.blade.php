@extends('layouts.admin')
@section('title', 'Issue Permit')
@section('page_title', 'Issue New Permit')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form method="POST" action="{{ $permit->id ? route('admin.permits.update', $permit->id) : route('admin.permits.store') }}">
            @csrf
            @if($permit->id) @method('PUT') @endif
            
            <div class="mb-3"><label class="form-label small fw-semibold">Business Name *</label><input type="text" name="business_name" class="form-control" value="{{ old('business_name', $permit->business_name) }}" required></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Business Type *</label>
                <select name="business_type" class="form-select" required>
                    <option value="Hotel" {{ old('business_type', $permit->business_type) == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                    <option value="Restaurant" {{ old('business_type', $permit->business_type) == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                    <option value="Club" {{ old('business_type', $permit->business_type) == 'Club' ? 'selected' : '' }}>Club / Lounge</option>
                    <option value="Tour Operator" {{ old('business_type', $permit->business_type) == 'Tour Operator' ? 'selected' : '' }}>Tour Operator</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label small fw-semibold">Owner/Manager Name</label><input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $permit->owner_name) }}"></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Address</label><input type="text" name="address" class="form-control" value="{{ old('address', $permit->address) }}"></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Amount Paid (₦) *</label><input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid', $permit->amount_paid) }}" required></div>
            
            @if($permit->id)
            <div class="mb-3">
                <label class="form-label small fw-semibold">Expiry Date *</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $permit->expiry_date->format('Y-m-d')) }}" required>
                <small class="text-muted">Set to a past date to test expired logic.</small>
            </div>
            @endif
            
            <button class="btn btn-gold w-100 mt-3">{{ $permit->id ? 'Update Certificate' : 'Generate Certificate' }}</button>
        </form>
    </div></div>
</div></div>
@endsection