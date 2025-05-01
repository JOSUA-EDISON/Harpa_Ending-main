@extends('layouts.app')

@section('title', 'Pengaturan API')

@php
    use App\Models\Setting;
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/settings.css') }}">
@endpush

@section('content')
    <!-- Make sure CSRF token is available -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="section-header">
        <h1>Pengaturan API</h1>
    </div>

    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <h2 class="section-title">Pengaturan API</h2>
        <p class="section-lead">
            Manajemen pengaturan API untuk RajaOngkir dan Midtrans.
        </p>

        <!-- Connection Status Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h4>Status Koneksi API</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="connection-status-item" id="rajaongkir-connection-info">
                                    <h6><i class="fas fa-shipping-fast mr-2"></i> RajaOngkir</h6>
                                    <div class="connection-details text-muted">
                                        <p>Klik "Test Koneksi API" untuk melihat status koneksi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="connection-status-item" id="midtrans-connection-info">
                                    <h6><i class="fas fa-credit-card mr-2"></i> Midtrans</h6>
                                    <div class="connection-details text-muted">
                                        <p>Klik "Test Koneksi API" untuk melihat status koneksi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- RajaOngkir Settings -->
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card api-settings-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Pengaturan RajaOngkir</h4>
                        <div id="rajaongkir-status">
                            <span class="badge badge-secondary">Belum diperiksa</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update-rajaongkir') }}" method="POST">
                            @csrf
                            @method('PUT')

                            @foreach ($rajaongkirSettings as $setting)
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                    @if ($setting->key == 'rajaongkir_package')
                                        <select class="form-control" name="{{ $setting->key }}" id="{{ $setting->key }}">
                                            <option value="starter" {{ $setting->value == 'starter' ? 'selected' : '' }}>
                                                Starter</option>
                                            <option value="basic" {{ $setting->value == 'basic' ? 'selected' : '' }}>Basic
                                            </option>
                                            <option value="pro" {{ $setting->value == 'pro' ? 'selected' : '' }}>Pro
                                            </option>
                                        </select>
                                    @else
                                        <div class="input-group api-key-group">
                                            <input type="password" class="form-control" id="{{ $setting->key }}"
                                                name="{{ $setting->key }}" value="{{ $setting->value }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-secondary copy-api-key"
                                                    title="Salin">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary toggle-password"
                                                    title="Tampilkan/Sembunyikan">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    @error($setting->key)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="form-group">
                                <button type="button" id="test-rajaongkir" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i> Test Koneksi API
                                </button>
                                <button type="button" id="debug-rajaongkir" class="btn btn-secondary">
                                    <i class="fas fa-bug"></i> Debug
                                </button>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Update RajaOngkir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Midtrans Settings -->
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card api-settings-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>
                            Pengaturan Midtrans
                            @if (Setting::getValueByKey('midtrans_is_production') == 'true')
                                <span class="environment-badge production">Production</span>
                            @else
                                <span class="environment-badge sandbox">Sandbox</span>
                            @endif
                        </h4>
                        <div id="midtrans-status">
                            <span class="badge badge-secondary">Belum diperiksa</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update-midtrans') }}" method="POST">
                            @csrf
                            @method('PUT')

                            @foreach ($midtransSettings as $setting)
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                    @if ($setting->key == 'midtrans_is_production')
                                        <select class="form-control" name="{{ $setting->key }}" id="{{ $setting->key }}">
                                            <option value="false" {{ $setting->value == 'false' ? 'selected' : '' }}>
                                                Sandbox/Development</option>
                                            <option value="true" {{ $setting->value == 'true' ? 'selected' : '' }}>
                                                Production</option>
                                        </select>
                                    @else
                                        <div class="input-group api-key-group">
                                            <input type="password" class="form-control" id="{{ $setting->key }}"
                                                name="{{ $setting->key }}" value="{{ $setting->value }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-secondary copy-api-key"
                                                    title="Salin">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary toggle-password"
                                                    title="Tampilkan/Sembunyikan">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    @error($setting->key)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="form-group">
                                <button type="button" id="test-midtrans" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i> Test Koneksi API
                                </button>
                                <button type="button" id="debug-midtrans" class="btn btn-secondary">
                                    <i class="fas fa-bug"></i> Debug
                                </button>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Update Midtrans</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/settings.js') }}"></script>

    <!-- Direct Test Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Direct test link for manual debugging
            const debugDiv = document.createElement('div');
            debugDiv.className = 'fixed-bottom bg-light p-3 border-top';
            debugDiv.style.display = 'none';
            debugDiv.innerHTML = `
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h5>API Debug Tools</h5>
                            <hr>
                            <button id="direct-test-rajaongkir" class="btn btn-sm btn-danger mr-2">Direct Test RajaOngkir</button>
                            <button id="direct-test-midtrans" class="btn btn-sm btn-danger mr-2">Direct Test Midtrans</button>
                            <button id="check-csrf" class="btn btn-sm btn-secondary mr-2">Check CSRF Token</button>
                            <button id="toggle-debug-panel" class="btn btn-sm btn-dark ml-2">Hide Debug Panel</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(debugDiv);

            // Show debug panel with keyboard shortcut (Ctrl+Shift+D)
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                    debugDiv.style.display = debugDiv.style.display === 'none' ? 'block' : 'none';
                }
            });

            // Toggle debug panel button
            document.getElementById('toggle-debug-panel').addEventListener('click', function() {
                debugDiv.style.display = 'none';
            });

            // Direct test RajaOngkir
            document.getElementById('direct-test-rajaongkir').addEventListener('click', function() {
                const apiKey = document.getElementById('rajaongkir_api_key')?.value;
                if (!apiKey) {
                    alert('API Key RajaOngkir kosong!');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    alert('CSRF Token tidak ditemukan!');
                    return;
                }

                alert('Mengirim request test RajaOngkir. Cek console untuk hasil.');

                fetch('/admin/settings/test-rajaongkir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ api_key: apiKey })
                })
                .then(response => {
                    console.log('Direct Test RajaOngkir - Status:', response.status);
                    return response.text().then(text => {
                        try {
                            return text ? JSON.parse(text) : {};
                        } catch (e) {
                            console.error('Failed to parse response:', text);
                            return { error: 'Failed to parse response' };
                        }
                    });
                })
                .then(data => {
                    console.log('Direct Test RajaOngkir - Response:', data);
                    alert('Test selesai. Hasil: ' + (data.success ? 'BERHASIL' : 'GAGAL') + '\n' + (data.message || ''));
                })
                .catch(error => {
                    console.error('Direct Test RajaOngkir - Error:', error);
                    alert('Test gagal: ' + error.message);
                });
            });

            // Direct test Midtrans
            document.getElementById('direct-test-midtrans').addEventListener('click', function() {
                const serverKey = document.getElementById('midtrans_server_key')?.value;
                if (!serverKey) {
                    alert('Server Key Midtrans kosong!');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    alert('CSRF Token tidak ditemukan!');
                    return;
                }

                alert('Mengirim request test Midtrans. Cek console untuk hasil.');

                fetch('/admin/settings/test-midtrans', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ server_key: serverKey })
                })
                .then(response => {
                    console.log('Direct Test Midtrans - Status:', response.status);
                    return response.text().then(text => {
                        try {
                            return text ? JSON.parse(text) : {};
                        } catch (e) {
                            console.error('Failed to parse response:', text);
                            return { error: 'Failed to parse response' };
                        }
                    });
                })
                .then(data => {
                    console.log('Direct Test Midtrans - Response:', data);
                    alert('Test selesai. Hasil: ' + (data.success ? 'BERHASIL' : 'GAGAL') + '\n' + (data.message || ''));
                })
                .catch(error => {
                    console.error('Direct Test Midtrans - Error:', error);
                    alert('Test gagal: ' + error.message);
                });
            });

            // Check CSRF token
            document.getElementById('check-csrf').addEventListener('click', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                alert(csrfToken ? 'CSRF Token tersedia: ' + csrfToken.substring(0, 10) + '...' : 'CSRF Token tidak ditemukan!');
            });
        });
    </script>
@endpush

