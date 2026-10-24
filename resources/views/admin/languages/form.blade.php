@extends('layouts.admin')

@section('title', 'Language - Admin')
@section('page_title', 'Language')
@section('page_subtitle', 'Edo State indigenous language details')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">

    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $language->exists ? route('admin.languages.update', $language) : route('admin.languages.store') }}">
      @csrf
      @if($language->exists)
        @method('PUT')
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $language->name) }}" required maxlength="80">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Speakers</label>
              <input type="text" name="speakers" class="form-control" value="{{ old('speakers', $language->speakers) }}" maxlength="120">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Area</label>
              <input type="text" name="area" class="form-control" value="{{ old('area', $language->area) }}" maxlength="120">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">LGAs (comma-separated)</label>
            <input type="text" name="lga_list" class="form-control" value="{{ old('lga_list', $language->lga_list) }}" maxlength="255">
          </div>

          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $language->notes) }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $language->sort_order ?? 0) }}" min="0">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label d-block">Active</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $language->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Visible on site</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
          <a href="{{ route('admin.languages.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-gold">{{ $language->exists ? 'Update' : 'Create' }} Language</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection