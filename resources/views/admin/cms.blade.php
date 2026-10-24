@extends('layouts.admin')

@section('title', 'CMS Settings - Admin')
@section('page_title', 'Site Content & Appearance')
@section('page_subtitle', 'Manage global text, logos, and images')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-10">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.cms_update') }}" enctype="multipart/form-data">
      @csrf
      
      <!-- TABS NAVIGATION -->
      <ul class="nav nav-tabs mb-4 border-bottom" id="cmsTabs" role="tablist">
        @php $first = true; @endphp
        @foreach($cms_groups as $group => $settings)
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $first ? 'active' : '' }} fw-bold text-capitalize" id="tab-{{ $group }}" data-bs-toggle="tab" data-bs-target="#content-{{ $group }}" type="button" role="tab">
              {{ $group }}
            </button>
          </li>
          @php $first = false; @endphp
        @endforeach
      </ul>

      <!-- TABS CONTENT -->
      <div class="tab-content" id="cmsTabsContent">
        @php $first = true; @endphp
        @foreach($cms_groups as $group => $settings)
          <div class="tab-pane fade {{ $first ? 'show active' : '' }}" id="content-{{ $group }}" role="tabpanel">
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                
                @foreach($settings as $setting)
                <div class="mb-4">
                  <label class="form-label fw-semibold small text-muted text-uppercase">
                    {{ $setting['label'] ?? ucwords(str_replace('_',' ', $setting['key'])) }}
                  </label>
                  
                  @if(($setting['type'] ?? 'text') === 'textarea')
                    <textarea name="settings[{{ $setting['key'] }}]" class="form-control" rows="4">{{ old("settings.{$setting['key']}", $setting['value']) }}</textarea>
                  
                  @elseif(($setting['type'] ?? 'text') === 'image')
                    <div class="d-flex align-items-center gap-3">
                      @if(!empty($setting['value']))
                        <img src="{{ asset('storage/' . $setting['value']) }}" alt="Current Image" class="img-thumbnail rounded" style="height: 80px; object-fit: contain;">
                      @endif
                      <div class="flex-grow-1">
                        <input type="file" name="images[{{ $setting['key'] }}]" class="form-control" accept="image/*">
                        <small class="text-muted">Upload a new image to replace the current one.</small>
                      </div>
                    </div>
                  
                  @else
                    <input type="{{ $setting['type'] ?? 'text' }}" name="settings[{{ $setting['key'] }}]" class="form-control" value="{{ old("settings.{$setting['key']}", $setting['value']) }}">
                  @endif
                </div>
                @endforeach

              </div>
            </div>
          </div>
          @php $first = false; @endphp
        @endforeach
      </div>

      <div class="d-flex gap-2 mt-4">
        <button class="btn btn-gold px-4 py-2 fw-bold">Save All Settings</button>
      </div>
    </form>

    <!-- NOTIFY ALL USERS -->
    <div class="card border-0 shadow-sm mt-5">
      <div class="card-body p-4">
        <h5 class="fw-bold text-blue mb-3"><i class="bi bi-bell me-2"></i>Send Notification to All Users</h5>
        <form method="POST" action="{{ route('admin.notify_all') }}">
          @csrf
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold small text-muted text-uppercase">Audience</label>
              <select name="audience" class="form-select">
                <option value="all">Everyone (All Roles)</option>
                <option value="Tourist">Tourists Only</option>
                <option value="Guide">Guides Only</option>
                <option value="Hotel Manager">Hotel Managers Only</option>
                <option value="Restaurant Manager">Restaurant Managers Only</option>
                <option value="Admin">Admins Only</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold small text-muted text-uppercase">Type</label>
              <select name="type" class="form-select">
                <option value="info">Info</option>
                <option value="success">Success</option>
                <option value="warning">Warning</option>
                <option value="danger">Danger</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-semibold small text-muted text-uppercase">Title *</label>
              <input type="text" name="title" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small text-muted text-uppercase">Message *</label>
            <textarea name="message" class="form-control" rows="3" required></textarea>
          </div>
          <button class="btn btn-blue"><i class="bi bi-send me-2"></i>Send Notification</button>
        </form>
      </div>
    </div>

    <!-- DANGER ZONE -->
    <div class="card border-0 shadow-sm border-start border-danger border-4 mt-5 mb-5">
      <div class="card-body p-4">
        <h5 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
        <p class="text-muted small mb-4">These actions are irreversible. Please make sure you have a backup before proceeding.</p>
        <div class="d-flex gap-3">
          <button type="button" onclick="if(confirm('Are you SURE you want to flush the entire database? This cannot be undone!')) { document.getElementById('flushForm').submit(); }" class="btn btn-outline-danger">Flush Database</button>
          
          <form id="flushForm" action="{{ route('admin.database.flush') }}" method="POST" style="display:none;">@csrf</form>

          <button type="button" onclick="if(confirm('Run migrations and reseed the database?')) { document.getElementById('reseedForm').submit(); }" class="btn btn-outline-danger">Run Migrations & Reseed</button>
          
          <form id="reseedForm" action="{{ route('admin.database.reseed') }}" method="POST" style="display:none;">@csrf</form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection