@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('content')

<div class="mb-4 d-flex flex-wrap gap-2">
    <a href="{{ route('admin.attractions') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-building me-1"></i>Attractions</a>
    <a href="{{ route('admin.guides', ['status' => 'Pending']) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-person-badge me-1"></i>Pending Guides</a>
    <a href="{{ route('admin.hotels', ['status' => 'inactive']) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-building-fill me-1"></i>Pending Hotels</a>
    <a href="{{ route('admin.restaurants', ['status' => 'inactive']) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-cup-hot me-1"></i>Pending Restaurants</a>
    <a href="{{ route('admin.permits.index') }}" class="btn btn-outline-gold btn-sm"><i class="bi bi-award me-1"></i>Tourism Permits</a>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-people me-1"></i>All Users</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_users']) }}</div><div class="small text-muted">Users</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_attractions']) }}</div><div class="small text-muted">Attractions</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_bookings']) }}</div><div class="small text-muted">Bookings</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_guides']) }}</div><div class="small text-muted">Guides</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_hotels']) }}</div><div class="small text-muted">Hotels</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_restaurants']) }}</div><div class="small text-muted">Restaurants</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_events']) }}</div><div class="small text-muted">Events</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_clubs']) }}</div><div class="small text-muted">Clubs</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number text-gold">{{ number_format($stats['total_permits']) }}</div><div class="small text-muted">Permits</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['total_staff']) }}</div><div class="small text-muted">Staff</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['pending_bookings']) }}</div><div class="small text-muted">Pending Bookings</div></div></div>
    <div class="col-6 col-md-3 col-lg-2"><div class="stat-card"><div class="stat-number">{{ number_format($stats['pending_guides']) }}</div><div class="small text-muted">Pending Guides</div></div></div>
</div>

@php
$hasPending = ($stats['pending_hotels'] - 0) + ($stats['pending_restaurants'] - 0) + ($stats['pending_events'] - 0) + ($stats['pending_guides'] - 0) + ($stats['pending_clubs'] - 0);
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-1"><h5 class="mb-0 fw-bold text-blue"><i class="bi bi-clock-history me-2 text-gold"></i>Pending Approvals</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6"><div class="p-3 bg-light rounded-3 text-center"><div class="display-6 fw-bold text-warning">{{ $stats['pending_guides'] - 0 }}</div><div class="text-muted small">Guides</div>@if(($stats['pending_guides'] - 0) > 0)<a href="{{ route('admin.guides', ['status' => 'Pending']) }}" class="btn btn-sm btn-outline-primary mt-1">Review</a>@endif</div></div>
                    <div class="col-md-2 col-6"><div class="p-3 bg-light rounded-3 text-center"><div class="display-6 fw-bold text-warning">{{ $stats['pending_hotels'] - 0 }}</div><div class="text-muted small">Hotels</div>@if(($stats['pending_hotels'] - 0) > 0)<a href="{{ route('admin.hotels', ['status' => 'inactive']) }}" class="btn btn-sm btn-outline-primary mt-1">Review</a>@endif</div></div>
                    <div class="col-md-2 col-6"><div class="p-3 bg-light rounded-3 text-center"><div class="display-6 fw-bold text-warning">{{ $stats['pending_restaurants'] - 0 }}</div><div class="text-muted small">Restaurants</div>@if(($stats['pending_restaurants'] - 0) > 0)<a href="{{ route('admin.restaurants', ['status' => 'inactive']) }}" class="btn btn-sm btn-outline-primary mt-1">Review</a>@endif</div></div>
                    <div class="col-md-2 col-6"><div class="p-3 bg-light rounded-3 text-center"><div class="display-6 fw-bold text-warning">{{ $stats['pending_events'] - 0 }}</div><div class="text-muted small">Events</div>@if(($stats['pending_events'] - 0) > 0)<a href="{{ route('admin.events', ['status' => 'inactive']) }}" class="btn btn-sm btn-outline-primary mt-1">Review</a>@endif</div></div>
                    <div class="col-md-2 col-6"><div class="p-3 bg-light rounded-3 text-center"><div class="display-6 fw-bold text-warning">{{ $stats['pending_clubs'] - 0 }}</div><div class="text-muted small">Clubs</div>@if(($stats['pending_clubs'] - 0) > 0)<a href="{{ route('admin.clubs-lounges.index') }}" class="btn btn-sm btn-outline-primary mt-1">Review</a>@endif</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-1"><h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2 text-gold"></i>Recent Bookings</h5></div>
            <div class="card-body p-0">
                @if($recent_bookings->count())
                <div class="table-responsive"><table class="table table-hover mb-0 admin-table"><thead><tr><th>Ref</th><th>User</th><th>Target</th><th>Amount</th><th>Status</th></tr></thead><tbody>
                @foreach($recent_bookings as $b)
                <tr><td><code>{{ $b->reference_code }}</code></td><td>{{ $b->user?->full_name }}</td><td>{{ $b->target_name }}</td><td class="text-gold fw-semibold">&#8358;{{ number_format($b->total_price) }}</td><td><span class="badge bg-secondary">{{ $b->booking_status }}</span></td></tr>
                @endforeach
                </tbody></table></div>
                @else
                <div class="text-center py-4 text-muted">No recent bookings.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-1"><h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-gold"></i>Recent Users</h5></div>
            <div class="card-body p-0">
                @if($recent_users->count())
                <ul class="list-group list-group-flush">
                @foreach($recent_users as $u)
                <li class="list-group-item d-flex justify-content-between align-items-center"><div><span class="fw-semibold small">{{ $u->full_name }}</span><br><small class="text-muted" style="font-size:0.7rem">{{ $u->email }}</small></div><span class="badge bg-secondary" style="font-size:0.65rem">{{ $u->role }}</span></li>
                @endforeach
                </ul>
                @else
                <div class="text-center py-4 text-muted">No recent users.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-1"><h5 class="mb-0 fw-bold"><i class="bi bi-award me-2 text-gold"></i>Recent Permits</h5></div>
            <div class="card-body p-0">
                @if(isset($recent_permits) && $recent_permits->count())
                <ul class="list-group list-group-flush">
                @foreach($recent_permits as $p)
                <li class="list-group-item d-flex justify-content-between align-items-center"><div><span class="fw-semibold small">{{ $p->business_name }}</span><br><small class="text-muted" style="font-size:0.7rem">{{ $p->business_type }}</small></div>@if($p->isExpired())<span class="badge bg-danger" style="font-size:0.65rem">Expired</span>@else<span class="badge bg-success" style="font-size:0.65rem">Valid</span>@endif</li>
                @endforeach
                </ul>
                @else
                <div class="text-center py-4 text-muted">No permits yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection