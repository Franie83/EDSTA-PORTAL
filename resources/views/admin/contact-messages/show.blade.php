@extends('layouts.admin')

@section('title', 'View Contact Message')
@section('page_title', 'Contact Message')
@section('page_subtitle', 'Read and manage this enquiry')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Inbox
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $contactMessage->subject }}</h4>
                        <small class="text-muted">
                            Received {{ $contactMessage->created_at?->format('d M Y, h:i A') }}
                        </small>
                    </div>
                    <span class="badge bg-primary">{{ ucfirst($contactMessage->status) }}</span>
                </div>

                <div class="border rounded-3 p-3 bg-light">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Sender</small>
                            <strong>{{ $contactMessage->name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email Address</small>
                            <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted d-block mb-2">Message</small>
                    <div style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $contactMessage->message }}</div>
                </div>

                <div class="mt-4">
                    <a
    href="https://mail.google.com/mail/?view=cm&fs=1&to={{ rawurlencode($contactMessage->email) }}&su={{ rawurlencode('Re: ' . $contactMessage->subject) }}"
    class="btn btn-primary"
    target="_blank"
    rel="noopener noreferrer"
>
    <i class="bi bi-envelope me-1"></i>Reply by Email
</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">Manage Message</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.contact-messages.status', $contactMessage->id) }}">
                    @csrf
                    @method('PATCH')

                    <label for="status" class="form-label">Message Status</label>
                    <select name="status" id="status" class="form-select mb-3" required>
                        @foreach(['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'resolved' => 'Resolved'] as $value => $label)
                            <option value="{{ $value }}" @selected($contactMessage->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold text-danger">Delete Message</h6>
                <p class="small text-muted">This permanently deletes this enquiry from the database.</p>

                <form method="POST"
                      action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}"
                      onsubmit="return confirm('Permanently delete this contact message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i>Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
