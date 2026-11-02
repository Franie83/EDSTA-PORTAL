@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')
@section('page_subtitle', 'Manage enquiries submitted through the public website')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <a href="{{ route('admin.contact-messages.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body">
                <div class="small text-muted">All Messages</div>
                <div class="fs-3 fw-bold text-dark">{{ number_format($counts['all']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body">
                <div class="small text-muted">New</div>
                <div class="fs-3 fw-bold text-danger">{{ number_format($counts['new']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body">
                <div class="small text-muted">Read</div>
                <div class="fs-3 fw-bold text-primary">{{ number_format($counts['read']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.contact-messages.index', ['status' => 'resolved']) }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body">
                <div class="small text-muted">Resolved</div>
                <div class="fs-3 fw-bold text-success">{{ number_format($counts['resolved']) }}</div>
            </div>
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="row g-2 mb-4">
            <div class="col-md-6">
                <input
                    type="search"
                    name="q"
                    class="form-control"
                    value="{{ request('q') }}"
                    placeholder="Search name, email, subject or message..."
                >
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach(['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'resolved' => 'Resolved'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Date Received</th>
                        <th>Status</th>
                        <th>Email Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $message->name }}</div>
                                <small class="text-muted">{{ $message->email }}</small>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($message->subject, 55) }}</td>
                            <td>
                                <small>{{ $message->created_at?->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $message->created_at?->format('h:i A') }}</small>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'new' => 'bg-danger',
                                        'read' => 'bg-primary',
                                        'replied' => 'bg-info text-dark',
                                        'resolved' => 'bg-success',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$message->status] ?? 'bg-secondary' }}">
                                    {{ ucfirst($message->status) }}
                                </span>
                            </td>
                            <td>
                                @if($message->email_status === 'sent')
                                    <span class="badge bg-success">Accepted</span>
                                @elseif($message->email_status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($message->email_status) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No contact messages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <small class="text-muted">
                Showing {{ $messages->firstItem() ?? 0 }} to {{ $messages->lastItem() ?? 0 }}
                of {{ $messages->total() }} messages
            </small>
            {{ $messages->links() }}
        </div>
    </div>
</div>

@endsection