@extends('layouts.app')
@section('title', 'Apply for Permit')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-blue mb-4">Apply for Annual Tourism Permit</h3>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

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
                        <input type="hidden" name="action" value="generate">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Address *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address ?? '') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Amount to Pay (&#8358;)</label>
                            <input type="text" id="displayAmount" class="form-control form-control-lg bg-light" value="Select a facility" readonly>
                            <small class="text-muted">Price is fixed by the Agency based on the facility type.</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-shield-check"></i> <strong>Secure Payment:</strong> All details are locked. Click below to pay and generate your official tourism permit.
                        </div>

                        <button type="submit" id="permitPaymentButton" class="btn btn-gold btn-lg w-100" disabled>Pay &#8358;<span id="btnAmount">0</span> & Generate Permit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const prices = @json($prices);
    const select = document.getElementById('facilitySelect');
    const facilityId = document.getElementById('facilityId');
    const businessName = document.getElementById('businessName');
    const businessType = document.getElementById('businessType');
    const displayAmount = document.getElementById('displayAmount');
    const buttonAmount = document.getElementById('btnAmount');
    const paymentButton = document.getElementById('permitPaymentButton');

    function updatePermitPrice() {
        if (!select) return;

        const option = select.options[select.selectedIndex];
        const id = select.value;

        if (!id || !option) {
            facilityId.value = '';
            businessName.value = '';
            businessType.value = '';
            displayAmount.value = 'Select a facility';
            buttonAmount.textContent = '0';
            paymentButton.disabled = true;
            return;
        }

        const type = option.getAttribute('data-type');
        const name = option.getAttribute('data-name');
        const rawPrice = prices[type];
        const price = Number(rawPrice);

        facilityId.value = id;
        businessName.value = name || '';
        businessType.value = type || '';

        if (
            rawPrice === null ||
            rawPrice === undefined ||
            rawPrice === '' ||
            !Number.isFinite(price) ||
            price <= 0
        ) {
            displayAmount.value = 'Fee not configured';
            buttonAmount.textContent = 'Unavailable';
            paymentButton.disabled = true;
            return;
        }

        const formattedPrice = price.toLocaleString('en-NG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        displayAmount.value = formattedPrice;
        buttonAmount.textContent = formattedPrice;
        paymentButton.disabled = false;
    }

    if (select) {
        select.addEventListener('change', updatePermitPrice);
        updatePermitPrice();
    }
</script>
@endsection