@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Undangan Bergabung dengan Harpa</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-4x text-primary mb-3"></i>
                        <h5>Anda telah diundang untuk bergabung dengan Harpa sebagai <strong>{{ ucfirst($invitation->role) }}</strong></h5>
                        <p class="text-muted">Undangan ini berlaku hingga: {{ $invitation->expires_at->format('d M Y, H:i') }}</p>
                    </div>

                    <p>Untuk menerima undangan ini, Anda perlu mengautentikasi dengan akun Google Anda. Pastikan menggunakan akun Google dengan email <strong>{{ $invitation->email }}</strong>.</p>

                    <hr>

                    <div class="text-center mt-4">
                        <a href="{{ route('auth.google.invitation.redirect', $invitation->token) }}" class="btn btn-lg btn-danger">
                            <i class="fab fa-google mr-2"></i> Lanjutkan dengan Google
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>Jika Anda tidak mengenali undangan ini, silakan abaikan atau hubungi administrator.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
