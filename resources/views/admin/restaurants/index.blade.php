@extends('layouts.admin')
@section('title', 'Restaurants – Admin')
@section('page_title', 'Restaurants')
@section('content')

{{-- ==================== BULK UPLOAD UI ==================== --}}
<div class="alert alert-light border shadow-sm mb-3 d-flex justify-content-between align-items-center">
  <div>
    <h6 class="mb-1 fw-bold"><i class="bi bi-file-earmark-excel text-success me-2"></i>Bulk Import Restaurants</h6>
    <small class="text-muted">Upload an Excel or CSV file to add multiple restaurants at once.</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.restaurant_template') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-download me-1"></i>Template
    </a>
    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModalRestaurants">
      <i class="bi bi-upload me-1"></i>Upload File
    </button>
  </div>
</div>

{{-- ==================== BULK UPLOAD MODAL ==================== --}}
<div class="modal fade" id="bulkUploadModalRestaurants" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('admin.restaurant_bulk_upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Upload Restaurants File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Select Excel or CSV File</label>
          <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
          <small class="text-muted">Accepted: .xlsx, .xls, .csv (max 5 MB).</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Upload &amp; Import</button>
      </div>
    </form>
  </div>
</div>
{{-- ==================== END BULK UPLOAD UI ==================== --}}

<div class="d-flex justify-content-between align-items-center mb-3">
  <form class="d-flex gap-2" method="GET">
    <input type="search" name="q" class="form-control form-control-sm" placeholder="Search restaurants…" value="{{ request('q') }}" style="width:200px">
    <select name="status" class="form-select form-select-sm" style="width:auto">
      <option value="">All Statuses</option>
      <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
      <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Pending</option>
    </select>
    <button class="btn btn-blue btn-sm">Filter</button>
  </form>
  <a href="{{ route('admin.restaurant_create') }}" class="btn btn-gold btn-sm"><i class="bi bi-plus me-1"></i>Add Restaurant</a>
</div>

<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover mb-0 admin-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Cuisine</th>
        <th>City</th>
        <th>User</th>
        <th>Featured</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($restaurants as $r)
    <tr>
      <td class="fw-semibold small">{{ $r->name }}</td>
      <td class="small">{{ $r->cuisine_type }}</td>
      <td class="small">{{ $r->city }}</td>
      <td class="small">{{ $r->user?->full_name ?? '—' }}</td>
      <td>@if($r->is_featured)<span class="badge bg-gold text-dark">Featured</span>@else<span class="text-muted">—</span>@endif</td>
      <td><span class="status-badge badge {{ $r->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $r->is_active ? 'Active' : 'Inactive' }}</span></td>
      <td>
        <div class="d-flex gap-1">
          <a href="{{ route('admin.restaurant_edit', $r->id) }}" class="btn btn-outline-secondary btn-sm py-0"><i class="bi bi-pencil"></i></a>
          @if(!$r->is_active)
            <form method="POST" action="{{ route('admin.restaurant_approve', $r->id) }}" class="d-inline">
              @csrf
              <button class="btn btn-success btn-sm py-0" title="Approve"><i class="bi bi-check-lg"></i></button>
            </form>
          @endif
          <form method="POST" action="{{ route('admin.restaurant_delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this restaurant?')">
            @csrf
            <button class="btn btn-outline-danger btn-sm py-0"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @empty<tr><td colspan="7" class="text-center py-4 text-muted">No restaurants.</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-3">{{ $restaurants->withQueryString()->links() }}</div>
</div></div>
@endsection