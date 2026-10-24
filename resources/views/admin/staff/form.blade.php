@extends('layouts.admin')
@section('title', $member->id ? 'Edit Staff' : 'Add Staff')
@section('page_title', $member->id ? 'Edit Staff' : 'Add Staff')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data" action="{{ $member->id ? route('admin.staff.update', $member->id) : route('admin.staff.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label small fw-semibold">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required></div>
                <div class="col-md-6"><label class="form-label small fw-semibold">Role / Title *</label><input type="text" name="role" class="form-control" value="{{ old('role', $member->role) }}" placeholder="e.g. Senior Tourism Officer" required></div>
                <div class="col-md-6"><label class="form-label small fw-semibold">Department</label><input type="text" name="department" class="form-control" value="{{ old('department', $member->department) }}" placeholder="e.g. Operations"></div>
                <div class="col-md-6"><label class="form-label small fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}"></div>
                <div class="col-md-6">
    <label class="form-label small fw-semibold">Photo (Upload)</label>
    <input type="file" name="image_file" class="form-control" accept="image/*">
    <small class="text-muted">Upload a JPG or PNG (max 2MB)</small>
</div>
<div class="col-md-6">
    <label class="form-label small fw-semibold">Photo URL (Alternative)</label>
    <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $member->image_url) }}" placeholder="https://...">
</div>
                <div class="col-12"><label class="form-label small fw-semibold">Short Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $member->bio) }}</textarea></div>
                <div class="col-md-4"><label class="form-label small fw-semibold">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $member->sort_order ?? 0) }}"></div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-gold">{{ $member->id ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection