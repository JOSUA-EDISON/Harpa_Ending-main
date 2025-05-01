@extends('layouts.app')

@section('title', 'Edit Data Hak Akses')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/hakases/hakases.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Hak Akses</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.hakakses.index') }}">Hak Akses</a></div>
                <div class="breadcrumb-item active">Edit Data</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Card untuk Edit Role -->
            <div class="card">
                <div class="card-header">
                    <h4>Form Edit Role</h4>
                </div>
                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>×</span></button>
                                {{ session('message') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>×</span></button>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.hakakses.update', $hakakses->id) }}" method="POST" onsubmit="confirmUpdate(event)">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-4">
                            <label for="name" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $hakakses->name }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="email" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email" id="email" class="form-control" value="{{ $hakakses->email }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="role" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                            <div class="col-sm-12 col-md-7">
                                <select name="role" id="role" class="form-control" required>
                                    <option value="admin" {{ $hakakses->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $hakakses->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button type="submit" class="btn btn-primary">Update Role</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card untuk Manajemen Password -->
            <div class="card">
                <div class="card-header">
                    <h4>Reset Password</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>×</span></button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Reset Password dengan OTP -->
                    <div class="section-title mt-0">Reset Password dengan OTP</div>
                    <p class="text-muted">Reset password akan mengirimkan password baru ke email pengguna dan memaksa logout dari semua sesi.</p>
                    <div class="row mb-4">
                        <div class="col-12">
                            @if(session('otp_sent'))
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Kode OTP telah dikirim ke email {{ $hakakses->email }}
                                </div>
                                <form action="{{ route('admin.hakakses.verify-otp', $hakakses->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="otp" class="form-control" placeholder="Masukkan kode OTP" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Verifikasi & Reset Password</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('admin.hakakses.send-otp', $hakakses->id) }}" method="POST" onsubmit="confirmResetPassword(event)">
                                    @csrf
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-key"></i> Kirim OTP untuk Reset Password
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Update Password Manual -->
                    <div class="section-title">Update Password Manual</div>
                    <p class="text-muted">Update password secara manual akan memaksa logout dari semua sesi.</p>
                    <form action="{{ route('admin.hakakses.update-password', $hakakses->id) }}" method="POST" onsubmit="confirmUpdate(event)">
                        @csrf
                        @method('PUT')
                        <div class="form-group row mb-4">
                            <label for="new_password" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password Baru</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimal 8 karakter</small>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="new_password_confirmation" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Konfirmasi Password</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Password
                                </button>
                                <a href="{{ route('admin.hakakses.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('components.sweet-alert')
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
