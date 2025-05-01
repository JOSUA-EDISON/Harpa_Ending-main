@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6 d-none d-md-block" style="background: linear-gradient(135deg, #2a9d8f, #43aa8b);">
                        <div class="d-flex align-items-center justify-content-center h-100 p-4">
                            <img src="{{ asset('img/img/register.jpg') }}"
                                alt="Music Illustration"
                                class="img-fluid register-illustration"
                                style="max-height: 400px; filter: drop-shadow(0 10px 8px rgb(0 0 0 / 0.2));">
                        </div>
                    </div>
                    <div class="col-md-6" style="background-color: #f8f9fa;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold" style="color: #2a9d8f;">{{ __('Create Account') }}</h2>
                                <p class="text-muted">Join our musical community</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label text-muted">{{ __('Name') }}</label>
                                    <input id="name" type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="name"
                                        autofocus
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label text-muted">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label text-muted">{{ __('Password') }}</label>
                                    <input id="password" type="password"
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
                                    <label for="password-confirm" class="form-label text-muted">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password"
                                        class="form-control form-control-lg"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-lg w-100" style="border-radius: 10px; background: linear-gradient(to right, #e9c46a, #f4a261); color: #2a3541; border: none; font-weight: 600; height: 48px;">
                                        {{ __('Register') }}
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <a href="{{ route('auth.google') }}" class="btn btn-lg w-100" style="border-radius: 10px; background: #DB4437; color: white; border: none; font-weight: 600; height: 48px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                        </svg>
                                        {{ __('Register with Google') }}
                                    </a>
                                </div>

                                <div class="text-center">
                                    <span class="text-muted">Already have an account?</span>
                                    <a class="text-decoration-none ms-1 fw-bold" href="{{ route('login') }}" style="color: #2a9d8f;">
                                        {{ __('Login') }}
                                    </a>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ url('/') }}" class="btn btn-lg w-100" style="border-radius: 10px; background: linear-gradient(to right, #e9c46a, #f4a261); color: #2a3541; border: none; font-weight: 600; margin-top: 10px;">
                                        &larr; Kembali ke Halaman Utama
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

.register-illustration {
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
