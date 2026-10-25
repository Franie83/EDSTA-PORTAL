@extends('layouts.admin')
@section('title', ($restaurant->id ? 'Edit Restaurant' : 'New Restaurant') . ' – Admin')
@section('page_title', $restaurant->id ? 'Edit Restaurant' : 'Add Restaurant')
@section('content')
<div class="row justify-content-center"><div class="col-md-9">
  <div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form method="POST" action="{{ $restaurant->id ? route('admin.restaurant_update', $restaurant->id) : route('admin.restaurant_store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label fw-semibold small">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $restaurant->name) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Cuisine Type</label><input type="text" name="cuisine_type" class="form-control" value="{{ old('cuisine_type', $restaurant->cuisine_type) }}" placeholder="Nigerian, Continental…"></div>

        {{-- ✅ Assigned User dropdown --}}
        <div class="col-12">
          <label class="form-label fw-semibold small">Assigned User *</label>
          <select name="user_id" class="form-select" required>
            <option value="">Select user</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id', $restaurant->user_id) == $user->id ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12"><label class="form-label fw-semibold small">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $restaurant->description) }}</textarea></div>
        <div class="col-md-8"><label class="form-label fw-semibold small">Address</label><input type="text" name="address" class="form-control" value="{{ old('address', $restaurant->address) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $restaurant->city) }}"></div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Latitude</label>
          <input type="number" step="any" name="latitude" id="lat_restaurant" class="form-control" value="{{ old('latitude', $restaurant->latitude ?? '') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Longitude</label>
          <input type="number" step="any" name="longitude" id="lng_restaurant" class="form-control" value="{{ old('longitude', $restaurant->longitude ?? '') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_restaurant', 'lng_restaurant')"><i class="bi bi-geo-alt"></i> Use Current Location</button>
        </div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $restaurant->phone) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Opening Hours</label><input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', $restaurant->opening_hours) }}" placeholder="11am–10pm"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Avg Price (₦)</label><input type="number" name="avg_price" class="form-control" value="{{ old('avg_price', $restaurant->avg_price) }}"></div>
                {{-- Images --}}
        <div class="col-12 mt-3">
          <h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-1"></i>Images</h6>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Primary Image (upload)</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Shown on cards and as fallback when no gallery exists</small>
          @if($restaurant->image_url)
            <div class="mt-1">
              <small class="text-muted">Current: <a href="{{ \App\Helpers\Helpers::imageUrl($restaurant->image_url) }}" target="_blank">View Image</a></small>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Primary Image (URL)</label>
          <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('image_url', $restaurant->image_url ?? '') }}">
          <small class="text-muted">Alternative to upload</small>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Gallery Images (Max 10)</label>
          <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
          <small class="text-muted">Carousel on the public page</small>
        </div>

        @if(isset($restaurant) && $restaurant->images && $restaurant->images->count() > 0)
        <div class="col-12 mt-2">
          <label class="form-label fw-semibold small">Current Gallery</label>
          <div class="row g-2">
            @foreach($restaurant->images as $img)
              <div class="col-3 col-md-2">
                <div class="card border-0 shadow-sm position-relative">
                  <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top rounded" style="height: 80px; object-fit: cover;">
                  <a href="{{ route('admin.restaurant_image_delete', $img->id) }}"
                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                     style="padding: 2px 6px; font-size: 0.7rem;"
                     onclick="return confirm('Delete this image?')">x</a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif
        <div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured" {{ old('is_featured', $restaurant->is_featured)?'checked':'' }}><label class="form-check-label">Featured</label></div></div>
<div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_active" {{ old('is_active', $restaurant->is_active) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>      </div>
      <div class="d-flex gap-2 mt-4"><button class="btn btn-gold">{{ $restaurant->id ? 'Update' : 'Create' }} Restaurant</button><a href="{{ route('admin.restaurants') }}" class="btn btn-outline-secondary">Cancel</a></div>
    </form>
  </div></div>
</div></div>
@endsection