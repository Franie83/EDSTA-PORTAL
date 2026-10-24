@extends('layouts.app')

@section('title', 'My Club / Lounge - ' . ($settings['site_name'] ?? 'Edo Tourism'))
@section('content')

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li><li class="breadcrumb-item active">My Club / Lounge</li></ol></nav>
    <h1>My Club / Lounge</h1>
    <p class="mb-0 text-white-50">Manage your nightlife venue profile and offerings.</p>
  </div>
</div>

<div class="container py-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif
  @if(session('danger'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('danger') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-blue mb-0"><i class="bi bi-music-note-beamed me-2"></i>My Venues</h5>
    <small class="text-muted">New venues require admin approval before they appear publicly.</small>
  </div>

  @if($clubs->count())
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($clubs as $club)
            <tr>
              <td>{{ $club->id }}</td>
              <td><strong>{{ $club->name }}</strong></td>
              <td class="small">{{ $club->address ?? '—' }}</td>
              <td class="small">{{ $club->phone ?? '—' }}</td>
              <td>
                @if($club->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-warning text-dark">Pending Approval</span>
                @endif
              </td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-secondary" title="Edit (coming soon)"><i class="bi bi-pencil"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <i class="bi bi-music-note-beamed fs-1 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold mb-2">No club / lounge listings yet</h5>
        <p class="text-muted mb-3">When you register a nightlife venue, it will appear here.</p>
        <p class="text-muted small mb-0">Contact the Edo State Tourism Agency admin to have your venue listed.</p>
      </div>
    </div>
  @endif
</div>

@endsection