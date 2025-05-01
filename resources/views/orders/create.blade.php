@extends('layouts.app')

@section('title', 'Buat Pesanan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/orders/orders.css') }}">
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Buat Pesanan</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Detail Pengiriman</h4>

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

                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="form-group">
                                    <label for="quantity">Jumlah</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" max="{{ $product->track_inventory ? $product->stock_quantity : 100 }}" required>

                                    @error('quantity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @if ($product->track_inventory)
                                        <small class="form-text text-muted">
                                            Stok tersedia: {{ $product->stock_quantity }}
                                        </small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="shipping_address">Alamat Pengiriman</label>
                                    <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address') }}</textarea>

                                    @error('shipping_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Nomor Telepon</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>

                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Catatan (Opsional)</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>

                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">Buat Pesanan</button>
                                    <a href="/" class="btn btn-secondary ml-2">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="card order-card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Detail Produk</h4>
                            <div class="product-summary mt-4">
                                <div class="product-image mb-3">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                </div>

                                <h5 class="product-title">{{ $product->name }}</h5>
                                <p class="product-price text-primary font-weight-bold">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>

                                <div class="product-stock mb-3">
                                    <span class="badge {{ $product->inStock() ? 'badge-success' : 'badge-danger' }}">
                                        {{ $product->stock_status }}
                                    </span>
                                </div>

                                <div class="product-description">
                                    <h6>Deskripsi:</h6>
                                    <div class="product-description-text">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="order-detail-section">
                            <h4>Ringkasan Pesanan</h4>
                            <div class="order-totals mt-4">
                                <div class="row">
                                    <div class="col-8">Harga Produk</div>
                                    <div class="col-4 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-8">Jumlah</div>
                                    <div class="col-4 text-right" id="qty-display">1</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-8 font-weight-bold">Total</div>
                                    <div class="col-4 text-right font-weight-bold" id="total-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                </div>
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
        const qtyInput = document.getElementById('quantity');
        const qtyDisplay = document.getElementById('qty-display');
        const totalPrice = document.getElementById('total-price');
        const unitPrice = {{ $product->price }};

        qtyInput.addEventListener('change', updatePrice);
        qtyInput.addEventListener('keyup', updatePrice);

        function updatePrice() {
            const qty = parseInt(qtyInput.value) || 1;
            qtyDisplay.textContent = qty;

            const total = unitPrice * qty;
            totalPrice.textContent = 'Rp ' + numberFormat(total);
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    });
</script>
@endpush
