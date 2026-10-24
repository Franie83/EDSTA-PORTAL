@extends('layouts.admin')
@section('title', 'Staff Directory')
@section('page_title', 'Manage Staff')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Tourism Staff</h5>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-gold btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Staff</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Order</th><th>Name</th><th>Role</th><th>Department</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                <tr>
                    <td>{{ $s->sort_order }}</td>
                    <td><div class="d-flex align-items-center gap-2">
                        @if($s->image_url)<img src="{{ $s->image_url }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover">@else<i class="bi bi-person-circle fs-4 text-muted"></i>@endif
                        <span class="fw-semibold">{{ $s->name }}</span></div></td>
                    <td>{{ $s->role }}</td>
                    <td>{{ $s->department ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.staff.edit', $s->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.staff.delete', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No staff members yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection