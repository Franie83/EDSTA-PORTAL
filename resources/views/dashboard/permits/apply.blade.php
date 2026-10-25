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
                            <label class="form-label fw-semibold">Select Facility *</label>
                            <select name="facility_select" id="facilitySelect" class="form-select" required>
                                <option value="" disabled selected>-- Choose a facility --</option>
                                @foreach($facilities as $fac)
                                    <option value="{{ $fac['id'] }}" data-type="{{ $fac['type'] }}" data-name="{{ $fac['name'] }}">
                                        {{ $fac['name'] }} ({{ $fac['type'] }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select the specific business you want to permit.</small>
                        </div>

                        <!-- Hidden fields populated by JS -->
                        <input type="hidden" name="facility_id" id="facilityId">
                        <input type="hidden" name="business_name" id="businessName">
                        <input type="hidden" name="business_type" id="businessType">
                        <input type="hidden" name="amount_paid" id="amountPaid">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Address *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address ?? '') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Amount to Pay (₦)</label>
                            <input type="text" id="displayAmount" class="form-control form-control-lg bg-light" value="0" readonly>
                            <small class="text-muted">Price is fixed by the Agency based on the facility type.</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-shield-check"></i> <strong>Secure Payment:</strong> All details are locked. Click below to pay and generate your official certificate.
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg w-100">Pay ₦<span id="btnAmount">0</span> & Generate Certificate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const prices = @json($prices);
    const select = document.getElementById('facilitySelect');
    
    select.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const type = option.getAttribute('data-type');
        const name = option.getAttribute('data-name');
        const id = this.value;
        
        const price = prices[type] || 20000;
        
        document.getElementById('facilityId').value = id;
        document.getElementById('businessName').value = name;
        document.getElementById('businessType').value = type;
        document.getElementById('amountPaid').value = price;
        
        document.getElementById('displayAmount').value = Number(price).toLocaleString();
        document.getElementById('btnAmount').textContent = Number(price).toLocaleString();
    });
</script>
@endsection