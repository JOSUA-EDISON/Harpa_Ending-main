@extends('layouts.app')

@section('title', 'Detail Invoice')

@section('css')
<link rel="stylesheet" href="{{ asset('css/invoices/invoices.css') }}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Invoice</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
            @if(Auth::user()->role == 'admin')
                <div class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Manajemen Invoice</a></div>
            @else
                <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></div>
            @endif
            <div class="breadcrumb-item active">Detail Invoice</div>
        </div>
    </div>

    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="invoice-detail-title">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Invoice #{{ $invoice->invoice_number }}</h2>
                </div>
                <div class="col-md-6 text-md-right">
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
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card invoice-card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Invoice</h4>
                        <div class="card-header-action">
                            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-icon btn-primary" target="_blank">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </div>
                    </div>
                    <div class="card-body invoice-detail">
                        <div class="invoice-info">
                            <div class="invoice-info-section">
                                <h4>Dari</h4>
                                <div class="invoice-info-item">
                                    <div class="value">Gallery Bejo</div>
                                    <div>Jl. Singgalang No.5, Pisang Candi</div>
                                    <div>Kec. Sukun, Kota Malang</div>
                                    <div>Jawa Timur 65146</div>
                                    <div>Email: info@gallerybejo.com</div>
                                    <div>Telp: (0341) 123456</div>
                                </div>
                            </div>

                            <div class="invoice-info-section">
                                <h4>Kepada</h4>
                                <div class="invoice-info-item">
                                    <div class="value">{{ $invoice->order->user->name }}</div>
                                    <div>{{ $invoice->order->shipping_address }}</div>
                                    <div>Telp: {{ $invoice->order->phone_number }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-info-item">
                                    <div class="label">No. Invoice</div>
                                    <div class="value">{{ $invoice->invoice_number }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-info-item">
                                    <div class="label">Tanggal</div>
                                    <div class="value">{{ $invoice->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-info-item">
                                    <div class="label">No. Pesanan</div>
                                    <div class="value">
                                        <a href="{{ Auth::user()->role == 'admin' ? route('admin.orders.show', $invoice->order) : route('orders.show', $invoice->order) }}" class="text-primary">
                                            {{ $invoice->order->order_number }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-info-item">
                                    <div class="label">Jatuh Tempo</div>
                                    <div class="value">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-items">
                            <table class="table table-striped table-accent">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-footer">
                                        <td colspan="3" class="text-right">Total</td>
                                        <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="invoice-total">
                            <span class="total-label">Total Pembayaran:</span>
                            <span class="total-amount">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="invoice-notes mt-4">
                            <h5>Catatan:</h5>
                            <p>Terima kasih telah berbelanja di Gallery Bejo. Untuk pembayaran, silakan transfer ke rekening berikut:</p>
                            <ul>
                                <li>Bank BCA: 1234567890 a.n. Gallery Bejo</li>
                                <li>Bank Mandiri: 0987654321 a.n. Gallery Bejo</li>
                            </ul>

                            @if ($invoice->status == 'unpaid')
                                <p>Harap lakukan pembayaran sebelum {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card invoice-card">
                    <div class="card-header">
                        <h4 class="card-title">Status & Tindakan</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="mb-2">Status Invoice:</div>
                            <div class="d-flex align-items-center">
                                <span class="status-indicator {{ $invoice->status == 'paid' ? 'paid' : ($invoice->status == 'waiting_confirmation' ? 'waiting' : ($invoice->status == 'cancelled' ? 'cancelled' : 'unpaid')) }}"></span>
                                @if ($invoice->status == 'unpaid')
                                    <strong>Belum Dibayar</strong>
                                @elseif ($invoice->status == 'waiting_confirmation')
                                    <strong>Menunggu Konfirmasi</strong>
                                @elseif ($invoice->status == 'paid')
                                    <strong>Lunas</strong>
                                @elseif ($invoice->status == 'cancelled')
                                    <strong>Dibatalkan</strong>
                                @endif
                            </div>
                        </div>

                        @if(Auth::user()->role == 'admin')
                            <div class="mb-4">
                                <div class="mb-2">Ubah Status:</div>
                                <form action="{{ route('admin.invoices.update-status', $invoice) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group">
                                        <select name="status" class="form-control">
                                            <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                            <option value="waiting_confirmation" {{ $invoice->status == 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                            <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            @if($invoice->status == 'waiting_confirmation')
                                <div class="d-grid mb-4">
                                    <form action="{{ route('admin.invoices.update-status', $invoice) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                            <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <div class="mb-4">
                            <div class="mb-2">Tindakan:</div>
                            <div class="d-grid">
                                <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-primary btn-block mb-2" target="_blank">
                                    <i class="fas fa-print"></i> Cetak Invoice
                                </a>

                                @if(Auth::user()->role != 'admin' && $invoice->status == 'unpaid')
                                    <a href="{{ route('invoices.upload', $invoice) }}" class="btn btn-success btn-block">
                                        <i class="fas fa-upload"></i> Upload Pembayaran
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="mb-2">Informasi Pembayaran:</div>
                            <div class="mb-1">
                                <small class="text-muted">Jumlah:</small>
                                <div class="font-weight-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="mb-1">
                                <small class="text-muted">Tanggal Pembuatan:</small>
                                <div>{{ $invoice->created_at->format('d M Y') }}</div>
                            </div>
                            @if($invoice->payment_date)
                            <div class="mb-1">
                                <small class="text-muted">Tanggal Pembayaran:</small>
                                <div>{{ $invoice->payment_date->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($invoice->payment_method)
                            <div class="mb-1">
                                <small class="text-muted">Metode Pembayaran:</small>
                                <div>{{ $invoice->payment_method }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if (($invoice->status == 'waiting_confirmation' || $invoice->status == 'paid') && $invoice->payment_proof)
                    <div class="card invoice-card">
                        <div class="card-header">
                            <h4 class="card-title">Bukti Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Metode Pembayaran:</small>
                                <div class="font-weight-bold">{{ $invoice->payment_method }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Tanggal Upload:</small>
                                <div>{{ $invoice->payment_date->format('d M Y H:i') }}</div>
                            </div>
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $invoice->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid payment-proof-preview">
                            </div>
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $invoice->payment_proof) }}" target="_blank" class="btn btn-light btn-block">
                                    <i class="fas fa-eye"></i> Lihat Bukti Asli
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
