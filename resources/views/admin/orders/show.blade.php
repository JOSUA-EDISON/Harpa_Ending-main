@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/orders/orders.css') }}">
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Detail Pesanan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></div>
            <div class="breadcrumb-item">Detail Pesanan</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center order-detail-header">
                            <h4>Informasi Pesanan #{{ $order->order_number }}</h4>
                            <div class="order-actions">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-icon icon-left btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>

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

                        <div class="order-detail-section">
                            <h4>Informasi Pelanggan</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted font-weight-normal">Nama</h6>
                                        <h5>{{ $order->user->name }}</h5>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="text-muted font-weight-normal">Email</h6>
                                        <h5>{{ $order->user->email }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted font-weight-normal">Nomor Telepon</h6>
                                        <h5>{{ $order->phone_number }}</h5>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="text-muted font-weight-normal">Tanggal Pesanan</h6>
                                        <h5>{{ $order->created_at->format('d M Y H:i') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="order-detail-section">
                            <h4>Alamat Pengiriman</h4>
                            <div class="order-address">
                                {{ $order->shipping_address }}
                            </div>

                            @if($order->notes)
                                <h4 class="mt-4">Catatan Pesanan</h4>
                                <div class="order-address">
                                    {{ $order->notes }}
                                </div>
                            @endif
                        </div>

                        <div class="order-detail-section">
                            <h4>Item Pesanan</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($item->product && $item->product->image)
                                                            <div class="order-item-image">
                                                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" class="mr-3">
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="order-totals mt-4">
                                <div class="row">
                                    <div class="col-8 text-right">Subtotal:</div>
                                    <div class="col-4 text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-8 text-right">Total:</div>
                                    <div class="col-4 text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Status Pesanan</h4>
                            <div class="mb-4">
                                <h6 class="text-muted font-weight-normal">Status Saat Ini</h6>
                                <h5>
                                    @if ($order->status == 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif ($order->status == 'processing')
                                        <span class="badge badge-info">Diproses</span>
                                    @elseif ($order->status == 'completed')
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </h5>
                            </div>

                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="status">Update Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Informasi Pembayaran</h4>
                            <div class="mb-4">
                                <h6 class="text-muted font-weight-normal">No. Invoice</h6>
                                <h5>{{ $order->invoice->invoice_number }}</h5>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-muted font-weight-normal">Status Pembayaran</h6>
                                <h5>
                                    @if ($order->invoice->status == 'unpaid')
                                        <span class="badge badge-danger">Belum Dibayar</span>
                                    @elseif ($order->invoice->status == 'waiting_confirmation')
                                        <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                    @elseif ($order->invoice->status == 'paid')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif ($order->invoice->status == 'cancelled')
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-muted font-weight-normal">Total Pembayaran</h6>
                                <h5>Rp {{ number_format($order->invoice->amount, 0, ',', '.') }}</h5>
                            </div>

                            @if ($order->invoice->payment_proof)
                                <div class="mb-4">
                                    <h6 class="text-muted font-weight-normal">Bukti Pembayaran</h6>
                                    <div class="payment-proof-image mt-2">
                                        <a href="{{ Storage::url($order->invoice->payment_proof) }}" target="_blank">
                                            <img src="{{ Storage::url($order->invoice->payment_proof) }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                                        </a>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-muted font-weight-normal">Metode Pembayaran</h6>
                                    <h5>{{ $order->invoice->payment_method }}</h5>
                                </div>
                            @endif

                            @if ($order->invoice->status == 'waiting_confirmation')
                                <form action="{{ route('admin.invoices.update-status', $order->invoice) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="paid">

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">Konfirmasi Pembayaran</button>
                                    </div>
                                </form>
                            @endif

                            <a href="{{ route('invoices.print', $order->invoice) }}" class="btn btn-light btn-block mt-3" target="_blank">
                                <i class="fas fa-print"></i> Cetak Invoice
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Riwayat Status</h4>
                            <div class="timeline-steps">
                                <div class="timeline-step">
                                    <div class="timeline-step-icon">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title">Pesanan Dibuat</div>
                                        <div class="timeline-step-date">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>

                                <!-- Contoh timeline untuk status lain -->
                                @if ($order->status != 'pending')
                                <div class="timeline-step">
                                    <div class="timeline-step-icon">
                                        <i class="fas fa-sync"></i>
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title">Pesanan Diproses</div>
                                        <div class="timeline-step-date">{{ $order->updated_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                                @endif

                                @if ($order->status == 'completed')
                                <div class="timeline-step">
                                    <div class="timeline-step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title">Pesanan Selesai</div>
                                        <div class="timeline-step-date">{{ $order->updated_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                                @endif

                                @if ($order->status == 'cancelled')
                                <div class="timeline-step">
                                    <div class="timeline-step-icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title">Pesanan Dibatalkan</div>
                                        <div class="timeline-step-date">{{ $order->updated_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
