@extends('layouts.app')
@section('title', $club->name . ' - Edo Tourism')
@section('content')
<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('clubs.list') }}">Clubs & Lounges</a></li><li class="breadcrumb-item active">{{ $club->name }}</li></ol></nav>
    <h1>{{ $club->name }}</h1>
    <div class="d-flex gap-3 flex-wrap mt-2">
      <span class="text-white-50 small"><i class="bi bi-geo-alt me-1"></i>{{ $club->address }}</span>
    </div>
  </div>
</div>

<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-8">
      @if($club->image_url)
        <div class="detail-image mb-4"><img src="{{ \App\Helpers\Helpers::imageUrl($club->image_url) }}" class="img-fluid rounded-3" alt="{{ $club->name }}"></div>
      @endif
      <div class="mb-4">
        <h3 class="text-blue fw-bold">About {{ $club->name }}</h3>
        <p class="text-muted" style="white-space:pre-line">{{ $club->description }}</p>
      </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
      <div class="info-card mb-3">
        <h5 class="fw-bold text-blue mb-3">Info</h5>
        @if($club->address)<p class="small mb-1"><i class="bi bi-geo-alt me-2 text-gold"></i>{{ $club->address }}</p>@endif
        @if($club->opening_hours)<p class="small mb-1"><i class="bi bi-clock me-2 text-gold"></i>{{ $club->opening_hours }}</p>@endif
        @if($club->phone)<p class="small mb-1"><i class="bi bi-telephone me-2 text-gold"></i>{{ $club->phone }}</p>@endif
        
        @if($club->latitude && $club->longitude)
          <div class="mt-3 pt-3 border-top">
            <button type="button" class="btn btn-gold w-100" onclick="getDirections({{ $club->latitude }}, {{ $club->longitude }}, '{{ addslashes($club->name) }}')">
              <i class="bi bi-geo-fill me-2"></i>Get Directions
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection