@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/orders/orders.css') }}">
@endpush

@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Daftar Pesanan Saya</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title m-0">Riwayat Pesanan</h2>
                        <div>
                            <a href="{{ route('products.catalog') }}" class="btn btn-icon icon-left btn-primary mr-2">
                                <i class="fas fa-shopping-bag"></i> Belanja Produk
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-icon icon-left btn-secondary">
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

                    @if (count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="orders-table">
                                <thead>
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status Pesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($order->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif ($order->status == 'processing')
                                                    <span class="badge badge-info">Diproses</span>
                                                @elseif ($order->status == 'completed')
                                                    <span class="badge badge-success">Selesai</span>
                                                @elseif ($order->status == 'cancelled')
                                                    <span class="badge badge-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->invoice->status == 'unpaid')
                                                    <span class="badge badge-danger">Belum Dibayar</span>
                                                @elseif ($order->invoice->status == 'waiting_confirmation')
                                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                                @elseif ($order->invoice->status == 'paid')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif ($order->invoice->status == 'cancelled')
                                                    <span class="badge badge-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="btn btn-icon btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state" data-height="400">
                            <div class="empty-state-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h2>Anda belum memiliki pesanan</h2>
                            <p class="lead">
                                Silakan belanja terlebih dahulu untuk melihat riwayat pesanan Anda di sini.
                            </p>
                            <a href="{{ route('products.catalog') }}" class="btn btn-primary mt-4">Belanja Sekarang</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables if present
            if ($.fn.DataTable) {
                $('#orders-table').DataTable({
                    responsive: true,
                    ordering: true,
                    paging: true,
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 10,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari...",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });

                // Customize DataTables search box
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_filter input').attr('placeholder', 'Cari...');
                $('.dataTables_filter label').addClass('mb-0');
                $('.dataTables_length select').addClass('custom-select custom-select-sm');
            }
        });
    </script>
@endpush
