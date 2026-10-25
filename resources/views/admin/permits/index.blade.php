@extends('layouts.admin')
@section('title', 'Tourism Permits')
@section('page_title', 'Issued Certificates')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Certificates</h5>
    <a href="{{ route('admin.permits.create') }}" class="btn btn-gold btn-sm"><i class="bi bi-plus-lg"></i> Issue New</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Cert #</th><th>Business</th><th>Type</th><th>Expiry</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($permits as $p)
                <tr>
                    <td><code>{{ $p->certificate_number }}</code></td>
                    <td class="fw-semibold">{{ $p->business_name }}</td>
                    <td>{{ $p->business_type }}</td>
                    <td>{{ $p->expiry_date->format('M d, Y') }}</td>
                    <td>
                        @if($p->isExpired()) <span class="badge bg-danger">Expired</span>
                        @else <span class="badge bg-success">Valid</span> @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.permits.show', $p->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.permits.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.permits.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete permanently?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button></form>
                        @if(!$p->isExpired())
                        <form action="{{ route('admin.permits.expire', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Force expire for testing?')">@csrf<button class="btn btn-sm btn-outline-warning" title="Test Expiration"><i class="bi bi-hourglass-split"></i></button></form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No permits issued yet. Click "Issue New" to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection