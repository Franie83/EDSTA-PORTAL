@extends('layouts.app')
@section('title', 'Clubs & Lounges - Edo Tourism')
@section('content')
<div class="page-hero py-5" style="background: linear-gradient(rgba(10,26,51,0.85), rgba(10,26,51,0.85)), url('{{ !empty($settings['hero_image']) ? \App\Helpers\Helpers::imageUrl($settings['hero_image']) : 'https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600' }}') center/cover;">
  <div class="container text-center text-white py-4">
    <h1 class="display-5 fw-bold" style="font-family:'Playfair Display',serif">Clubs & Lounges</h1>
    <p class="lead mb-0">Experience Edo State's vibrant nightlife</p>
  </div>
</div>
<div class="container py-5">
  <div class="row g-4">
    @forelse($clubs as $club)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
          @if($club->image_url)
            <img src="{{ \App\Helpers\Helpers::imageUrl($club->image_url) }}" class="card-img-top" style="height:200px; object-fit:cover;">
          @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
              <i class="bi bi-music-note-beamed text-muted" style="font-size:3rem;"></i>
            </div>
          @endif
          <div class="card-body">
            <h5 class="fw-bold text-blue mb-2">{{ $club->name }}</h5>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1 text-gold"></i>{{ $club->address }}</p>
            @if($club->description)
              <p class="text-muted small mb-3">{{ Str::limit($club->description, 100) }}</p>
            @endif
            <a href="{{ route('clubs.detail', $club->id) }}" class="btn btn-gold btn-sm w-100">View Details</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5"><p>No clubs or lounges listed yet.</p></div>
    @endforelse
  </div>
</div>
@endsection