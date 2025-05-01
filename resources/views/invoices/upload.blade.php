@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/invoices/invoices.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Upload Bukti Pembayaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></div>
                <div class="breadcrumb-item active">Upload Bukti Pembayaran</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                    <div class="card invoice-card">
                        <div class="card-header">
                            <h4 class="card-title">Upload Bukti Pembayaran</h4>
                            <div class="card-header-action">
                                <a href="{{ route('orders.show', $invoice->order) }}" class="btn btn-icon btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body invoice-detail">
                            <div class="invoice-status text-right mb-4">
                                Status:
                                @if ($invoice->status == 'unpaid')
                                    <span class="status-badge status-unpaid">Belum Dibayar</span>
                                @elseif ($invoice->status == 'waiting_confirmation')
                                    <span class="status-badge status-waiting">Menunggu Konfirmasi</span>
                                @elseif ($invoice->status == 'paid')
                                    <span class="status-badge status-paid">Lunas</span>
                                @elseif ($invoice->status == 'cancelled')
                                    <span class="status-badge status-cancelled">Dibatalkan</span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-info-item">
                                        <div class="label">No. Invoice</div>
                                        <div class="value">{{ $invoice->invoice_number }}</div>
                                    </div>
                                    <div class="invoice-info-item">
                                        <div class="label">No. Pesanan</div>
                                        <div class="value">{{ $invoice->order->order_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-info-item">
                                        <div class="label">Tanggal</div>
                                        <div class="value">{{ $invoice->created_at->format('d M Y') }}</div>
                                    </div>
                                    <div class="invoice-info-item">
                                        <div class="label">Total Pembayaran</div>
                                        <div class="value total-amount">Rp
                                            {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-4">
                                <p class="mb-0">
                                    <strong>Petunjuk Pembayaran:</strong><br>
                                    1. Silakan transfer ke salah satu rekening berikut:<br>
                                    - Bank BCA: 1234567890 a.n. Gallery Bejo<br>
                                    - Bank Mandiri: 0987654321 a.n. Gallery Bejo<br>
                                    2. Transfer tepat sebesar <strong>Rp
                                        {{ number_format($invoice->amount, 0, ',', '.') }}</strong><br>
                                    3. Upload bukti pembayaran di bawah ini<br>
                                    4. Pembayaran akan dikonfirmasi oleh admin dalam 1x24 jam
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="payment-upload">
                                <h5 class="payment-upload-title">Form Upload Bukti Pembayaran</h5>
                                <form action="{{ route('invoices.upload', $invoice) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="Bank BCA">Bank BCA</option>
                                            <option value="Bank Mandiri">Bank Mandiri</option>
                                            <option value="Bank BNI">Bank BNI</option>
                                            <option value="Bank BRI">Bank BRI</option>
                                            <option value="Bank Lainnya">Bank Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Bukti Pembayaran</label>
                                        <div class="custom-file">
                                            <input type="file" name="payment_proof" class="custom-file-input"
                                                id="payment_proof" accept="image/*" required>
                                            <label class="custom-file-label" for="payment_proof">Pilih gambar...</label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Format yang diterima: JPG, JPEG, PNG. Ukuran maksimal 2MB.
                                        </small>
                                    </div>

                                    <div class="form-group" id="image-preview" style="display: none;">
                                        <label>Preview</label>
                                        <div class="mt-2">
                                            <img src="" id="preview-image" class="payment-proof-preview">
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">Upload Bukti Pembayaran</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Preview uploaded image
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('preview-image');
                const previewContainer = document.getElementById('image-preview');

                reader.onload = function(event) {
                    preview.src = event.target.result;
                    previewContainer.style.display = 'block';
                }

                reader.readAsDataURL(file);

                // Update file input label
                const fileName = file.name;
                const label = document.querySelector('label.custom-file-label');
                label.textContent = fileName;
            }
        });
    </script>
@endpush
