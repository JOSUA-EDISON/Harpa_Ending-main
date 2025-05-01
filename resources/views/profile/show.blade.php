@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="row justify-content-center profile-container">
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Profile') }}</span>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-edit">
                            <i class="fa fa-edit me-1"></i> {{ __('Edit Profile') }}
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @if (session('status'))
                            <div class="alert alert-success m-3" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="profile-avatar">
                            <div class="avatar-circle">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="profile-name">{{ $user->name }}</div>
                            <div class="profile-email">{{ $user->email }}</div>
                        </div>

                        <div class="profile-info">
                            <div class="info-group">
                                <div class="info-label">{{ __('Name') }}</div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">{{ __('Email') }}</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">{{ __('Phone') }}</div>
                                <div class="info-value">
                                    {!! $user->phone_number ?? '<span class="empty-value">Belum diisi</span>' !!}
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">{{ __('Address') }}</div>
                                <div class="info-value">
                                    {!! $user->address ?? '<span class="empty-value">Belum diisi</span>' !!}
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fa fa-edit me-1"></i> {{ __('Edit Profile') }}
                            </a>
                            <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary">
                                <i class="fa fa-lock me-1"></i> {{ __('Change Password') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
