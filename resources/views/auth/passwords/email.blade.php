@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6 d-none d-md-block" style="background: linear-gradient(135deg, #f4efd3, #e7f0d3);">
                        <div class="d-flex align-items-center justify-content-center h-100 p-4">
                            <img src="{{ asset('img/img/reset-password.jpg') }}"
                                alt="Reset Password Illustration"
                                class="img-fluid reset-illustration"
                                style="max-height: 400px; filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.2));">
                        </div>
                    </div>
                    <div class="col-md-6" style="background-color: #f9f7ed;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold" style="color: #c9a253;">{{ __('Reset Password') }}</h2>
                                <p class="text-muted">Enter your email to receive OTP code</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #e7f0d3; border-color: #e7f0d3; color: #5a6851;">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label" style="color: #91784a;">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        style="border-radius: 10px; border: 1px solid #dfd6b3; background-color: #fefdf9;">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-lg w-100"
                                        style="border-radius: 10px; background: linear-gradient(to right, #c9a253, #d9b66b); color: #4a3e27; border: none; font-weight: 600; height: 48px;">
                                        {{ __('Send OTP Code') }}
                                    </button>
                                </div>

                                <div class="text-center">
                                    <span class="text-muted">Remember your password?</span>
                                    <a class="text-decoration-none ms-1 fw-bold" href="{{ route('login') }}" style="color: #c9a253;">
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
    border-color: #c9a253;
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 83, 0.25);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 7px 14px rgba(201, 162, 83, 0.15), 0 3px 6px rgba(201, 162, 83, 0.1);
}

.card {
    border-radius: 15px;
}

.reset-illustration {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-15px) rotate(2deg);
    }
    100% {
        transform: translateY(0px) rotate(0deg);
    }
}

/* Floating petals */
.container {
    position: relative;
    overflow: hidden;
}

.container::before,
.container::after {
    content: "✿";
    position: absolute;
    font-size: 18px;
    color: rgba(255, 255, 255, 0.7);
    z-index: -1;
}

.container::before {
    top: 15%;
    left: 10%;
    animation: float-petal 10s ease-in-out infinite;
}

.container::after {
    bottom: 20%;
    right: 15%;
    animation: float-petal 8s ease-in-out infinite 1s;
}

@keyframes float-petal {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(15deg);
    }
    100% {
        transform: translateY(0px) rotate(0deg);
    }
}
</style>
@endsection
