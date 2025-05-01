@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard Admin</h1>
    </div>

    <div class="row">
        <!-- Total Penjualan All Time -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    <div class="card-label">Total Penjualan (All Time)</div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Bulan Ini -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card dashboard-card bg-success text-white">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-value">Rp {{ number_format($monthlySales, 0, ',', '.') }}</div>
                    <div class="card-label">Penjualan Bulan {{ \Carbon\Carbon::now()->format('F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Total Produk Terjual Bulan Ini -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card dashboard-card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="card-value">{{ number_format($totalMonthlySoldItems, 0, ',', '.') }}</div>
                    <div class="card-label">Produk Terjual Bulan Ini</div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card dashboard-card bg-danger text-white">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-value">{{ $totalUsers }}</div>
                    <div class="card-label">Total User Terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Sales Products -->
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Penjualan Produk Bulan {{ \Carbon\Carbon::now()->format('F Y') }}</h4>
                    <small class="text-muted">Data akan direset setiap bulan</small>
                </div>
                <div class="card-body">
                    @if($monthlySoldProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="40%">Nama Produk</th>
                                        <th class="text-right" width="15%">Jumlah Terjual</th>
                                        <th class="text-right" width="20%">Harga Satuan</th>
                                        <th class="text-right" width="20%">Total Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySoldProducts as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td class="text-right">{{ number_format($item->total_quantity, 0, ',', '.') }} unit</td>
                                        <td class="text-right">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                        <td class="text-right font-weight-bold">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Total:</td>
                                        <td class="text-right font-weight-bold">{{ number_format($totalMonthlySoldItems, 0, ',', '.') }} unit</td>
                                        <td></td>
                                        <td class="text-right font-weight-bold">Rp {{ number_format($monthlySales, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h2>Belum ada penjualan bulan ini</h2>
                            <p class="lead">
                                Data akan muncul setelah ada transaksi yang selesai di bulan {{ \Carbon\Carbon::now()->format('F Y') }}.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Most Popular Products -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Produk Terlaris (All Time)</h4>
                </div>
                <div class="card-body">
                    @if($popularProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th>Nama Produk</th>
                                        <th class="text-right">Total Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($popularProducts as $index => $item)
                                    <tr>
                                        <td class="product-rank">{{ $index + 1 }}</td>
                                        <td class="product-name">{{ $item->product->name }}</td>
                                        <td class="product-value text-right">{{ $item->total_sold }} unit</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-question"></i>
                            </div>
                            <h2>Belum ada data penjualan</h2>
                            <p class="lead">
                                Data akan muncul setelah ada transaksi penjualan.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Most Viewed Products -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Produk Banyak Dilihat</h4>
                </div>
                <div class="card-body">
                    @if($mostViewedProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th>Nama Produk</th>
                                        <th class="text-right">Jumlah Dilihat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mostViewedProducts as $index => $product)
                                    <tr>
                                        <td class="product-rank">{{ $index + 1 }}</td>
                                        <td class="product-name">{{ $product->name }}</td>
                                        <td class="product-value text-right">{{ $product->views }} views</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-question"></i>
                            </div>
                            <h2>Belum ada data produk dilihat</h2>
                            <p class="lead">
                                Data akan muncul setelah produk dilihat oleh pengguna.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
