@extends('layouts.landing')

@section('title', 'Katalog Produk')

@push('css')
@if(request()->query('layout') === 'landing')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endif
<style>
    /* Main container styling */
    .catalog-container {
        background-color: #f8fafb;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    /* Product card styling */
    .product-card {
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        background: white;
    }

    .product-card:hover {
        transform: translateY(-12px) scale(1.01);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        z-index: 5;
    }

    .product-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        box-shadow: 0 0 0 2px transparent;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .product-card:hover::after {
        box-shadow: 0 0 0 2px #6777ef;
    }

    .product-img-container {
        position: relative;
        padding-top: 75%;
        overflow: hidden;
        background: #f9f9f9;
        min-height: 200px;
        border: 1px solid #eee;
    }

    .product-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        transition: transform 0.7s ease;
        z-index: 1;
    }

    .product-img::after {
        content: "Gambar Error";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
        color: #e74c3c;
        font-weight: bold;
        z-index: 0;
    }

    .product-card:hover .product-img {
        transform: scale(1.05);
    }

    .product-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: white;
        position: relative;
    }

    .product-category {
        font-size: 12px;
        text-transform: uppercase;
        color: #6777ef;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .product-title {
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 18px;
        color: #333;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 44px;
        line-height: 1.4;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-title {
        color: #6777ef;
    }

    .product-price {
        font-weight: 800;
        color: #6777ef;
        font-size: 20px;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .product-price::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #6777ef, #3abaf4);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .product-card:hover .product-price::after {
        width: 100%;
    }

    .product-description {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 25px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
        line-height: 1.6;
        letter-spacing: 0.2px;
    }

    .btn-add-cart {
        width: 100%;
        margin-top: auto;
    }

    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        letter-spacing: 0.5px;
        backdrop-filter: blur(5px);
    }

    .search-container {
        margin-bottom: 30px;
        border-radius: 16px;
        overflow: hidden;
    }

    .quantity-input {
        width: 80px;
        border-radius: 10px;
        text-align: center;
        font-weight: 600;
        border: 2px solid #e4e6fc;
        transition: all 0.3s ease;
    }

    .quantity-input:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.2);
    }

    .product-actions {
        display: flex;
        align-items: center;
        margin-top: 15px;
        gap: 12px;
    }

    .product-actions button {
        flex-grow: 1;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 6px 15px rgba(103, 119, 239, 0.2);
        letter-spacing: 0.3px;
        border: none;
        background: linear-gradient(135deg, #6777ef, #3abaf4);
    }

    .product-actions button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.3);
        background: linear-gradient(135deg, #5668e2, #2aa7e1);
    }

    .product-actions button i {
        margin-right: 8px;
        transition: transform 0.5s ease;
    }

    .product-actions button:hover i {
        transform: translateX(3px) rotate(5deg);
    }

    /* Sorting options styling */
    .sort-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .sort-container:hover {
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06);
    }

    .sort-options {
        display: flex;
        align-items: center;
        background: white;
        padding: 15px 20px;
        border-radius: 16px;
    }

    .sort-options label {
        margin-bottom: 0;
        margin-right: 12px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
    }

    .sort-options label i {
        margin-right: 8px;
        color: #6777ef;
    }

    .sort-options select {
        border-radius: 10px;
        border: 2px solid #e4e6fc;
        box-shadow: none;
        font-weight: 500;
        padding: 10px 15px;
        color: #555;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .sort-options select:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.2);
    }

    /* Enhanced header styling */
    .enhanced-section-header {
        background: white;
        margin-bottom: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        position: relative;
    }

    .enhanced-section-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, #6777ef, #3abaf4);
    }

    .enhanced-header-content {
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .enhanced-header-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
        position: relative;
        padding-left: 15px;
        display: flex;
        align-items: center;
    }

    .enhanced-header-title i {
        margin-right: 10px;
        font-size: 26px;
        background: linear-gradient(135deg, #6777ef, #3abaf4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .enhanced-breadcrumb {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #6c757d;
    }

    .enhanced-breadcrumb-item {
        display: flex;
        align-items: center;
    }

    .enhanced-breadcrumb-item a {
        color: #6777ef;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .enhanced-breadcrumb-item a:hover {
        color: #3abaf4;
        transform: translateY(-1px);
    }

    .enhanced-breadcrumb-separator {
        margin: 0 10px;
        color: #ccc;
    }

    .enhanced-breadcrumb-item.active {
        font-weight: 600;
        color: #343a40;
    }

    /* Back to home styling */
    .back-to-home-container {
        margin-bottom: 20px;
    }

    .back-to-home {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        background: white;
        color: #6777ef;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
        border: 2px solid #6777ef;
    }

    .back-to-home i {
        margin-right: 8px;
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .back-to-home:hover {
        background: #6777ef;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(103, 119, 239, 0.2);
    }

    .back-to-home:hover i {
        transform: translateX(-4px);
    }

    /* Header styling */
    .catalog-header {
        background: linear-gradient(135deg, #6777ef, #3abaf4);
        padding: 40px;
        border-radius: 16px;
        margin-bottom: 40px;
        color: white;
        box-shadow: 0 15px 30px rgba(103, 119, 239, 0.2);
        position: relative;
        overflow: hidden;
    }

    .catalog-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .catalog-header h2 {
        font-weight: 800;
        margin-bottom: 15px;
        font-size: 32px;
        position: relative;
        display: inline-block;
    }

    .catalog-header h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 80px;
        height: 4px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 4px;
    }

    .catalog-header p {
        opacity: 0.9;
        font-size: 16px;
        max-width: 600px;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .catalog-search-form {
        background: white;
        border-radius: 12px;
        padding: 8px;
        display: flex;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .catalog-search-form input {
        border: none;
        padding: 12px 20px;
        flex-grow: 1;
        border-radius: 8px;
        font-size: 15px;
    }

    .catalog-search-form input:focus {
        box-shadow: none;
    }

    .catalog-search-form button {
        border-radius: 8px;
        padding: 12px 25px;
        margin-left: 5px;
        font-weight: 600;
        background: linear-gradient(135deg, #6777ef, #3abaf4);
        border: none;
        transition: all 0.3s ease;
    }

    .catalog-search-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(103, 119, 239, 0.3);
        background: linear-gradient(135deg, #5668e2, #2aa7e1);
    }

    /* Cart summary box styling */
    .cart-summary-box {
        background: linear-gradient(135deg, #ff9f43, #ff6b6b);
        color: white;
        border-radius: 16px;
        box-shadow: 0 15px 30px rgba(255, 107, 107, 0.2);
        transition: all 0.4s ease;
        overflow: hidden;
        position: relative;
        border: none;
    }

    .cart-summary-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(-30deg);
        pointer-events: none;
    }

    .cart-summary-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(255, 107, 107, 0.3);
    }

    .cart-summary-box .card-body {
        padding: 25px;
        position: relative;
        z-index: 1;
    }

    .cart-summary-box h5 {
        font-weight: 700;
        font-size: 22px;
        margin-bottom: 8px;
    }

    .cart-summary-box p {
        opacity: 0.9;
        font-size: 15px;
        margin-bottom: 0;
    }

    .cart-summary-box .btn {
        background: white;
        color: #ff6b6b;
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .cart-summary-box .btn:hover {
        transform: translateX(5px);
        background: #f8f9fa;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .cart-summary-box .btn i {
        transition: transform 0.3s ease;
        margin-right: 6px;
    }

    .cart-summary-box .btn:hover i {
        transform: translateX(3px);
    }

    /* Pagination styling */
    .pagination {
        margin-top: 20px;
    }

    .pagination .page-item .page-link {
        border-radius: 10px;
        margin: 0 3px;
        color: #6777ef;
        font-weight: 600;
        padding: 10px 18px;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #6777ef, #3abaf4);
        border-color: #6777ef;
    }

    .pagination .page-item .page-link:hover {
        background: #e4e6fc;
        color: #6777ef;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(103, 119, 239, 0.2);
    }

    /* Empty state styling */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 60px 30px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: #f3f4ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
    }

    .empty-state-icon i {
        font-size: 40px;
        color: #6777ef;
    }

    .empty-state h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 16px;
        max-width: 500px;
        margin: 0 auto 25px;
    }

    .empty-state .btn {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 12px;
        box-shadow: 0 8px 15px rgba(103, 119, 239, 0.2);
        transition: all 0.3s ease;
    }

    .empty-state .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(103, 119, 239, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .product-title {
            min-height: auto;
        }

        .catalog-header {
            padding: 25px;
        }

        .catalog-header h2 {
            font-size: 26px;
        }

        .enhanced-header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .enhanced-breadcrumb {
            margin-top: 15px;
        }

        .product-actions {
            flex-direction: column;
        }

        .product-actions input {
            width: 100%;
            margin-bottom: 10px;
        }

        .product-actions button {
            width: 100%;
        }

        .sort-options {
            flex-direction: column;
            align-items: flex-start;
        }

        .sort-options label {
            margin-bottom: 10px;
        }

        .sort-options select {
            width: 100%;
            margin-bottom: 10px;
        }

        .catalog-container {
            padding: 20px;
        }

        .cart-summary-box .d-flex {
            flex-direction: column;
        }

        .cart-summary-box .btn {
            margin-top: 15px;
            width: 100%;
        }
    }

    /* Animation keyframes */
    @keyframes pulse {
        0% {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        50% {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        100% {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
    }

    /* Dashboard button styles */
    .btn-back-to-dashboard {
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-back-to-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Homepage button styles */
    .btn-to-homepage {
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-to-homepage:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Navigation buttons container */
    .navigation-buttons {
        display: flex;
        align-items: center;
    }

    @media (max-width: 576px) {
        .navigation-buttons {
            flex-direction: column;
        }

        .navigation-buttons .btn {
            margin-right: 0 !important;
            margin-bottom: 8px;
            width: 100%;
        }
    }

    .product-img:hover {
        transform: scale(1.05);
    }

    /* Product detail link styling */
    .product-detail-link {
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .product-detail-link::after {
        content: 'Lihat Detail';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(103, 119, 239, 0.7);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .product-detail-link:hover::after {
        opacity: 1;
    }

    .product-name-link {
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .product-name-link:hover {
        color: #6777ef;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="section">
    <div class="enhanced-section-header">
        <div class="enhanced-header-content">
            <h1 class="enhanced-header-title">
                <i class="fas fa-store"></i> Katalog Produk
            </h1>
            <div class="enhanced-breadcrumb">
                @if(request()->query('layout') === 'landing')
                <a href="/" class="back-to-home mr-3">
                    <i class='bx bx-arrow-back'></i> Kembali ke Beranda
                </a>
                @else
                <div class="navigation-buttons">
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-back-to-dashboard mr-2">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    @else
                    <a href="{{ route('orders.index') }}" class="btn btn-primary btn-back-to-dashboard mr-2">
                        <i class="fas fa-shopping-bag mr-1"></i> Pesanan
                    </a>
                    @endif
                    <a href="/" class="btn btn-info btn-to-homepage">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="catalog-container">
            <div class="catalog-header">
                <h2>Temukan Produk Favorit Anda</h2>
                <p>Jelajahi berbagai produk unggulan kami dan temukan yang sesuai dengan kebutuhan Anda. Pilih dari koleksi berkualitas kami dengan harga terbaik.</p>

                <form action="{{ route('products.catalog') }}" method="GET" class="catalog-search-form">
                    <input type="text" class="form-control" name="search" placeholder="Cari produk yang Anda inginkan..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                </form>
            </div>

            <div class="sort-container">
                <form action="{{ route('products.catalog') }}" method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @if(request()->query('layout'))
                    <input type="hidden" name="layout" value="{{ request()->query('layout') }}">
                    @endif
                    <div class="sort-options">
                        <label for="sort"><i class="fas fa-sort"></i> Urutkan berdasarkan:</label>
                        <select class="form-control" name="sort_by" id="sort" onchange="this.form.submit()">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Harga</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                        </select>
                        <select class="form-control ml-2" name="sort_order" id="sort_order" onchange="this.form.submit()">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Menurun</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Menaik</option>
                        </select>
                    </div>
                </form>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($products->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2>Produk Tidak Ditemukan</h2>
                    <p class="lead">
                        Maaf, kami tidak dapat menemukan produk yang Anda cari. Silakan coba kata kunci lain atau lihat semua produk kami.
                    </p>
                    <a href="{{ route('products.catalog', request()->query('layout') ? ['layout' => request()->query('layout')] : []) }}" class="btn btn-primary">
                        <i class="fas fa-sync-alt mr-2"></i> Lihat Semua Produk
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card product-card h-100">
                                @if($product->track_inventory && $product->stock_quantity <= 0)
                                    <div class="stock-badge badge badge-danger">
                                        <i class="fas fa-times-circle mr-1"></i> Stok Habis
                                    </div>
                                @endif

                                <div class="product-img-container">
                                    <a href="{{ route('products.detail', $product) }}" class="product-detail-link">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-img"
                                                 onerror="this.onerror=null; this.src='{{ asset('img/no-image.png') }}'">
                                        @else
                                            <img src="{{ asset('img/no-image.png') }}"
                                                 alt="No Image"
                                                 class="product-img">
                                        @endif
                                    </a>
                                </div>

                                <div class="product-body">
                                    <div class="product-category">Produk Unggulan</div>
                                    <h5 class="product-title">
                                        <a href="{{ route('products.detail', $product) }}" class="product-name-link">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                                    <div class="product-description">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100) }}
                                    </div>

                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        @if(request()->query('layout') === 'landing')
                                            <input type="hidden" name="redirect_to" value="{{ route('cart.index', ['view' => 'landing']) }}">
                                        @endif

                                        <div class="product-actions">
                                            <input type="number" name="quantity" value="1" min="1"
                                                class="form-control quantity-input"
                                                {{ ($product->track_inventory && $product->stock_quantity <= 0) ? 'disabled' : '' }}>

                                            <button type="submit" class="btn btn-primary flex-grow-1"
                                                {{ ($product->track_inventory && $product->stock_quantity <= 0) ? 'disabled' : '' }}>
                                                <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                            </button>
                                        </div>

                                        @if($product->track_inventory && $product->stock_quantity > 0)
                                            <small class="text-muted mt-2 d-block">
                                                <i class="fas fa-cubes mr-1"></i> Stok tersedia: {{ $product->stock_quantity }}
                                            </small>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif

            <div class="row mt-5">
                <div class="col-12">
                    <div class="card cart-summary-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5><i class="fas fa-shopping-cart mr-2"></i>Keranjang Belanja Anda</h5>
                                    <p>Periksa keranjang Anda dan lanjutkan ke pembayaran untuk menyelesaikan pesanan Anda</p>
                                </div>
                                <a href="{{ request()->query('layout') === 'landing' ? route('cart.index', ['view' => 'landing']) : route('cart.index') }}" class="btn">
                                    <i class="fas fa-arrow-right"></i> Lihat Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug gambar produk
    const productImages = document.querySelectorAll('.product-img');
    console.log('Jumlah gambar produk:', productImages.length);

    productImages.forEach((img, index) => {
        console.log(`Gambar #${index + 1}:`, {
            src: img.src,
            width: img.width,
            height: img.height,
            visible: img.offsetParent !== null,
            complete: img.complete
        });

        // Tambahkan event untuk mendeteksi error loading
        img.addEventListener('error', function() {
            console.error(`Error loading image #${index + 1}:`, img.src);
        });
    });
});
</script>
@endpush
