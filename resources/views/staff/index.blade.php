@extends('layouts.app')
@section('title', 'Our Team - Edo Tourism')
@section('content')
<div class="page-hero py-5" style="background: linear-gradient(rgba(10,26,51,0.8), rgba(10,26,51,0.8)), url('{{ !empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : 'https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600' }}') center/cover;">
  <div class="container text-center text-white py-4">
    <h1 class="display-5 fw-bold" style="font-family:'Playfair Display',serif">Our Team</h1>
    <p class="lead mb-0">The dedicated staff of the Edo State Tourism Agency</p>
  </div>
</div>
<div class="container py-5">
  <div class="row g-4 justify-content-center">
    @forelse($staff as $member)
      <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm text-center" style="border-radius:16px">
          <div class="card-body p-4">
            @if($member->image_url)
              <img src="{{ $member->image_url }}" class="rounded-circle mx-auto mb-3" style="width:100px;height:100px;object-fit:cover">
            @else
              <div class="rounded-circle bg-blue-100 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px">
                <i class="bi bi-person text-blue" style="font-size:2.5rem"></i>
              </div>
            @endif
            <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
            <p class="text-gold small fw-semibold mb-1">{{ $member->role }}</p>
            @if($member->department)<p class="text-muted small mb-2">{{ $member->department }}</p>@endif
            @if($member->bio)<p class="text-muted small mb-3">{{ $member->bio }}</p>@endif
            @if($member->email)<a href="mailto:{{ $member->email }}" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-envelope me-1"></i>Contact</a>@endif
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5"><p>No staff members to display yet.</p></div>
    @endforelse
  </div>
</div>
@endsection