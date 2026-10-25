@extends('layouts.admin')

@section('title', 'Club / Lounge - Admin')
@section('page_title', 'Club / Lounge')
@section('page_subtitle', 'Nightlife venue details')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-10">

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $club->exists ? route('admin.clubs-lounges.update', $club) : route('admin.clubs-lounges.store') }}">
      @csrf
      @if($club->exists)
        @method('PUT')
      @endif

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Basic Information</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $club->name) }}" required maxlength="120">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $club->address) }}" maxlength="255">
        </div>
        <div class="col-md-4">
          <label class="form-label">Latitude</label>
          <input type="number" step="any" name="latitude" id="lat_club" class="form-control" value="{{ old('latitude', $club->latitude ?? '') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Longitude</label>
          <input type="number" step="any" name="longitude" id="lng_club" class="form-control" value="{{ old('longitude', $club->longitude ?? '') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-outline-secondary w-100" onclick="autoFillGPS('lat_club', 'lng_club')"><i class="bi bi-geo-alt"></i> Use Current Location</button>
        </div>
        <div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone', $club->phone) }}" maxlength="40">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $club->email) }}" maxlength="120">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Website</label>
              <input type="text" name="website" class="form-control" value="{{ old('website', $club->website) }}" maxlength="255">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Nearby Landmark</label>
              <input type="text" name="nearby_landmark" class="form-control" value="{{ old('nearby_landmark', $club->nearby_landmark) }}" maxlength="255">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Ownership</label>
              <select name="ownership" class="form-select">
                <option value="">—</option>
                <option value="private" {{ old('ownership', $club->ownership) === 'private' ? 'selected' : '' }}>Private</option>
                <option value="government" {{ old('ownership', $club->ownership) === 'government' ? 'selected' : '' }}>Government</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Staff Strength</label>
              <input type="text" name="staff_strength" class="form-control" value="{{ old('staff_strength', $club->staff_strength) }}" maxlength="40">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Best Time to Visit</label>
              <input type="text" name="best_time_to_visit" class="form-control" value="{{ old('best_time_to_visit', $club->best_time_to_visit) }}" maxlength="120">
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Facilities</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_live_band" value="1" id="hlb" {{ old('has_live_band', $club->has_live_band) ? 'checked' : '' }}><label class="form-check-label" for="hlb">Live Band</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_casino" value="1" id="hcas" {{ old('has_casino', $club->has_casino) ? 'checked' : '' }}><label class="form-check-label" for="hcas">Casino</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_grills" value="1" id="hgr" {{ old('has_grills', $club->has_grills) ? 'checked' : '' }}><label class="form-check-label" for="hgr">Grills & Chill</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_dance_floor" value="1" id="hdf" {{ old('has_dance_floor', $club->has_dance_floor) ? 'checked' : '' }}><label class="form-check-label" for="hdf">Dance Floor</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_indoor_bar" value="1" id="hib" {{ old('has_indoor_bar', $club->has_indoor_bar) ? 'checked' : '' }}><label class="form-check-label" for="hib">Indoor Bar</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_outdoor_bar" value="1" id="hob" {{ old('has_outdoor_bar', $club->has_outdoor_bar) ? 'checked' : '' }}><label class="form-check-label" for="hob">Outdoor Bar</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_dj_booth" value="1" id="hdj" {{ old('has_dj_booth', $club->has_dj_booth) ? 'checked' : '' }}><label class="form-check-label" for="hdj">DJ Booth</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_vip_section" value="1" id="hvip" {{ old('has_vip_section', $club->has_vip_section) ? 'checked' : '' }}><label class="form-check-label" for="hvip">VIP Section</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_light_sound" value="1" id="hls" {{ old('has_light_sound', $club->has_light_sound) ? 'checked' : '' }}><label class="form-check-label" for="hls">Light & Sound</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_cctv" value="1" id="hcctv" {{ old('has_cctv', $club->has_cctv) ? 'checked' : '' }}><label class="form-check-label" for="hcctv">CCTV</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_swimming_pool" value="1" id="hsp" {{ old('has_swimming_pool', $club->has_swimming_pool) ? 'checked' : '' }}><label class="form-check-label" for="hsp">Swimming Pool</label></div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check"><input class="form-check-input" type="checkbox" name="has_lodging" value="1" id="hl" {{ old('has_lodging', $club->has_lodging) ? 'checked' : '' }}><label class="form-check-label" for="hl">Lodging</label></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Pricing</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Regular Gate Fee</label>
              <input type="text" name="gate_fee_regular" class="form-control" value="{{ old('gate_fee_regular', $club->gate_fee_regular) }}" maxlength="80">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">VIP Gate Fee</label>
              <input type="text" name="gate_fee_vip" class="form-control" value="{{ old('gate_fee_vip', $club->gate_fee_vip) }}" maxlength="80">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Accommodation Fees</label>
              <input type="text" name="accommodation_fees" class="form-control" value="{{ old('accommodation_fees', $club->accommodation_fees) }}" maxlength="80">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Booking Terms</label>
            <input type="text" name="booking_terms" class="form-control" value="{{ old('booking_terms', $club->booking_terms) }}" maxlength="120">
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Media & Visibility</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $club->image_url) }}" maxlength="500">
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured', $club->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label" for="isFeatured">Featured</label>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $club->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Active</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <a href="{{ route('admin.clubs-lounges.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-gold">{{ $club->exists ? 'Update' : 'Create' }} Venue</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection