@extends('layouts.app')

@section('title', 'About EDSTA - Edo Tourism')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg" style="border-radius: 24px; background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px);">
                <div class="card-body p-5">
                    
                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">About EDSTA</h1>
                        <p class="lead text-muted">Discover the heart of Edo State</p>
                        <div class="mx-auto mt-4" style="width: 80px; height: 4px; background: var(--gold-500); border-radius: 2px;"></div>
                    </div>

                    {{-- Mission & Logo Card --}}
                    <div class="row g-5 align-items-center mb-5">
                        <div class="col-md-7">
                            <h2 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">Our Mission</h2>
                            <p class="lead text-dark" style="line-height: 1.8;">
                                {{ $settings['about_mission'] ?? 'To position Edo State as the premier tourism destination in Nigeria, preserving our rich cultural heritage while driving sustainable economic growth and job creation.' }}
                            </p>
                        </div>
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm text-center h-100" style="border-radius: 16px; background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-800) 100%);">
                                <div class="card-body p-4 d-flex flex-column justify-content-center">
                                    @if(!empty($settings['site_logo']))
                                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded shadow-sm" style="width:120px; height:120px;">
    {{-- Switch back to the original JPG for sharpness --}}
    <img src="{{ asset('storage/cms/A5hmYtSfi6ptkFzKdl1VPeXF6SOkxQACuowGdbm0.jpg') }}" alt="Logo" style="width:90px; height:auto;">
</div>
                                    @else
                                        <i class="bi bi-compass-fill text-gold mb-3" style="font-size: 3rem;"></i>
                                    @endif
                                    <h4 class="fw-bold mb-2 text-white">Explore the Cradle</h4>
                                    <p class="text-white-50 small mb-0">From the ancient walls of the Benin Kingdom to the vibrant festivals of today, Edo State is a living museum of African culture.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Vision --}}
                    <div class="row mb-5">
                        <div class="col-12">
                            <h3 class="mb-4" style="font-family: 'Playfair Display', serif; color: var(--blue-900);">Our Vision</h3>
                            <div class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                                {!! nl2br(e($settings['about_text'] ?? 'The Edo State Tourism Agency (EDSTA) was established to guide, preserve, license, and promote the natural landmarks, cultural festivals, and historical heritage monuments of Edo State.')) !!}
                            </div>
                        </div>
                    </div>

                    <hr class="my-5 border-light">

                    {{-- Contact Cards --}}
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light text-center p-4" style="border-radius: 16px;">
                                <i class="bi bi-geo-alt text-gold mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold text-dark">Visit Us</h5>
                                <p class="text-muted small mb-0">11 Agemomwen Street, Central Road, GRA, Benin City, Edo State, Nigeria</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light text-center p-4" style="border-radius: 16px;">
                                <i class="bi bi-telephone text-gold mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold text-dark">Call Us</h5>
                                <p class="text-muted small mb-0">07014826314</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light text-center p-4" style="border-radius: 16px;">
                                <i class="bi bi-envelope text-gold mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold text-dark">Email Us</h5>
                                <p class="text-muted small mb-0">info@edostate.gov.ng</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
  /* Dark Mode Overrides for the About Page Card */
  [data-theme="dark"] .card[style*="rgba(255, 255, 255, 0.98)"] {
      background: rgba(30, 41, 59, 0.98) !important;
  }
  [data-theme="dark"] h1, 
  [data-theme="dark"] h2, 
  [data-theme="dark"] h3, 
  [data-theme="dark"] h5 {
      color: #f8fafc !important;
  }
  [data-theme="dark"] .text-dark {
      color: #cbd5e1 !important;
  }
  [data-theme="dark"] .bg-light {
      background-color: #0f172a !important;
  }
  [data-theme="dark"] .card.bg-light h5 {
      color: #f8fafc !important;
  }
</style>
@endsection
