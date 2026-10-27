@extends('layouts.app')
@section('title', 'Reset Password - Edo Tourism')
@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(rgba(10,26,51,0.85), rgba(10,26,51,0.85)), url('{{ !empty($settings['hero_image']) ? \App\Helpers\Helpers::imageUrl($settings['hero_image']) : 'https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600' }}') center/cover no-repeat;">
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-lg" style="border-radius: 16px; background: rgba(255,255,255,0.95);"><div class="card-body p-4">
            <div class="text-center mb-4"><h3 class="fw-bold text-blue" style="font-family: 'Playfair Display', serif;">Reset Password</h3></div>
            <form method="POST" action="{{ route('password.update') }}">@csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3"><label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="mb-3"><label class="form-label small fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="mb-3"><label class="form-label small fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required></div>
                <button type="submit" class="btn btn-gold w-100">Reset Password</button>
            </form>
        </div></div>
    </div></div></div>
</div>
@endsection