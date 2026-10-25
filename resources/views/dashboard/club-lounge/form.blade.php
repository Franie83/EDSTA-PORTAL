@extends('layouts.app')
@section('title', ($club->id ? 'Edit' : 'Add') . ' Club / Lounge - Edo Tourism')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-blue mb-4">{{ $club->id ? 'Edit' : 'Add' }} Club / Lounge</h3>
                    @if($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" action="{{ $club->id ? route('dashboard.club-lounge.update', $club->id) : route('dashboard.club-lounge.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8"><label class="form-label fw-semibold small">Venue Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $club->name) }}" required></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Opening Hours</label><input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', $club->opening_hours) }}" placeholder="6PM - 2AM"></div>
                            <div class="col-12"><label class="form-label fw-semibold small">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $club->description) }}</textarea></div>
                            <div class="col-md-8"><label class="form-label fw-semibold small">Address</label><input type="text" name="address" class="form-control" value="{{ old('address', $club->address) }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $club->city) }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Latitude</label><input type="number" step="any" name="latitude" id="lat_club" class="form-control" value="{{ old('latitude', $club->latitude) }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Longitude</label><input type="number" step="any" name="longitude" id="lng_club" class="form-control" value="{{ old('longitude', $club->longitude) }}"></div>
                            <div class="col-md-4 d-flex align-items-end"><button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_club', 'lng_club')"><i class="bi bi-geo-alt"></i> Use Current Location</button></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $club->phone) }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $club->email) }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold small">Website</label><input type="url" name="website" class="form-control" value="{{ old('website', $club->website) }}"></div>
                            <div class="col-12 mt-3"><h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-1"></i>Images</h6></div>
                            <div class="col-md-6"><label class="form-label fw-semibold small">Main Image</label><input type="file" name="image" class="form-control" accept="image/*">@if($club->image_url)<small class="text-muted">Current: <a href="{{ \App\Helpers\Helpers::imageUrl($club->image_url) }}" target="_blank">View</a></small>@endif</div>
                            <div class="col-md-6"><label class="form-label fw-semibold small">Gallery Images (Max 10)</label><input type="file" name="gallery[]" class="form-control" multiple accept="image/*"><small class="text-muted">Carousel on the public page</small></div>
                            @if(isset($club) && $club->images && $club->images->count() > 0)
                            <div class="col-12 mt-2"><label class="form-label fw-semibold small">Current Gallery</label><div class="row g-2">@foreach($club->images as $img)<div class="col-3 col-md-2"><div class="card border-0 shadow-sm position-relative"><img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top rounded" style="height:80px;object-fit:cover;"><a href="{{ route('dashboard.club-lounge.image_delete', $img->id) }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="padding:2px 6px;font-size:0.7rem;" onclick="return confirm('Delete this image?')">×</a></div></div>@endforeach</div></div>
                            @endif
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-gold">{{ $club->id ? 'Update' : 'Create' }}</button>
                            <a href="{{ route('dashboard.club-lounge.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection