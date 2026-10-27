@extends('layouts.app')
@section('title', 'Register – Edo Tourism')
@section('content')
@php
$heroBg = !empty($settings['hero_image']) ? \App\Helpers\Helpers::imageUrl($settings['hero_image']) : 'https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600';
@endphp
<section class="hero-section" style="min-height: 100vh;">
  <div class="hero-bg" style="background-image:url('{{ $heroBg }}')"></div>
  <div class="hero-overlay"></div>
  <div class="min-vh-100 d-flex align-items-center w-100 position-relative" style="z-index: 2;">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">
        <div class="text-center mb-4">
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-white rounded-circle shadow" style="width:100px; height:100px;">
              <img src="{{ \App\Helpers\Helpers::imageUrl(\App\Helpers\Helpers::setting('site_logo') ?? '') }}" alt="Logo" style="width:70px; height:auto;">
          </div>
          <h2 class="text-white fw-bold" style="font-family:'Playfair Display',serif">Create Account</h2>
          <p class="text-white-50 small">Join the Edo Tourism community</p>
        </div>
        <div class="card border-0 shadow-lg" style="border-radius:16px">
          <div class="card-body p-4">
            @if($errors->any())
            <div class="alert alert-danger py-2 small">
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
            <form method="POST" action="{{ route('auth.register_post') }}">
              @csrf
              <div class="row g-3">
                <div class="col-6">
                  <label class="form-label fw-semibold small">First Name</label>
                  <input type="text" name="first_name" class="form-control bg-blue-100 border-0" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold small">Last Name</label>
                  <input type="text" name="last_name" class="form-control bg-blue-100 border-0" value="{{ old('last_name') }}" required>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">Email Address</label>
                  <input type="email" name="email" class="form-control bg-blue-100 border-0" value="{{ old('email') }}" required>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">Phone (optional)</label>
                  <input type="text" name="phone" class="form-control bg-blue-100 border-0" value="{{ old('phone') }}">
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">I am a…</label>
                  <select name="role" class="form-select bg-blue-100 border-0" required>
                    <option value="">Select your role</option>
                    <option value="Tourist" {{ old('role')=='Tourist'?'selected':'' }}>Tourist — Explore & Book</option>
                    <option value="Guide" {{ old('role')=='Guide'?'selected':'' }}>Tour Guide — Offer services</option>
                    <option value="Hotel" {{ old('role')=='Hotel'?'selected':'' }}>Hotel Manager — List property</option>
                    <option value="Restaurant" {{ old('role')=='Restaurant'?'selected':'' }}>Restaurant Owner — List restaurant</option>
                    <option value="Event Organizer" {{ old('role')=='Event Organizer'?'selected':'' }}>Event Organizer — List events</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">Password</label>
                  <input type="password" name="password" class="form-control bg-blue-100 border-0" placeholder="Min 6 characters" required>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold small">Confirm Password</label>
                  <input type="password" name="password_confirmation" class="form-control bg-blue-100 border-0" required>
                </div>
              </div>
              <button type="submit" class="btn btn-gold w-100 fw-semibold py-2 mt-4">Create Account <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
          </div>
          <div class="card-footer bg-gray-50 text-center py-3" style="border-radius:0 0 16px 16px">
            <small>Already have an account? <a href="{{ route('auth.login') }}" class="text-blue fw-semibold">Sign in</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection