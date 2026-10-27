@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-2">Demo Access</h1>
                    <p class="text-muted mb-4">
                        Enter the demo access secret to continue to the selected Quick Access account.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.demo_access.post', ['role' => $role]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="demo_secret" class="form-label">Demo access secret</label>
                            <input
                                id="demo_secret"
                                name="demo_secret"
                                type="password"
                                class="form-control"
                                autocomplete="current-password"
                                maxlength="512"
                                required
                                autofocus
                            >
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Continue to Demo
                        </button>
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Return to Login
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
