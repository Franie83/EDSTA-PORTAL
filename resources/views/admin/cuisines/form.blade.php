@extends('layouts.admin')

@section('title', 'Cuisine - Admin')
@section('page_title', 'Cuisine')
@section('page_subtitle', 'Traditional Edo dish details')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-9">

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $cuisine->exists ? route('admin.cuisines.update', $cuisine) : route('admin.cuisines.store') }}">
      @csrf
      @if($cuisine->exists)
        @method('PUT')
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $cuisine->name) }}" required maxlength="100">
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $cuisine->description) }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Ingredients</label>
            <textarea name="ingredients" class="form-control" rows="3">{{ old('ingredients', $cuisine->ingredients) }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Commonly Eaten With</label>
              <input type="text" name="commonly_eaten_with" class="form-control" value="{{ old('commonly_eaten_with', $cuisine->commonly_eaten_with) }}" maxlength="120">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Area</label>
              <input type="text" name="area" class="form-control" value="{{ old('area', $cuisine->area) }}" maxlength="120">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $cuisine->sort_order ?? 0) }}" min="0">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label d-block">Active</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $cuisine->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Visible on site</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <a href="{{ route('admin.cuisines.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-gold">{{ $cuisine->exists ? 'Update' : 'Create' }} Cuisine</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection