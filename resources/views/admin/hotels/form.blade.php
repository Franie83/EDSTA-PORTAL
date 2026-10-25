@extends('layouts.admin')
@section('title', ($hotel->id ? 'Edit Hotel' : 'New Hotel') . ' – Admin')
@section('page_title', $hotel->id ? 'Edit Hotel' : 'Add Hotel')
@section('content')
<div class="row justify-content-center"><div class="col-md-9">
  <div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form method="POST" action="{{ $hotel->id ? route('admin.hotel_update', $hotel->id) : route('admin.hotel_store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label fw-semibold small">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Stars</label><select name="stars" class="form-select">@for($s=1;$s<=5;$s++)<option value="{{ $s }}" {{ old('stars', $hotel->stars)==$s?'selected':'' }}>{{ $s }} Star{{ $s>1?'s':'' }}</option>@endfor</select></div>

        {{-- ✅ Assigned User dropdown --}}
        <div class="col-12">
          <label class="form-label fw-semibold small">Assigned User *</label>
          <select name="user_id" class="form-select" required>
            <option value="">Select user</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id', $hotel->user_id) == $user->id ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12"><label class="form-label fw-semibold small">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $hotel->description) }}</textarea></div>
        <div class="col-md-8"><label class="form-label fw-semibold small">Address</label><input type="text" name="address" class="form-control" value="{{ old('address', $hotel->address) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $hotel->city) }}"></div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Latitude</label>
          <input type="number" step="any" name="latitude" id="lat_hotel" class="form-control" value="{{ old('latitude', $hotel->latitude ?? '') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold small">Longitude</label>
          <input type="number" step="any" name="longitude" id="lng_hotel" class="form-control" value="{{ old('longitude', $hotel->longitude ?? '') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_hotel', 'lng_hotel')"><i class="bi bi-geo-alt"></i> Use Current Location</button>
        </div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Price/Night (₦) *</label><input type="number" name="price_per_night" class="form-control" step="500" value="{{ old('price_per_night', $hotel->price_per_night) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $hotel->phone) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $hotel->email) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Check-in Time</label><input type="text" name="check_in_time" class="form-control" value="{{ old('check_in_time', $hotel->check_in_time) }}" placeholder="2:00 PM"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Check-out Time</label><input type="text" name="check_out_time" class="form-control" value="{{ old('check_out_time', $hotel->check_out_time) }}" placeholder="12:00 PM"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Website</label><input type="url" name="website" class="form-control" value="{{ old('website', $hotel->website) }}"></div>
        <div class="col-12"><label class="form-label fw-semibold small">Amenities</label><input type="text" name="amenities" class="form-control" value="{{ old('amenities', $hotel->amenities) }}" placeholder="Pool, Gym, WiFi, Restaurant"></div>
        {{-- Images --}}
        <div class="col-12 mt-3">
          <h6 class="fw-bold text-muted mb-3"><i class="bi bi-images me-1"></i>Images</h6>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Primary Image (upload)</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Shown on cards and as fallback when no gallery exists</small>
          @if($hotel->image_url)
            <div class="mt-1">
              <small class="text-muted">Current: <a href="{{ \App\Helpers\Helpers::imageUrl($hotel->image_url) }}" target="_blank">View Image</a></small>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Primary Image (URL)</label>
          <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('image_url', $hotel->image_url ?? '') }}">
          <small class="text-muted">Alternative to upload - paste a URL</small>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold small">Gallery Images (Max 10)</label>
          <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
          <small class="text-muted">Carousel on the public page</small>
        </div>

        @if(isset($hotel) && $hotel->images && $hotel->images->count() > 0)
        <div class="col-12 mt-2">
          <label class="form-label fw-semibold small">Current Gallery</label>
          <div class="row g-2">
            @foreach($hotel->images as $img)
              <div class="col-3 col-md-2">
                <div class="card border-0 shadow-sm position-relative">
                  <img src="{{ asset('storage/' . $img->image_path) }}" class="card-img-top rounded" style="height: 80px; object-fit: cover;">
                  <a href="{{ route('admin.hotel_image_delete', $img->id) }}"
                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                     style="padding: 2px 6px; font-size: 0.7rem;"
                     onclick="return confirm('Delete this image?')">x</a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- ═══ EDSTA/ICTA Extended Fields ═══ --}}
        <div class="col-12"><hr class="my-4"><h5 class="fw-bold text-muted mb-3"><i class="bi bi-building me-1"></i>Extended Hotel Information (ICTA)</h5></div>

        {{-- Section 1: Contact --}}
        <div class="col-12"><h6 class="fw-bold text-primary mb-2">Contact & Location</h6></div>
        <div class="col-md-6"><label class="form-label fw-semibold small">Nearby Landmark</label><input type="text" name="nearby_landmark" class="form-control" value="{{ old('nearby_landmark', $hotel->nearby_landmark) }}" maxlength="255"></div>
        <div class="col-md-6"><label class="form-label fw-semibold small">Staff Strength</label><input type="text" name="staff_strength" class="form-control" value="{{ old('staff_strength', $hotel->staff_strength) }}" placeholder="e.g. 10-20" maxlength="40"></div>

        {{-- Section 2: Classification --}}
        <div class="col-12"><h6 class="fw-bold text-primary mb-2 mt-3">Classification</h6></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Star Rating</label><select name="star_rating" class="form-select"><option value="">—</option><option value="1" {{ old('star_rating', $hotel->star_rating) == 1 ? 'selected' : '' }}>1★</option><option value="2" {{ old('star_rating', $hotel->star_rating) == 2 ? 'selected' : '' }}>2★</option><option value="3" {{ old('star_rating', $hotel->star_rating) == 3 ? 'selected' : '' }}>3★</option><option value="4" {{ old('star_rating', $hotel->star_rating) == 4 ? 'selected' : '' }}>4★</option><option value="5" {{ old('star_rating', $hotel->star_rating) == 5 ? 'selected' : '' }}>5★</option></select></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Ownership</label><select name="ownership" class="form-select"><option value="">—</option><option value="private" {{ old('ownership', $hotel->ownership) === 'private' ? 'selected' : '' }}>Private</option><option value="government" {{ old('ownership', $hotel->ownership) === 'government' ? 'selected' : '' }}>Government</option></select></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Total Rooms</label><input type="number" name="rooms_total" class="form-control" value="{{ old('rooms_total', $hotel->rooms_total) }}" min="0"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Functional Rooms</label><input type="number" name="rooms_functional" class="form-control" value="{{ old('rooms_functional', $hotel->rooms_functional) }}" min="0"></div>
        <div class="col-md-4"><label class="form-label fw-semibold small">Suites</label><input type="number" name="rooms_suites" class="form-control" value="{{ old('rooms_suites', $hotel->rooms_suites) }}" min="0"></div>

        {{-- Section 3: Facilities --}}
        <div class="col-12"><h6 class="fw-bold text-primary mb-2 mt-3">Facilities & Safety</h6></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_swimming_pool" value="1" id="hsp" {{ old('has_swimming_pool', $hotel->has_swimming_pool) ? 'checked' : '' }}><label class="form-check-label" for="hsp">Swimming Pool</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_gym" value="1" id="hg" {{ old('has_gym', $hotel->has_gym) ? 'checked' : '' }}><label class="form-check-label" for="hg">Gym</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_spa" value="1" id="hspa" {{ old('has_spa', $hotel->has_spa) ? 'checked' : '' }}><label class="form-check-label" for="hspa">Spa</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_wifi" value="1" id="hwifi" {{ old('has_wifi', $hotel->has_wifi) ? 'checked' : '' }}><label class="form-check-label" for="hwifi">WiFi</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_cctv" value="1" id="hcctv" {{ old('has_cctv', $hotel->has_cctv) ? 'checked' : '' }}><label class="form-check-label" for="hcctv">CCTV</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_event_hall" value="1" id="heh" {{ old('has_event_hall', $hotel->has_event_hall) ? 'checked' : '' }}><label class="form-check-label" for="heh">Event Hall</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_live_band" value="1" id="hlb" {{ old('has_live_band', $hotel->has_live_band) ? 'checked' : '' }}><label class="form-check-label" for="hlb">Live Band</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_parking" value="1" id="hp" {{ old('has_parking', $hotel->has_parking) ? 'checked' : '' }}><label class="form-check-label" for="hp">Parking</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_concierge" value="1" id="hcon" {{ old('has_concierge', $hotel->has_concierge) ? 'checked' : '' }}><label class="form-check-label" for="hcon">Concierge</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_laundry" value="1" id="hl" {{ old('has_laundry', $hotel->has_laundry) ? 'checked' : '' }}><label class="form-check-label" for="hl">Laundry</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_first_aid" value="1" id="hfa" {{ old('has_first_aid', $hotel->has_first_aid) ? 'checked' : '' }}><label class="form-check-label" for="hfa">First Aid</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_emergency_exit" value="1" id="hee" {{ old('has_emergency_exit', $hotel->has_emergency_exit) ? 'checked' : '' }}><label class="form-check-label" for="hee">Emergency Exit</label></div></div>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="has_fire_extinguisher" value="1" id="hfe" {{ old('has_fire_extinguisher', $hotel->has_fire_extinguisher) ? 'checked' : '' }}><label class="form-check-label" for="hfe">Fire Extinguisher</label></div></div>

        {{-- Section 4: Pricing & Services --}}
        <div class="col-12"><h6 class="fw-bold text-primary mb-2 mt-3">Pricing & Services</h6></div>
        <div class="col-md-6"><label class="form-label fw-semibold small">Price Range for Feeding</label><input type="text" name="price_range_feeding" class="form-control" value="{{ old('price_range_feeding', $hotel->price_range_feeding) }}" placeholder="e.g. ₦5,000 - ₦15,000" maxlength="80"></div>
        <div class="col-md-6"><label class="form-label fw-semibold small">Booking Terms</label><input type="text" name="booking_terms" class="form-control" value="{{ old('booking_terms', $hotel->booking_terms) }}" placeholder="e.g. Pre-booking and on-the-spot" maxlength="120"></div>
        <div class="col-12"><label class="form-label fw-semibold small">Recreation Offerings</label><textarea name="recreation_offerings" class="form-control" rows="2" placeholder="e.g. Picnics, Games, Parties, Catering">{{ old('recreation_offerings', $hotel->recreation_offerings) }}</textarea></div>

        <div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured" {{ old('is_featured', $hotel->is_featured)?'checked':'' }}><label class="form-check-label">Featured</label></div></div>
<div class="col-md-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_active" {{ old('is_active', $hotel->is_active) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>      </div>
      <div class="d-flex gap-2 mt-4">
        <button class="btn btn-gold">{{ $hotel->id ? 'Update Hotel' : 'Create Hotel' }}</button>
        <a href="{{ route('admin.hotels') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div></div>
</div></div>
@endsection