@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6 d-none d-md-block" style="background: linear-gradient(135deg, #2a9d8f, #43aa8b);">
                        <div class="d-flex align-items-center justify-content-center h-100 p-4">
                            <img src="{{ asset('img/img/reset-password-confirmation.jpg') }}"
                                alt="Reset Password Illustration"
                                class="img-fluid reset-illustration"
                                style="max-height: 400px; filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.2));">
                        </div>
                    </div>
                    <div class="col-md-6" style="background-color: #f8f9fa;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold" style="color: #2a9d8f;">{{ __('Reset Password') }}</h2>
                                <p class="text-muted">Create a new secure password</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success mb-4" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @error('email')
                                <div class="alert alert-danger mb-4" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                            @error('token')
                                <div class="alert alert-danger mb-4" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label text-muted">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ $email ?? old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label text-muted">{{ __('New Password') }}</label>
                                    <input id="password"
                                        type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label text-muted">{{ __('Confirm New Password') }}</label>
                                    <input id="password-confirm"
                                        type="password"
                                        class="form-control form-control-lg"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-lg w-100" style="border-radius: 10px; background: linear-gradient(to right, #e9c46a, #f4a261); color: #2a3541; border: none; font-weight: 600; height: 48px;">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a class="text-decoration-none fw-bold" href="{{ route('login') }}" style="color: #2a9d8f;">
                                        {{ __('Back to Login') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #2a9d8f;
    box-shadow: 0 0 0 0.2rem rgba(42, 157, 143, 0.25);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 7px 14px rgba(233, 196, 106, 0.15), 0 3px 6px rgba(233, 196, 106, 0.1);
}

.card {
    border-radius: 15px;
}

.reset-illustration {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
    100% {
        transform: translateY(0px);
    }
}

/* Floating sparkle effects */
.container {
    position: relative;
    overflow: hidden;
}

.container::before,
.container::after {
    content: "✧";
    position: absolute;
    font-size: 24px;
    color: rgba(233, 196, 106, 0.3);
    z-index: -1;
}

.container::before {
    top: 15%;
    left: 10%;
    animation: float-sparkle 8s ease-in-out infinite;
}

.container::after {
    bottom: 20%;
    right: 15%;
    animation: float-sparkle 6s ease-in-out infinite 1s;
}

@keyframes float-sparkle {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(180deg);
    }
    100% {
        transform: translateY(0px) rotate(360deg);
    }
}
</style>
@endsection
