@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/orders/orders.css') }}">
@endpush

@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Manajemen Pesanan</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title m-0">Daftar Pesanan</h2>
                        <div class="d-flex">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-icon icon-left dropdown-toggle" type="button"
                                    id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <a class="dropdown-item {{ !isset($status) ? 'active' : '' }}"
                                        href="{{ route('admin.orders.index') }}">Semua Status</a>
                                    <a class="dropdown-item {{ isset($status) && $status == 'pending' ? 'active' : '' }}"
                                        href="{{ route('admin.orders.filter', ['status' => 'pending']) }}">Menunggu</a>
                                    <a class="dropdown-item {{ isset($status) && $status == 'processing' ? 'active' : '' }}"
                                        href="{{ route('admin.orders.filter', ['status' => 'processing']) }}">Diproses</a>
                                    <a class="dropdown-item {{ isset($status) && $status == 'completed' ? 'active' : '' }}"
                                        href="{{ route('admin.orders.filter', ['status' => 'completed']) }}">Selesai</a>
                                    <a class="dropdown-item {{ isset($status) && $status == 'cancelled' ? 'active' : '' }}"
                                        href="{{ route('admin.orders.filter', ['status' => 'cancelled']) }}">Dibatalkan</a>
                                </div>
                            </div>
                            <a href="{{ route('admin.orders.export', isset($status) ? ['status' => $status] : []) }}"
                                class="btn btn-light ml-2">
                                <i class="fas fa-file-export"></i> Export
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

                    <div class="table-responsive">
                        <table class="table table-striped" id="orders-table">
                            <thead>
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status Pesanan</th>
                                    <th>Status Pembayaran</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
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
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="btn btn-icon btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-shopping-bag"></i>
                                                </div>
                                                <h2>Tidak ada pesanan</h2>
                                                <p class="lead">
                                                    Belum ada pesanan yang dibuat.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
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
                    searching: true,
                    ordering: true,
                    paging: false, // We're using Laravel's pagination
                    info: false,
                    autoWidth: false,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari...",
                    },
                    "dom": '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>'
                });

                // Customize DataTables search box
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_filter input').attr('placeholder', 'Cari...');
                $('.dataTables_filter label').addClass('mb-0');
            }
        });
    </script>
@endpush
