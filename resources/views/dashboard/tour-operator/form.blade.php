@extends('layouts.app')

@section('title', ($attraction->id ? 'Edit' : 'Add') . ' Attraction - Edo Tourism')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-blue mb-4">{{ $attraction->id ? 'Edit' : 'Add' }} Attraction</h3>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ $attraction->id ? route('dashboard.tour-operator.update', $attraction->id) : route('dashboard.tour-operator.store') }}">
                        @csrf
                        @if($attraction->id) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Attraction Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $attraction->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $attraction->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $attraction->description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Historical Background</label>
                                <textarea name="history" class="form-control" rows="3">{{ old('history', $attraction->history) }}</textarea>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $attraction->address) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $attraction->city) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Latitude</label>
                                <input type="number" step="any" name="latitude" id="lat_attr" class="form-control" value="{{ old('latitude', $attraction->latitude) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Longitude</label>
                                <input type="number" step="any" name="longitude" id="lng_attr" class="form-control" value="{{ old('longitude', $attraction->longitude) }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_attr', 'lng_attr')">
                                    <i class="bi bi-geo-alt"></i> Use Current Location
                                </button>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Ticket Price (N)</label>
                                <input type="number" step="any" name="ticket_price" class="form-control" value="{{ old('ticket_price', $attraction->ticket_price) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Opening Hours</label>
                                <input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', $attraction->opening_hours) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $attraction->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $attraction->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Website</label>
                                <input type="url" name="website" class="form-control" value="{{ old('website', $attraction->website) }}">
                            </div>

                            <div class="col-12 mt-3">
                                <h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-1"></i>Images</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Main Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Gallery Images (Max 10)</label>
                                <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                            </div>
                            @if(isset($attraction) && $attraction->images && $attraction->images->count() > 0)
                            <div class="col-12 mt-2">
                                <label class="form-label fw-semibold small">Current Gallery</label>
                                <div class="row g-2">
                                    @foreach($attraction->images as $img)
                                    <div class="col-3 col-md-2">
                                        <div class="card border-0 shadow-sm position-relative">
                                            <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top rounded" style="height: 80px; object-fit: cover;">
                                            <a href="{{ route('dashboard.tour-operator.image_delete', $img->id) }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="padding: 2px 6px; font-size: 0.7rem;" onclick="return confirm('Delete this image?')">x</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Note:</strong> Attractions are reviewed by admin before appearing publicly. Only admins can delete attractions.
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-gold">{{ $attraction->id ? 'Update Attraction' : 'Add Attraction' }}</button>
                            <a href="{{ route('dashboard.tour-operator.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection