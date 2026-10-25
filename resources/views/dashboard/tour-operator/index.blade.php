@extends('layouts.app')

@section('title', 'My Attractions - Edo Tourism')
@section('content')

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li><li class="breadcrumb-item active">My Attractions</li></ol></nav>
    <h1>My Attractions</h1>
    <p class="mb-0 text-white-50">Manage the attractions you have registered with the Tourism Agency.</p>
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
    <div>
      <h5 class="fw-bold text-blue mb-0"><i class="bi bi-building me-2"></i>My Attractions</h5>
      <small class="text-muted">New attractions require admin approval. Only admins can delete attractions.</small>
    </div>
    <a href="{{ route('dashboard.tour-operator.create') }}" class="btn btn-gold">
      <i class="bi bi-plus-lg me-1"></i>Add New Attraction
    </a>
  </div>

  @if(isset($attractions) && $attractions->count())
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Category</th>
              <th>City</th>
              <th>Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($attractions as $attraction)
              <tr>
                <td class="fw-semibold">{{ $attraction->name }}</td>
                <td>{{ $attraction->category?->name ?? '—' }}</td>
                <td>{{ $attraction->city ?? '—' }}</td>
                <td>
                  @if($attraction->is_active)
                    <span class="badge bg-success">Active</span>
                  @else
                    <span class="badge bg-warning text-dark">Pending Approval</span>
                  @endif
                </td>
                <td class="small text-muted">{{ $attraction->created_at?->format('d M Y') }}</td>
                <td>
                  <a href="{{ route('dashboard.tour-operator.edit', $attraction->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="text-center py-5 bg-white rounded shadow-sm">
      <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
      <h5 class="mt-3">No attractions yet</h5>
      <p class="text-muted">Add your first attraction to get started. It will be reviewed by an admin before appearing publicly.</p>
      <a href="{{ route('dashboard.tour-operator.create') }}" class="btn btn-gold mt-2">
        <i class="bi bi-plus-lg me-1"></i>Add Your First Attraction
      </a>
    </div>
  @endif
</div>

@endsection