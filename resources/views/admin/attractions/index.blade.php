@extends('layouts.admin')
@section('title', 'Attractions – Admin')
@section('page_title', 'Attractions')
@section('page_subtitle', 'Manage tourism attractions')
@section('content')

{{-- ==================== BULK UPLOAD UI ==================== --}}
<div class="alert alert-light border shadow-sm mb-3 d-flex justify-content-between align-items-center">
  <div>
    <h6 class="mb-1 fw-bold"><i class="bi bi-file-earmark-excel text-success me-2"></i>Bulk Import Attractions</h6>
    <small class="text-muted">Upload an Excel or CSV file to add multiple attractions at once.</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.attraction_template') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-download me-1"></i>Template
    </a>
    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
      <i class="bi bi-upload me-1"></i>Upload File
    </button>
  </div>
</div>

{{-- ==================== BULK UPLOAD MODAL ==================== --}}
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('admin.attraction_bulk_upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Upload Attractions File</h5>
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
    <input type="search" name="q" class="form-control form-control-sm" placeholder="Search attractions…" value="{{ request('q') }}" style="width:220px">
    <button class="btn btn-blue btn-sm">Filter</button>
  </form>
  <div class="d-flex gap-2">
    {{-- Add Attraction --}}
    <a href="{{ route('admin.attraction_create') }}" class="btn btn-gold btn-sm"><i class="bi bi-plus me-1"></i>Add Attraction</a>
    {{-- Regenerate All QR --}}
    <form method="POST" action="{{ route('admin.attractions.generate_all_qr') }}" class="d-inline">
      @csrf
      <button class="btn btn-outline-primary btn-sm" title="Regenerate QR codes for all attractions" onclick="return confirm('This will regenerate QR codes for ALL attractions. Proceed?')">
        <i class="bi bi-qr-code me-1"></i>Regenerate All QR
      </button>
    </form>
  </div>
</div>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>User</th>
            <th>Price</th>
            <th>Views</th>
            <th>Featured</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($attractions as $a)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="{{ \App\Helpers\Helpers::imageUrl($a->image_url) }}"
                   width="40" height="35" style="object-fit:cover;border-radius:6px" alt="{{ $a->name }}">
              <span class="fw-semibold small">{{ $a->name }}</span>
            </div>
          </td>
          <td class="small">{{ $a->category?->name ?? '—' }}</td>
          <td class="small">{{ $a->user?->full_name ?? '—' }}</td>
          <td class="small text-gold fw-semibold">{{ $a->ticket_price == 0 ? 'Free' : '₦'.number_format($a->ticket_price) }}</td>
          <td class="small">{{ number_format($a->views) }}</td>
          <td>@if($a->is_featured)<span class="badge bg-gold text-dark">Featured</span>@else<span class="text-muted">—</span>@endif</td>
          <td><span class="status-badge badge {{ $a->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $a->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.attraction_edit', $a->id) }}" class="btn btn-outline-secondary btn-sm py-0"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('admin.attraction_regen_qr', $a->id) }}" class="d-inline">@csrf<button class="btn btn-outline-info btn-sm py-0" title="Regenerate QR"><i class="bi bi-qr-code"></i></button></form>
              <form method="POST" action="{{ route('admin.attraction_delete', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this attraction?')">@csrf<button class="btn btn-outline-danger btn-sm py-0"><i class="bi bi-trash"></i></button></form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-4 text-muted">No attractions found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $attractions->withQueryString()->links() }}</div>
  </div>
</div>
@endsection