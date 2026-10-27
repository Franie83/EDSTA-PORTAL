@extends('layouts.admin')
@section('title', ($attraction->id ? 'Edit Attraction' : 'New Attraction') . ' – Admin')
@section('page_title', $attraction->id ? 'Edit Attraction' : 'Add Attraction')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-9">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <form method="POST" action="{{ $attraction->id ? route('admin.attraction_update', $attraction->id) : route('admin.attraction_store') }}" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-8"><label class="form-label fw-semibold small">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $attraction->name) }}" required></div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Category</label>
              <select name="category_id" class="form-select">
                <option value="">Select category</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $attraction->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach
              </select>
            </div>

            {{-- Assigned User --}}
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Assigned User *</label>
              <select name="user_id" class="form-select" required>
                <option value="">Select user</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ old('user_id', $attraction->user_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-12"><label class="form-label fw-semibold small">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $attraction->description) }}</textarea></div>
            <div class="col-12"><label class="form-label fw-semibold small">Historical Background</label><textarea name="history" class="form-control" rows="3">{{ old('history', $attraction->history) }}</textarea></div>
            <div class="col-md-8"><label class="form-label fw-semibold small">Address</label><input type="text" name="address" class="form-control" value="{{ old('address', $attraction->address) }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $attraction->city) }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Ticket Price (₦)</label><input type="number" name="ticket_price" class="form-control" step="0.01" value="{{ old('ticket_price', $attraction->ticket_price) }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Opening Hours</label><input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', $attraction->opening_hours) }}" placeholder="9am–5pm daily"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $attraction->phone) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold small">Website</label><input type="url" name="website" class="form-control" value="{{ old('website', $attraction->website) }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Latitude</label><input type="number" step="any" name="latitude" id="lat_attraction" class="form-control" value="{{ old('latitude', $attraction->latitude) }}"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Longitude</label><input type="number" step="any" name="longitude" id="lng_attraction" class="form-control" value="{{ old('longitude', $attraction->longitude) }}"></div>
            <div class="col-md-4 d-flex align-items-end"><button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_attraction', 'lng_attraction')"><i class="bi bi-geo-alt"></i> Use Current Location</button></div>
            
            {{-- ─── Images ─── --}}
<div class="col-12 mt-4">
  <h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-1"></i>Images</h6>
</div>

<div class="col-md-4">
  <label class="form-label fw-semibold small">Primary Image (upload)</label>
  <input type="file" name="image" class="form-control" accept="image/*">
  <small class="text-muted">Shown on cards, lists, and as fallback when no gallery exists</small>
  @if($attraction->image_url)
    <div class="mt-1">
      <small class="text-muted">Current: <a href="{{ \App\Helpers\Helpers::imageUrl($attraction->image_url) }}" target="_blank">View Image</a></small>
    </div>
  @endif
</div>

<div class="col-md-4">
  <label class="form-label fw-semibold small">Primary Image (URL)</label>
  <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('image_url', $attraction->image_url ?? '') }}">
  <small class="text-muted">Alternative to upload — paste a URL (e.g. from Unsplash)</small>
</div>

<div class="col-md-4">
  <label class="form-label fw-semibold small">Gallery Images (Max 10)</label>
  <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
  <small class="text-muted">Carousel on the public page</small>
</div>

@if(isset($attraction) && $attraction->images && $attraction->images->count() > 0)
<div class="col-12 mt-2">
  <label class="form-label fw-semibold small">Current Gallery</label>
  <div class="row g-2">
    @foreach($attraction->images as $img)
      <div class="col-3 col-md-2">
        <div class="card border-0 shadow-sm position-relative">
          <img src="{{ \App\Helpers\Helpers::imageUrl($img->image_path) }}" class="card-img-top rounded" style="height: 80px; object-fit: cover;">
          <a href="{{ route('admin.attraction_image_delete', $img->id) }}"
             class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
             style="padding: 2px 6px; font-size: 0.7rem;"
             onclick="return confirm('Delete this image?')">×</a>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif{{-- EDSTA Rich Fields --}}
            <div class="col-12"><hr class="my-3"><h6 class="fw-bold text-muted mb-3"><i class="bi bi-file-text me-1"></i>Rich Content (EDSTA)</h6></div>

            <div class="col-12">
              <label class="form-label fw-semibold small">Historical Background</label>
              <textarea name="historical_background" class="form-control" rows="3">{{ old('historical_background', $attraction->historical_background ?? '') }}</textarea>
              <small class="text-muted">Long-form history of the site (from EDSTA database)</small>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold small">Unique Selling Points</label>
              <textarea name="unique_selling_points" class="form-control" rows="3">{{ old('unique_selling_points', $attraction->unique_selling_points ?? '') }}</textarea>
              <small class="text-muted">What makes this attraction special</small>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold small">Ownership</label>
              <input type="text" name="ownership" class="form-control" placeholder="e.g. Public (State), Private, Traditional" value="{{ old('ownership', $attraction->ownership ?? '') }}" maxlength="80">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold small">Proximity from City</label>
              <input type="text" name="proximity_from_city" class="form-control" placeholder="e.g. 35km (22miles)" value="{{ old('proximity_from_city', $attraction->proximity_from_city ?? '') }}" maxlength="120">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold small">Nearby Landmarks</label>
              <input type="text" name="landmarks" class="form-control" placeholder="e.g. Kings Square, National Museum" value="{{ old('landmarks', $attraction->landmarks ?? '') }}" maxlength="255">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold small">Investment Opportunities</label>
              <textarea name="investment_opportunities" class="form-control" rows="2">{{ old('investment_opportunities', $attraction->investment_opportunities ?? '') }}</textarea>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold small">Visiting Tips</label>
              <textarea name="visiting_tips" class="form-control" rows="2">{{ old('visiting_tips', $attraction->visiting_tips ?? '') }}</textarea>
            </div>

            
            <div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured', $attraction->is_featured)?'checked':'' }}><label class="form-check-label" for="is_featured">Featured</label></div></div>
            <div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $attraction->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active</label></div></div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button class="btn btn-gold">{{ $attraction->id ? 'Update Attraction' : 'Create Attraction' }}</button>
            <a href="{{ route('admin.attractions') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection