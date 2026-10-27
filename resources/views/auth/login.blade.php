@extends('layouts.app')
@section('title', 'Login – Edo Tourism')
@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(rgba(10,26,51,0.85), rgba(10,26,51,0.85)), url('{{ !empty($settings['hero_image']) ? \App\Helpers\Helpers::imageUrl($settings['hero_image']) : "https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600" }}') center/cover no-repeat;">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle shadow" style="width:100px; height:100px; background-color: #ffffff !important; overflow: hidden;">
              <img src="{{ \App\Helpers\Helpers::imageUrl(\App\Models\CmsSetting::first()?->logo ?? '') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#loginLogoModal">
          </div>
          <h2 class="text-white fw-bold" style="font-family:'Playfair Display',serif">Welcome Back</h2>
          <p class="text-white-50 small">Sign in to your Edo Tourism account</p>
        </div>
        <div class="card border-0 shadow-lg" style="border-radius:16px">
          <div class="card-body p-4">
            @if(session('danger'))<div class="alert alert-danger py-2 small">{{ session('danger') }}</div>@endif
            <form method="POST" action="{{ route('auth.login_post') }}">
              @csrf
              <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <div class="input-group">
                  <span class="input-group-text bg-blue-100 border-0"><i class="bi bi-envelope text-blue"></i></span>
                  <input type="email" name="email" class="form-control border-0 bg-blue-100 @error('email') is-invalid @enderror" placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-blue-100 border-0"><i class="bi bi-lock text-blue"></i></span>
                  <input type="password" name="password" id="pwd" class="form-control border-0 bg-blue-100" placeholder="••••••••" required>
                  <button class="btn bg-blue-100 border-0" type="button" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="small text-muted text-decoration-none">Forgot Password?</a>
              </div>
              <button type="submit" class="btn btn-blue w-100 fw-semibold py-2">Sign In <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
            <hr class="my-3">
            <p class="text-center small text-muted mb-2 fw-semibold">Quick Demo Access</p>
            <div class="d-flex flex-column gap-1">
              <a href="{{ route('auth.quick_login', 'superadmin') }}" class="btn btn-gold btn-sm"><i class="bi bi-shield-star me-1"></i>Super Admin</a>
              <a href="{{ route('auth.quick_login', 'admin') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-shield-check me-1"></i>Agency Admin</a>
              <div class="row g-1">
                <div class="col-4"><a href="{{ route('auth.quick_login', 'tourist') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-person me-1"></i>Tourist</a></div>
                <div class="col-4"><a href="{{ route('auth.quick_login', 'guide') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-person-badge me-1"></i>Guide</a></div>
                <div class="col-4"><a href="{{ route('auth.quick_login', 'hotel') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-building me-1"></i>Hotel</a></div>
              </div>
              <a href="{{ route('auth.quick_login', 'restaurant') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1"><i class="bi bi-cup-hot me-1"></i>Restaurant</a>
              <a href="{{ route('auth.quick_login', 'touroperator') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1"><i class="bi bi-map me-1"></i>Tour Operator</a>
              <a href="{{ route('auth.quick_login', 'clublounge') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1"><i class="bi bi-music-note-beamed me-1"></i>Club / Lounge</a>
            </div>
          </div>
          <div class="card-footer bg-gray-50 text-center py-3" style="border-radius:0 0 16px 16px">
            <small>Don't have an account? <a href="{{ route('auth.register') }}" class="text-blue fw-semibold">Register here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
function togglePwd(){
  const p=document.getElementById('pwd'),i=document.getElementById('eyeIcon');
  p.type=p.type==='password'?'text':'password';
  i.className=p.type==='password'?'bi bi-eye':'bi bi-eye-slash';
}
</script>
</div> <!-- END CONTAINER -->

<!-- LOGO MODAL -->
<div class="modal fade" id="loginLogoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: #fff;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" style="font-family: 'Playfair Display', serif; color: #0d2447;">Edo State Tourism Agency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img src="{{ \App\Helpers\Helpers::imageUrl(\App\Models\CmsSetting::first()?->logo ?? '') }}" alt="Site Logo" class="img-fluid" style="max-height: 60vh; object-fit: contain;">
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-gold px-4" data-bs-dismiss="modal" style="border-radius: 20px;">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
