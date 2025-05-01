@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6 d-none d-md-block" style="background: linear-gradient(135deg, #2a9d8f, #43aa8b);">
                        <div class="d-flex align-items-center justify-content-center h-100 p-4">
                            <img src="{{ asset('img/img/verify.jpg') }}"
                                alt="Music Illustration"
                                class="img-fluid verify-illustration"
                                style="max-height: 400px; filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.2));">
                        </div>
                    </div>
                    <div class="col-md-6" style="background-color: #f8f9fa;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold" style="color: #2a9d8f;">
                                    {{ $isRegistration ? __('Complete Registration') : __('Login Verification') }}
                                </h2>
                                <p class="text-muted">Verify your email to continue</p>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="alert border-start border-4 border-info bg-info bg-opacity-10" style="border-radius: 10px;">
                                @if($isRegistration)
                                    Please enter the verification code sent to your email to complete your registration.
                                @else
                                    Please enter the verification code sent to your email to complete login.
                                @endif
                            </div>

                            <form method="POST" action="{{ route('verification.verify') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label text-muted">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email', $email) }}"
                                        required
                                        readonly
                                        style="border-radius: 10px; border: 1px solid #e2e8f0; background-color: #f8fafc;">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="otp" class="form-label text-muted">{{ __('Verification Code') }}</label>
                                    <input id="otp" type="text"
                                        class="form-control form-control-lg @error('otp') is-invalid @enderror"
                                        name="otp"
                                        value="{{ old('otp') }}"
                                        required
                                        autofocus
                                        placeholder="Enter 6-digit code"
                                        style="border-radius: 10px; border: 1px solid #e2e8f0; letter-spacing: 0.5em; text-align: center;">

                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted d-block text-center mt-2">
                                        Please check your email for the verification code.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-lg w-100"
                                        style="border-radius: 10px; background: linear-gradient(to right, #e9c46a, #f4a261); color: #2a3541; border: none; font-weight: 600; height: 48px;">
                                        {{ $isRegistration ? __('Complete Registration') : __('Verify and Login') }}
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a class="text-decoration-none"
                                        href="{{ route('verification.resend') }}"
                                        onclick="event.preventDefault(); document.getElementById('resend-form').submit();"
                                        style="color: #2a9d8f;">
                                        {{ __('Resend Code') }}
                                    </a>
                                </div>
                            </form>

                            <form id="resend-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email', $email) }}">
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

.verify-illustration {
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

/* Floating musical notes */
.container {
    position: relative;
    overflow: hidden;
}

.container::before,
.container::after {
    content: "♪";
    position: absolute;
    font-size: 24px;
    color: rgba(233, 196, 106, 0.3);
    z-index: -1;
}

.container::before {
    top: 15%;
    left: 10%;
    animation: float-note 8s ease-in-out infinite;
}

.container::after {
    bottom: 20%;
    right: 15%;
    animation: float-note 6s ease-in-out infinite 1s;
}

@keyframes float-note {
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
