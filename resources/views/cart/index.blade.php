@extends('layouts.landing')

@section('title', 'Keranjang Belanja')

@push('css')
<link rel="stylesheet" href="{{ asset('css/orders/orders.css') }}">
<link rel="stylesheet" href="{{ asset('css/cart/cart-styles.css') }}">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
    .checkout-section {
        padding: 50px 0;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .back-to-cart {
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        color: #6777ef;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-to-cart:hover {
        color: #3d4eda;
        text-decoration: none;
    }

    .back-to-cart i {
        margin-right: 5px;
        font-size: 20px;
    }

    .checkout-hero {
        background: linear-gradient(135deg, #6777ef, #3d4eda);
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 5px 20px rgba(103, 119, 239, 0.2);
    }

    .checkout-hero h1 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .checkout-hero p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    /* Steps */
    .checkout-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .checkout-steps::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 10%;
        right: 10%;
        height: 3px;
        background-color: #e4e6fc;
        z-index: 1;
    }

    .step {
        text-align: center;
        z-index: 2;
        flex: 1;
    }

    .step-icon {
        width: 80px;
        height: 80px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border: 3px solid #e4e6fc;
        transition: all 0.3s ease;
        position: relative;
    }

    .step-icon i {
        font-size: 32px;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .step-label {
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .step.active .step-icon {
        background-color: #6777ef;
        border-color: #6777ef;
        box-shadow: 0 0 0 5px rgba(103, 119, 239, 0.2);
    }

    .step.active .step-icon i {
        color: white;
    }

    .step.active .step-label {
        color: #6777ef;
    }

    .step.current .step-icon {
        transform: scale(1.1);
    }

    /* Checkout content */
    .checkout-content {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
        transition: opacity 0.3s ease;
    }

    .checkout-form {
        flex: 1;
        min-width: 60%;
        padding: 0 15px;
        margin-bottom: 30px;
    }

    .checkout-summary {
        width: 35%;
        padding: 0 15px;
    }

    .checkout-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .checkout-card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        font-weight: 600;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .checkout-card-header i {
        margin-right: 10px;
        color: #6777ef;
        font-size: 18px;
    }

    .checkout-card-body {
        padding: 20px;
    }

    .checkout-item {
        display: flex;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .checkout-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .checkout-item-image {
        width: 80px;
        height: 80px;
        overflow: hidden;
        border-radius: 8px;
        margin-right: 15px;
    }

    .checkout-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .checkout-item-details {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .checkout-item-name {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .checkout-item-price {
        color: #6777ef;
        font-weight: 600;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        padding: 5px;
        border: 1px solid #e4e6fc;
        border-radius: 5px;
        margin-right: 10px;
    }

    .btn-update {
        background-color: #6777ef;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        margin-right: 10px;
    }

    .btn-update:hover {
        background-color: #5a68d5;
    }

    .btn-remove {
        color: #fc544b;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        color: #e03e36;
    }

    .cart-empty {
        text-align: center;
        padding: 30px 20px;
    }

    .cart-empty i {
        font-size: 50px;
        color: #e4e6fc;
        margin-bottom: 15px;
    }

    .cart-empty h3 {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .cart-empty p {
        color: #6c757d;
        margin-bottom: 20px;
    }

    .btn-continue {
        display: inline-flex;
        align-items: center;
        background-color: #6777ef;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-continue:hover {
        background-color: #5a68d5;
        transform: translateY(-2px);
        text-decoration: none;
        color: white;
    }

    .order-count {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .order-count .badge {
        background-color: #6777ef;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        margin-right: 10px;
    }

    .checkout-summary-totals {
        margin-bottom: 20px;
    }

    .checkout-total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }

    .checkout-total-row:last-child {
        border-bottom: none;
    }

    .checkout-total-row.grand-total {
        font-weight: 700;
        font-size: 18px;
        color: #6777ef;
        border-top: 2px solid #f0f0f0;
        border-bottom: none;
        margin-top: 10px;
        padding-top: 10px;
    }

    .btn-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: linear-gradient(to right, #f7972d, #fb6340);
        color: white;
        padding: 12px 15px;
        border-radius: 30px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-order:hover {
        background: linear-gradient(to right, #fb6340, #f7972d);
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(251, 99, 64, 0.25);
    }

    .btn-order.pulse-animation {
        animation: pulse 2s infinite;
        box-shadow: 0 0 0 rgba(103, 119, 239, 0.4);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(103, 119, 239, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(103, 119, 239, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(103, 119, 239, 0);
        }
    }

    .btn-back {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: transparent;
        border: 1px solid #6777ef;
        color: #6777ef;
        padding: 10px 15px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .btn-back:hover {
        background: rgba(103, 119, 239, 0.1);
        text-decoration: none;
        color: #6777ef;
    }

    .checkout-promo {
        background: #fff8e1;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .promo-tag {
        display: inline-flex;
        align-items: center;
        background: #ffab00;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .promo-tag i {
        margin-right: 5px;
    }

    .promo-message p {
        margin: 0;
        font-size: 14px;
        color: #795548;
    }

    .secure-badges {
        display: flex;
        flex-direction: column;
    }

    .secure-badge {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .secure-badge:last-child {
        margin-bottom: 0;
    }

    .secure-badge i {
        font-size: 20px;
        color: #6777ef;
        margin-right: 10px;
    }

    /* Responsive styles */
    @media (max-width: 991px) {
        .checkout-content {
            flex-direction: column;
        }

        .checkout-form,
        .checkout-summary {
            width: 100%;
        }

        .step-icon {
            width: 60px;
            height: 60px;
        }

        .step-icon i {
            font-size: 24px;
        }
    }

    @media (max-width: 576px) {
        .checkout-steps {
            flex-wrap: wrap;
        }

        .step {
            width: 50%;
            margin-bottom: 20px;
        }

        .checkout-hero h1 {
            font-size: 22px;
        }

        .checkout-hero p {
            font-size: 14px;
        }
    }

    .fade-out {
        opacity: 0;
    }

    .step.pre-active {
        transform: scale(1.05);
        color: #6777ef;
    }
</style>
@endpush

@section('content')
<div class="checkout-section">
    <div class="checkout-container">
        <a href="{{ route('welcome') }}" class="back-to-cart">
            <i class='bx bx-arrow-back'></i> Kembali ke Beranda
        </a>

        <div class="checkout-hero">
            <div class="checkout-hero-content">
                <h1>Keranjang Belanja</h1>
                <p>Periksa keranjang Anda dan lanjutkan pembelian untuk mendapatkan produk pilihan Anda</p>
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

        <div class="checkout-steps">
            <div class="step active current">
                <div class="step-icon">
                    <i class='bx bx-cart'></i>
                </div>
                <div class="step-label">Keranjang</div>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class='bx bx-map-alt'></i>
                </div>
                <div class="step-label">Pengiriman</div>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class='bx bx-credit-card'></i>
                </div>
                <div class="step-label">Pembayaran</div>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class='bx bx-check-circle'></i>
                </div>
                <div class="step-label">Selesai</div>
            </div>
        </div>

        <div class="checkout-content">
            <div class="checkout-form">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <i class='bx bx-cart'></i> Item dalam Keranjang
                    </div>
                    <div class="checkout-card-body">
                        @if(count($items) > 0)
                            @foreach($items as $item)
                                <div class="checkout-item">
                                    <div class="checkout-item-image">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}">
                                        @else
                                            <img src="{{ asset('img/no-image.png') }}" alt="No Image">
                                        @endif
                                    </div>
                                    <div class="checkout-item-details">
                                        <div>
                                            <h4 class="checkout-item-name">{{ $item->product_name }}</h4>
                                            <div class="checkout-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="cart-item-actions">
                                            <form action="{{ route('cart.update-item', $item->id) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="quantity-input">
                                                <button type="submit" class="btn-update">
                                                    <i class='bx bx-refresh mr-1'></i> Perbarui
                                                </button>
                                            </form>
                                            <form action="{{ route('cart.remove-item', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-remove" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="cart-empty">
                                <i class='bx bx-cart'></i>
                                <h3>Keranjang Anda Kosong</h3>
                                <p>Anda belum menambahkan produk ke keranjang.</p>
                                <a href="{{ route('products.catalog') }}" class="btn-continue">
                                    <i class='bx bx-shopping-bag mr-2'></i> Mulai Belanja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="checkout-card secure-badge-container">
                    <div class="secure-badges">
                        <div class="secure-badge">
                            <i class='bx bx-lock-alt'></i>
                            <span>Pembayaran Aman</span>
                        </div>
                        <div class="secure-badge">
                            <i class='bx bx-package'></i>
                            <span>Pengiriman Terpercaya</span>
                        </div>
                        <div class="secure-badge">
                            <i class='bx bx-check-shield'></i>
                            <span>Garansi Kualitas</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-summary">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <i class='bx bx-receipt'></i> Ringkasan Belanja
                    </div>
                    <div class="checkout-card-body">
                        @if(count($items) > 0)
                            <div class="order-count">
                                <span class="badge">{{ $totalItems }} item</span> dalam keranjang Anda
                            </div>

                            <div class="checkout-summary-totals">
                                <div class="checkout-total-row">
                                    <span>Jumlah Item</span>
                                    <span>{{ $totalItems }}</span>
                                </div>
                                <div class="checkout-total-row">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="checkout-total-row grand-total">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('cart.checkout') }}" class="btn-order pulse-animation">
                                <i class='bx bx-check-circle mr-2'></i> Lanjut ke Pengiriman <i class='bx bx-right-arrow-alt ml-2'></i>
                            </a>

                            <a href="{{ route('welcome') }}" class="btn-back mt-3">
                                <i class='bx bx-arrow-back mr-2'></i> Lanjutkan Belanja
                            </a>

                            <div class="checkout-promo mt-4">
                                <div class="promo-tag">
                                    <i class='bx bxs-discount'></i> PROMO
                                </div>
                                <div class="promo-message">
                                    <p>Dapatkan pengiriman gratis untuk pembelian pertama Anda!</p>
                                </div>
                            </div>
                        @else
                            <div class="checkout-total-row grand-total">
                                <span>Total</span>
                                <span>Rp 0</span>
                            </div>
                            <a href="{{ route('products.catalog') }}" class="btn-continue">
                                <i class='bx bx-shopping-bag mr-2'></i> Mulai Belanja
                            </a>
                        @endif
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
        // Add smooth transition when navigating to checkout
        const checkoutButton = document.querySelector('.btn-order');
        if (checkoutButton) {
            checkoutButton.addEventListener('click', function(e) {
                e.preventDefault();

                console.log('Checkout button clicked');

                // Store the target URL for later use
                const checkoutUrl = checkoutButton.getAttribute('href');
                console.log('Checkout URL:', checkoutUrl);

                // Add fade-out class to main content
                document.querySelector('.checkout-content').classList.add('fade-out');

                // Highlight the next step (Pengiriman) before navigating
                const steps = document.querySelectorAll('.step');
                if (steps.length > 1) {
                    steps[1].classList.add('pre-active');
                }

                // Navigate after a short delay
                setTimeout(function() {
                    console.log('Navigating to checkout page...');
                    window.location.href = checkoutUrl;
                }, 300);
            });
        } else {
            console.error('Checkout button not found on the page');
        }
    });
</script>
@endpush
