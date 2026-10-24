@extends('layouts.app')
@section('title', 'Contact - Edo Tourism')
@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <h2 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">Send us a Message</h2>
            <form action="#" method="POST" onsubmit="alert('Contact form is not wired up yet.'); return false;">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Your Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="col-12"><label class="form-label">Subject *</label><input type="text" name="subject" class="form-control" required></div>
                    <div class="col-12"><label class="form-label">Message *</label><textarea name="message" rows="6" class="form-control" required></textarea></div>
                    <div class="col-12 mt-4"><button type="submit" class="btn btn-gold px-4 py-2" style="border-radius: 8px;">Send Message</button></div>
                </div>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">EDSTA Headquarters</h4>
                    <p class="mb-3 text-muted"><i class="bi bi-geo-alt-fill text-gold me-2"></i>{{ $settings['contact_address'] ?? 'EDSTA HQ, Government House Road, GRA, Benin City' }}</p>
                    <p class="mb-3 text-muted"><i class="bi bi-telephone-fill text-gold me-2"></i>{{ $settings['contact_phone'] ?? '+234 (0) 52 255 000' }}</p>
                    <p class="mb-4 text-muted"><i class="bi bi-envelope-fill text-gold me-2"></i>{{ $settings['contact_email'] ?? 'info@edsta.edo.gov.ng' }}</p>
                    <hr>
                    <h6 class="fw-bold mb-3 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 1px;">Office Hours</h6>
                    <p class="mb-1 text-muted">Monday - Friday: 8:00am - 5:00pm</p>
                    <p class="mb-0 text-muted">Saturday: 9:00am - 1:00pm</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection