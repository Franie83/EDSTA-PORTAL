@extends('layouts.admin')
@section('title', 'Guides – Admin')
@section('page_title', 'Tour Guides')
@section('page_subtitle', 'Review and verify guide applications')
@section('content')

{{-- ==================== BULK UPLOAD UI ==================== --}}
<div class="alert alert-light border shadow-sm mb-3 d-flex justify-content-between align-items-center">
  <div>
    <h6 class="mb-1 fw-bold"><i class="bi bi-file-earmark-excel text-success me-2"></i>Bulk Import Guides</h6>
    <small class="text-muted">Upload an Excel or CSV file to add multiple guides at once.</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.guide_template') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-download me-1"></i>Template
    </a>
    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModalGuides">
      <i class="bi bi-upload me-1"></i>Upload File
    </button>
  </div>
</div>

{{-- ==================== BULK UPLOAD MODAL ==================== --}}
<div class="modal fade" id="bulkUploadModalGuides" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('admin.guide_bulk_upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Upload Guides File</h5>
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
  <form method="GET" class="d-flex gap-2">
    <input type="search" name="q" class="form-control form-control-sm" placeholder="Search guides…" value="{{ request('q') }}" style="width:220px">
    <select name="status" class="form-select form-select-sm" style="width:auto">
      <option value="">All Statuses</option>
      @foreach(['Pending','Approved','Rejected'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
      @endforeach
    </select>
    <button class="btn btn-blue btn-sm">Filter</button>
  </form>
  <a href="{{ route('admin.guide_create') }}" class="btn btn-gold btn-sm"><i class="bi bi-plus me-1"></i>Add Guide</a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 admin-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Rate</th>
            <th>Exp</th>
            <th>Languages</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($guides as $g)
        <tr>
          <td>
            <div class="fw-semibold small">{{ $g->user?->full_name }}</div>
            <div class="text-muted" style="font-size:.7rem">{{ $g->user?->email }}</div>
          </td>
          <td class="small text-gold fw-semibold">₦{{ number_format($g->hourly_rate) }}/hr</td>
          <td class="small">{{ $g->experience }} yrs</td>
          <td class="small">{{ Str::limit($g->languages, 30) }}</td>
          <td>
            @if($g->verification_status==='Approved')
              <span class="badge-verified">Approved</span>
            @elseif($g->verification_status==='Pending')
              <span class="badge-pending">Pending</span>
            @else
              <span class="badge-rejected">Rejected</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1">
              @if($g->verification_status !== 'Approved')
              <form method="POST" action="{{ route('admin.guide_verify', $g->id) }}" class="d-inline">
                @csrf
                <input type="hidden" name="status" value="Approved">
                <button class="btn btn-success btn-sm py-0">Approve</button>
              </form>
              @endif
              @if($g->verification_status !== 'Rejected')
              <form method="POST" action="{{ route('admin.guide_verify', $g->id) }}" class="d-inline">
                @csrf
                <input type="hidden" name="status" value="Rejected">
                <button class="btn btn-danger btn-sm py-0">Reject</button>
              </form>
              @endif
              <a href="{{ route('admin.guide_edit', $g->id) }}" class="btn btn-outline-secondary btn-sm py-0"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('admin.guide_delete', $g->id) }}" class="d-inline" onsubmit="return confirm('Delete this guide?')">
                @csrf
                <button class="btn btn-outline-danger btn-sm py-0"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No guides found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $guides->withQueryString()->links() }}</div>
  </div>
</div>
@endsection