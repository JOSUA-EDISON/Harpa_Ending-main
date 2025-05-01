@extends('layouts.app')

@section('title', $product->name)

@push('css')
    <style>
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.5s;
        }

        .product-detail-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            margin-bottom: 40px;
            overflow: hidden;
        }

        .product-image-container {
            padding: 30px;
            text-align: center;
            background: #f8f9fc;
            border-radius: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.5s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            border-left: 5px solid #6777ef;
            padding-left: 15px;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #6777ef;
            margin-bottom: 20px;
            background: rgba(103, 119, 239, 0.1);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
        }

        .product-description {
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.8;
            color: #6c757d;
        }

        .product-stock {
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .stock-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }

        .stock-badge.available {
            background-color: #47c363;
            color: white;
        }

        .stock-badge.limited {
            background-color: #ffa426;
            color: white;
        }

        .stock-badge.out {
            background-color: #fc544b;
            color: white;
        }

        .product-actions {
            margin-top: 30px;
        }

        .product-actions .form-control {
            border-radius: 10px;
            height: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
            border: 2px solid #e4e6fc;
        }

        .product-actions .btn {
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 15px rgba(103, 119, 239, 0.2);
            transition: all 0.3s ease;
        }

        .product-actions .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(103, 119, 239, 0.3);
        }

        .product-actions .btn i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .product-actions .btn:hover i {
            transform: translateX(3px);
        }

        .back-button {
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
            margin-bottom: 30px;
        }

        .back-button i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .back-button:hover {
            background: #6777ef;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(103, 119, 239, 0.2);
            text-decoration: none;
        }

        .back-button:hover i {
            transform: translateX(-3px);
        }

        .section-header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
        }

        .section-header h1 {
            font-size: 1.8rem;
            color: #34395e;
            position: relative;
            padding-left: 15px;
        }

        .section-header h1::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 25px;
            border-radius: 5px;
            background: linear-gradient(to bottom, #6777ef, #3abaf4);
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Produk</h1>
        </div>

        <div class="section-body">
            <a href="{{ route('products.catalog') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
            </a>

            <div class="row">
                <div class="col-md-12">
                    <div class="product-detail-container">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="product-image-container">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="product-image">
                                    @else
                                        <img src="{{ asset('img/no-image.png') }}" alt="No Image" class="product-image">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="p-4">
                                    <h1 class="product-title">{{ $product->name }}</h1>
                                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                                    <div class="product-stock">
                                        Stok:
                                        @if (!$product->track_inventory)
                                            <span class="stock-badge available">Tersedia</span>
                                        @elseif($product->stock_quantity > 10)
                                            <span class="stock-badge available">Tersedia
                                                ({{ $product->stock_quantity }})</span>
                                        @elseif($product->stock_quantity > 0)
                                            <span class="stock-badge limited">Stok Terbatas
                                                ({{ $product->stock_quantity }})</span>
                                        @else
                                            <span class="stock-badge out">Habis</span>
                                        @endif
                                    </div>

                                    <div class="product-description">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>

                                    <div class="product-actions">
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="number" name="quantity" value="1" min="1"
                                                        class="form-control"
                                                        {{ $product->track_inventory && $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                </div>
                                                <div class="col-md-9">
                                                    <button type="submit" class="btn btn-primary btn-block"
                                                        {{ $product->track_inventory && $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-cart-plus"></i> Tambahkan ke Keranjang
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
