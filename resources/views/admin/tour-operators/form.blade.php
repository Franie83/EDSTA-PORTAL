@extends('layouts.admin')

@section('title', 'Tour Operator - Admin')
@section('page_title', 'Tour Operator')
@section('page_subtitle', 'Tour and travel operator details')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-10">

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $operator->exists ? route('admin.tour-operators.update', $operator) : route('admin.tour-operators.store') }}">
      @csrf
      @if($operator->exists)
        @method('PUT')
      @endif

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Basic Information</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $operator->name) }}" required maxlength="120">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $operator->address) }}" maxlength="255">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone', $operator->phone) }}" maxlength="40">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $operator->email) }}" maxlength="120">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Website</label>
              <input type="text" name="website" class="form-control" value="{{ old('website', $operator->website) }}" maxlength="255">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Nearby Landmark</label>
              <input type="text" name="nearby_landmark" class="form-control" value="{{ old('nearby_landmark', $operator->nearby_landmark) }}" maxlength="255">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Ownership</label>
              <select name="ownership" class="form-select">
                <option value="">—</option>
                <option value="private" {{ old('ownership', $operator->ownership) === 'private' ? 'selected' : '' }}>Private</option>
                <option value="government" {{ old('ownership', $operator->ownership) === 'government' ? 'selected' : '' }}>Government</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Staff Strength</label>
              <input type="text" name="staff_strength" class="form-control" value="{{ old('staff_strength', $operator->staff_strength) }}" maxlength="40">
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Service Details</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Scope of Service</label>
              <input type="text" name="scope_of_service" class="form-control" value="{{ old('scope_of_service', $operator->scope_of_service) }}" maxlength="80">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Service Availability</label>
              <input type="text" name="service_availability" class="form-control" value="{{ old('service_availability', $operator->service_availability) }}" maxlength="120">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Age Limit</label>
            <input type="text" name="age_limit" class="form-control" value="{{ old('age_limit', $operator->age_limit) }}" maxlength="80">
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Cost Ranges</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Edo South</label>
              <input type="text" name="cost_range_edo_south" class="form-control" value="{{ old('cost_range_edo_south', $operator->cost_range_edo_south) }}" maxlength="80">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Edo North</label>
              <input type="text" name="cost_range_edo_north" class="form-control" value="{{ old('cost_range_edo_north', $operator->cost_range_edo_north) }}" maxlength="80">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Edo Central</label>
              <input type="text" name="cost_range_edo_central" class="form-control" value="{{ old('cost_range_edo_central', $operator->cost_range_edo_central) }}" maxlength="80">
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Visitor Data & Engagement</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Yearly Interstate Visitors</label>
              <input type="number" name="yearly_interstate_visitors" class="form-control" value="{{ old('yearly_interstate_visitors', $operator->yearly_interstate_visitors) }}" min="0">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Yearly International Visitors</label>
              <input type="number" name="yearly_international_visitors" class="form-control" value="{{ old('yearly_international_visitors', $operator->yearly_international_visitors) }}" min="0">
            </div>
          </div>
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="aware_of_edsta" value="1" id="aware" {{ old('aware_of_edsta', $operator->aware_of_edsta) ? 'checked' : '' }}>
            <label class="form-check-label" for="aware">Aware of EDSTA</label>
          </div>
          <div class="mb-3">
            <label class="form-label">Suggestions</label>
            <textarea name="suggestions" class="form-control" rows="3">{{ old('suggestions', $operator->suggestions) }}</textarea>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Media & Visibility</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $operator->image_url) }}" maxlength="500">
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured', $operator->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label" for="isFeatured">Featured</label>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $operator->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Active</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <a href="{{ route('admin.tour-operators.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-gold">{{ $operator->exists ? 'Update' : 'Create' }} Tour Operator</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection