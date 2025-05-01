@extends('layouts.app')

@section('title', 'Ganti Password')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="row justify-content-center profile-container">
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Ganti Password') }}</span>
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-edit">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('Back to Profile') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="profile-avatar">
                            <div class="avatar-circle">
                                <i class="fa fa-lock" style="font-size: 36px;"></i>
                            </div>
                            <div class="profile-name">{{ __('Password Security') }}</div>
                            <div class="profile-email">{{ __('Update your password regularly for better security') }}</div>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.password') }}" class="profile-form">
                            @csrf
                            @method('PUT')
                            <div class="form-group row mb-3">
                                <label for="current_password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password Sekarang') }}</label>

                                <div class="col-md-6">
                                    <input id="current_password" type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        name="current_password" required autocomplete="current_password">

                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="new_password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password Baru') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="password"
                                        class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                        required autocomplete="new_password">

                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Password minimal 8 karakter</small>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label for="new_password_confirmation"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi Password Baru') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password_confirmation" type="password"
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                        name="new_password_confirmation" required autocomplete="new_password_confirmation">

                                    @error('new_password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary submit-btn">
                                        <i class="fa fa-key me-1"></i> {{ __('Ganti Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
