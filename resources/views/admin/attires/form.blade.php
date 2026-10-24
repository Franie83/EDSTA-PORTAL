@extends('layouts.admin')

@section('title', 'Traditional Attire - Admin')
@section('page_title', 'Traditional Attire')
@section('page_subtitle', 'Edo ethnic attire details')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-9">

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $attire->exists ? route('admin.attires.update', $attire) : route('admin.attires.store') }}">
      @csrf
      @if($attire->exists)
        @method('PUT')
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label fw-bold">Ethnic Group *</label>
              <input type="text" name="ethnic_group" class="form-control" value="{{ old('ethnic_group', $attire->ethnic_group) }}" required maxlength="80">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold">Gender *</label>
              <select name="gender" class="form-select" required>
                <option value="male" {{ old('gender', $attire->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $attire->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="unisex" {{ old('gender', $attire->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Attire Items (comma-separated)</label>
            <textarea name="attire_items" class="form-control" rows="2">{{ old('attire_items', $attire->attire_items) }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $attire->description) }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Occasions</label>
            <input type="text" name="occasions" class="form-control" value="{{ old('occasions', $attire->occasions) }}" maxlength="255">
          </div>

          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $attire->notes) }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $attire->sort_order ?? 0) }}" min="0">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label d-block">Active</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $attire->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Visible on site</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <a href="{{ route('admin.attires.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-gold">{{ $attire->exists ? 'Update' : 'Create' }} Attire</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection