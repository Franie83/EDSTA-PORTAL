@extends('layouts.app')
@section('title', 'Forgot Password - Edo Tourism')
@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(rgba(10,26,51,0.85), rgba(10,26,51,0.85)), url('{{ !empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : 'https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600' }}') center/cover no-repeat;">
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-lg" style="border-radius: 16px; background: rgba(255,255,255,0.95);"><div class="card-body p-4">
            <div class="text-center mb-4"><h3 class="fw-bold text-blue" style="font-family: 'Playfair Display', serif;">Forgot Password</h3><p class="text-muted small">Enter your email to receive a reset link</p></div>
            @if (session('status'))<div class="alert alert-success small">{{ session('status') }}</div>@endif
            <form method="POST" action="{{ route('password.email') }}">@csrf
                <div class="mb-3"><label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <button type="submit" class="btn btn-gold w-100">Send Reset Link</button>
                <div class="text-center mt-3"><a href="{{ route('auth.login') }}" class="small text-muted">Back to Login</a></div>
            </form>
        </div></div>
    </div></div></div>
</div>
@endsection