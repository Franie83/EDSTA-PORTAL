@extends('layouts.admin')
@section('title', 'Categories – Admin')
@section('page_title', 'Categories')
@section('content')
<div class="row g-4">
  <div class="col-md-7">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    <div class="card border-0 shadow-sm"><div class="card-body p-0">
      <div class="table-responsive"><table class="table table-hover mb-0 admin-table">
        <thead><tr><th>Icon</th><th>Name</th><th>Color</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr>
          <td style="color:{{ $cat->color ?? '#1a3a6b' }};font-size:1.4rem"><i class="bi {{ $cat->icon ?? 'bi-building' }}"></i></td>
          <td class="fw-semibold small">{{ $cat->name }}</td>
          <td><span style="display:inline-block;width:20px;height:20px;border-radius:4px;background:{{ $cat->color ?? '#1a3a6b' }}"></span></td>
          <td class="small">{{ $cat->sort_order }}</td>
          <td><span class="status-badge badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td class="text-nowrap">
            <a href="{{ route('admin.category_edit', $cat->id) }}" class="btn btn-outline-primary btn-sm py-0"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('admin.category_delete', $cat->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
              @csrf
              <button class="btn btn-outline-danger btn-sm py-0"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="6" class="text-center py-4 text-muted">No categories.</td></tr>
        @endforelse
        </tbody>
      </table></div>
    </div></div>
  </div>
  <div class="col-md-5">
    @if(isset($editCategory))
      <div class="card border-0 shadow-sm border-start border-4 border-warning">
        <div class="card-header bg-warning bg-opacity-10 fw-bold text-dark border-0 pt-3 pb-1 d-flex justify-content-between">
          <span><i class="bi bi-pencil-square me-1"></i>Edit Category</span>
          <a href="{{ route('admin.categories') }}" class="small text-muted text-decoration-none"><i class="bi bi-x-circle"></i> Cancel</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.category_update', $editCategory->id) }}">
            @csrf
            <div class="mb-2"><label class="form-label fw-semibold small">Name *</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $editCategory->name) }}" required>
            </div>
            <div class="row g-2 mb-2">
              <div class="col-6"><label class="form-label fw-semibold small">Icon</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $editCategory->icon) }}" placeholder="bi-building-fill">
              </div>
              <div class="col-6"><label class="form-label fw-semibold small">Color</label>
                <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $editCategory->color ?? '#1a3a6b') }}">
              </div>
            </div>
            <div class="mb-2"><label class="form-label fw-semibold small">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $editCategory->sort_order) }}">
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $editCategory->is_active) ? 'checked' : '' }}>
              <label class="form-check-label small">Active</label>
            </div>
            <button class="btn btn-warning btn-sm w-100 fw-bold">Update Category</button>
          </form>
        </div>
      </div>
    @else
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-blue border-0 pt-3 pb-1">Add Category</div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.category_store') }}">
            @csrf
            <div class="mb-2"><label class="form-label fw-semibold small">Name *</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="row g-2 mb-2">
              <div class="col-6"><label class="form-label fw-semibold small">Icon (Bootstrap Icons class)</label>
                <input type="text" name="icon" class="form-control" placeholder="bi-building-fill">
              </div>
              <div class="col-6"><label class="form-label fw-semibold small">Color</label>
                <input type="color" name="color" class="form-control form-control-color" value="#1a3a6b">
              </div>
            </div>
            <div class="mb-2"><label class="form-label fw-semibold small">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="is_active" checked>
              <label class="form-check-label small">Active</label>
            </div>
            <button class="btn btn-gold btn-sm w-100">Add Category</button>
          </form>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection