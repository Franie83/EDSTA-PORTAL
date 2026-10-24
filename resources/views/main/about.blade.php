@extends('layouts.app')

@section('title', 'About EDSTA - Edo Tourism')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">About EDSTA</h1>
        <p class="lead text-muted">Discover the heart of Edo State</p>
    </div>

    <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">Our Mission</h2>
            <p class="lead text-dark">
                {{ $settings['about_mission'] ?? 'To position Edo State as the premier tourism destination in Nigeria, preserving our rich cultural heritage while driving sustainable economic growth and job creation.' }}
            </p>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-800) 100%);">
                <div class="card-body p-5 text-center text-white">
                    <i class="bi bi-compass-fill text-gold mb-3" style="font-size: 3rem;"></i>
                    <h3 class="fw-bold mb-3">Explore the Cradle</h3>
                    <p class="text-white-50">From the ancient walls of the Benin Kingdom to the vibrant festivals of today, Edo State is a living museum of African culture.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">Our Vision</h3>
            <div class="text-muted" style="line-height: 1.8;">
                {!! nl2br(e($settings['about_text'] ?? 'The Edo State Tourism Agency (EDSTA) was established to guide, preserve, license, and promote the natural landmarks, cultural festivals, and historical heritage monuments of Edo State.')) !!}
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <i class="bi bi-geo-alt text-gold mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold">Visit Us</h5>
                <p class="text-muted small mb-0">{{ $settings['contact_address'] ?? 'EDSTA HQ, Government House Road, GRA, Benin City' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <i class="bi bi-telephone text-gold mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold">Call Us</h5>
                <p class="text-muted small mb-0">{{ $settings['contact_phone'] ?? '+234 (0) 52 255 000' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <i class="bi bi-envelope text-gold mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold">Email Us</h5>
                <p class="text-muted small mb-0">{{ $settings['contact_email'] ?? 'info@edsta.edo.gov.ng' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection