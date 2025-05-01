@extends('layouts.app')

@section('title', 'Tambah Admin Baru')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/hakases/hakases.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Admin Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.hakakses.index') }}">Hak Akses</a></div>
                <div class="breadcrumb-item active">Tambah Admin</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Tambah Admin</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <ul class="nav nav-tabs" id="userCreationTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="manual-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="true">Manual</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="google-tab" data-toggle="tab" href="#google" role="tab" aria-controls="google" aria-selected="false">Google</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="userCreationTabContent">
                        <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                            <form action="{{ route('admin.hakakses.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="auth_type" value="manual">
                                <div class="form-group row mb-4">
                                    <label for="name" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama
                                        Lengkap</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="email"
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="password"
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <small class="form-text text-muted">Password minimal 8 karakter</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="role" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="{{ route('admin.hakakses.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="google" role="tabpanel" aria-labelledby="google-tab">
                            <form action="{{ route('admin.hakakses.invite-google') }}" method="POST">
                                @csrf
                                <input type="hidden" name="auth_type" value="google">

                                <div class="form-group row mb-4">
                                    <label for="email_invite" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="email" name="email" id="email_invite" class="form-control"
                                            value="{{ old('email') }}" required>
                                        <small class="form-text text-muted">Masukkan email Google yang akan diundang</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="role_google" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="role" id="role_google" class="form-control" required>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fab fa-google mr-2"></i>Kirim Undangan
                                        </button>
                                        <a href="{{ route('admin.hakakses.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
