@extends('layouts.landing')

@section('title', 'Checkout - Harpa')

@push('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/landing/tes.css') }}">
<link rel="stylesheet" href="{{ asset('css/cart/cart-styles.css') }}">
<style>
        /* Checkout styles */
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

        /* Form elements for landing */
        .checkout-section .form-group {
            margin-bottom: 20px;
        }

        .checkout-section .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .checkout-section .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .checkout-section .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.2);
        }

        .checkout-section .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            font-size: 16px;
            background-color: white;
            transition: all 0.3s ease;
        }

        .checkout-section .form-select:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.2);
        }

        /* Order summary for landing */
        .checkout-items-list {
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .checkout-item {
            display: flex;
            align-items: center;
            padding-bottom: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .checkout-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .checkout-item-img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 15px;
        }

        .checkout-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .checkout-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .checkout-total-row.grand-total {
            font-weight: 700;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #f0f0f0;
        }

        /* Button styles for landing */
        .btn-order {
            background: linear-gradient(to right, #6777ef, #3d4eda);
            border: none;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
            cursor: pointer;
        transition: all 0.3s ease;
    }

        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 119, 239, 0.3);
        }

        .btn-order:disabled {
            background: #b4b9f7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-order i {
            margin-right: 8px;
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
    }
</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="checkout-section">
    <div class="checkout-container">
        <a href="{{ route('cart.index') }}" class="back-to-cart">
            <i class='bx bx-arrow-back'></i> Kembali ke Keranjang
        </a>

        <div class="checkout-hero">
            <div class="checkout-hero-content">
                <h1>Checkout</h1>
                <p>Lengkapi informasi pengiriman untuk melanjutkan ke pembayaran</p>
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
            <div class="step">
                <div class="step-icon">
                    <i class='bx bx-cart'></i>
                </div>
                <div class="step-label">Keranjang</div>
            </div>
            <div class="step active current">
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
                <form id="checkout-form" action="{{ route('orders.store-from-cart') }}" method="POST">
                    @csrf
                    <!-- Shipping Address -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                            <i class='bx bx-map'></i> Alamat Pengiriman
                    </div>
                    <div class="checkout-card-body">
                            <div class="form-group">
                                <label for="shipping_address" class="form-label">Alamat Lengkap</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="province_id" class="form-label">Provinsi</label>
                                    <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->province_id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="city_id" class="form-label">Kota/Kabupaten</label>
                                    <select name="city_id" id="city_id" class="form-control @error('city_id') is-invalid @enderror" required>
                                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                            </div>

                    <!-- Shipping Method -->
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <i class='bx bx-package'></i> Metode Pengiriman
                        </div>
                        <div class="checkout-card-body">
                            <div class="form-group">
                                <label for="courier" class="form-label">Kurir</label>
                                <select name="courier" id="courier" class="form-control @error('courier') is-invalid @enderror" required>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                    </select>
                                @error('courier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Shipping service details will be displayed here -->
                            <div id="shipping-services-container" class="mt-3">
                                <div class="alert alert-info">
                                    <i class='bx bx-info-circle mr-2'></i> Silakan pilih provinsi dan kota untuk melihat opsi pengiriman.
                                </div>
                            </div>

                            <!-- Hidden fields for shipping details -->
                            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                            <input type="hidden" name="shipping_service" id="shipping_service" value="">
                        </div>
                </div>

                    <!-- Notes -->
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <i class='bx bx-note'></i> Catatan Pesanan
                        </div>
                        <div class="checkout-card-body">
                            <div class="form-group">
                                <label for="notes" class="form-label">Catatan (opsional)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <i class='bx bx-receipt'></i> Ringkasan Pesanan
                    </div>
                    <div class="checkout-card-body">
                        <div class="checkout-items-list">
                            @foreach($cart->items as $item)
                                <div class="checkout-item">
                                    <div class="checkout-item-img">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="img-fluid" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="no-image-placeholder" style="width: 60px; height: 60px;"></div>
                                        @endif
                                    </div>
                                    <div class="checkout-item-details flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            <span class="font-weight-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                            <div class="checkout-total-row">
                            <span>Subtotal</span>
                            <span class="order-subtotal-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="checkout-total-row">
                            <span>Ongkos Kirim</span>
                                <span class="shipping-cost-value">Rp 0</span>
                            </div>
                        <hr>
                            <div class="checkout-total-row grand-total">
                            <span>Total</span>
                            <span class="order-total-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-4">
                            <button type="submit" form="checkout-form" class="btn btn-primary btn-lg btn-block btn-order" id="checkout-button" disabled>
                                Lanjutkan ke Pembayaran
                                    </button>
                    </div>
                </div>
            </div>

                <div class="checkout-card">
                    <div class="checkout-card-body">
                        <div class="secure-badges">
                            <div class="d-flex align-items-center mb-2">
                                <i class='bx bx-lock-alt text-success mr-2' style="font-size: 20px;"></i>
                                <span>Pembayaran Aman</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class='bx bx-package text-success mr-2' style="font-size: 20px;"></i>
                                <span>Pengiriman Terpercaya</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class='bx bx-check-shield text-success mr-2' style="font-size: 20px;"></i>
                                <span>Garansi Kualitas</span>
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
<script src="{{ asset('js/shipping.js') }}"></script>
<script>
    // Check if shipping.js is loaded
    function checkShippingJsLoaded() {
        if (typeof window.calculateShippingCost !== 'function' && typeof window.updateShippingCost !== 'function') {
            console.error('shipping.js is not loaded properly. Loading dynamically...');

            // Try to load it dynamically
            var script = document.createElement('script');
            script.src = '{{ asset('js/shipping.js') }}?v=' + new Date().getTime();
            script.onload = function() {
                console.log('shipping.js loaded dynamically successfully');
                // If city and province are already selected, trigger shipping calculation
                var provinceId = $('#province_id').val();
                var cityId = $('#city_id').val();
                var courier = $('#courier').val();

                if (provinceId && cityId && courier) {
                    if (typeof window.calculateShippingCost === 'function') {
                        window.calculateShippingCost(provinceId, cityId, courier);
                    } else if (typeof window.updateShippingCost === 'function') {
                        window.updateShippingCost();
                    }
                }
            };
            script.onerror = function() {
                console.error('Failed to load shipping.js dynamically');
                $('#shipping-services-container').html(`
                    <div class="alert alert-danger">
                        <i class='bx bx-error-circle mr-2'></i>
                        <span>Gagal memuat file perhitungan ongkos kirim (shipping.js)</span>
                    </div>
                `);
            };
            document.head.appendChild(script);
        } else {
            console.log('shipping.js is already loaded');
        }
    }

    // Document ready handler
    $(document).ready(function() {
        console.log('Checkout script initialized');

        // Check if shipping.js is properly loaded
        checkShippingJsLoaded();

        // Just in case, add another check after a slight delay
        setTimeout(checkShippingJsLoaded, 1000);

        // Remove any existing event listeners to prevent duplicates
        $('#province_id').off('change');

        // Province change handler
        $('#province_id').on('change', function() {
            const provinceId = $(this).val();

            if (provinceId) {
                getCities(provinceId, 'city_id');
            } else {
                $('#city_id').empty().append('<option value="">Pilih Provinsi Terlebih Dahulu</option>');
            }
        });

        // City change handler
        $('#city_id').change(function() {
            const provinceId = $('#province_id').val();
            const cityId = $(this).val();
            const courier = $('#courier').val();

            if (provinceId && cityId && courier) {
                // Check for both possible function names
                if (typeof window.calculateShippingCost === 'function') {
                    window.calculateShippingCost(provinceId, cityId, courier);
                } else if (typeof window.updateShippingCost === 'function') {
                    window.updateShippingCost();
                } else {
                    console.error('Shipping calculation function not available');
                    $('#shipping-services-container').html(`
                        <div class="alert alert-danger">
                            <i class='bx bx-error-circle mr-2'></i>
                            <span>Fungsi perhitungan ongkos kirim tidak tersedia. Pastikan file js/shipping.js telah dimuat.</span>
                        </div>
                    `);
                }
            }
        });

        // Courier change handler
        $('#courier').change(function() {
            const provinceId = $('#province_id').val();
            const cityId = $('#city_id').val();
            const courier = $(this).val();

            if (provinceId && cityId && courier) {
                // Check for both possible function names
                if (typeof window.calculateShippingCost === 'function') {
                    window.calculateShippingCost(provinceId, cityId, courier);
                } else if (typeof window.updateShippingCost === 'function') {
                    window.updateShippingCost();
                } else {
                    console.error('Shipping calculation function not available');
                    $('#shipping-services-container').html(`
                        <div class="alert alert-danger">
                            <i class='bx bx-error-circle mr-2'></i>
                            <span>Fungsi perhitungan ongkos kirim tidak tersedia. Pastikan file js/shipping.js telah dimuat.</span>
                        </div>
                    `);
                }
            }
        });

        // Function to get cities by province ID
        function getCities(provinceId, elementId) {
            // Try multiple endpoints in sequence
            tryCityEndpoint(`/api/shipping/cities/${provinceId}`, provinceId, elementId, function() {
                // First fallback: try shipping/cities
                tryCityEndpoint(`/shipping/cities/${provinceId}`, provinceId, elementId, function() {
                    // Second fallback: try test-cities
                    tryCityEndpoint(`/shipping/test-cities/${provinceId}`, provinceId, elementId, function() {
                        // All attempts failed
                        $(`#${elementId}`).empty().append('<option value="">Gagal memuat kota - semua endpoint gagal</option>');
                        console.error('All city endpoints failed for province ID:', provinceId);
                    });
                });
            });
        }

        // Try a specific endpoint to get cities
        function tryCityEndpoint(endpoint, provinceId, elementId, onFailure) {
            console.log(`Trying to fetch cities from: ${endpoint}`);
            $.ajax({
                url: endpoint,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $(`#${elementId}`).empty().append('<option value="">Loading...</option>');
                },
                success: function(response) {
                    console.log(`Response from ${endpoint}:`, response);
                    $(`#${elementId}`).empty().append('<option value="">Pilih Kota/Kabupaten</option>');

                    if ((response.status === 'success' || response.success === true) &&
                        response.data && response.data.length > 0) {
                        $.each(response.data, function(key, city) {
                            // Handle different city data formats
                            const cityId = city.city_id || city.id;
                            const cityName = city.name;
                            const cityType = (city.type === 'Kabupaten' || city.type === 'kabupaten') ? 'Kab. ' : 'Kota ';

                            $(`#${elementId}`).append(`<option value="${cityId}">${cityType}${cityName}</option>`);
                        });
                        console.log(`Successfully loaded ${response.data.length} cities from ${endpoint}`);
                    } else {
                        console.warn(`Endpoint ${endpoint} returned success but no usable data`);
                        $(`#${elementId}`).append('<option value="">Tidak ada kota tersedia untuk provinsi ini</option>');

                        // If no data, try the next endpoint
                        if (typeof onFailure === 'function') {
                            onFailure();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error(`Error fetching cities from ${endpoint}:`, status, error);

                    // Try the next endpoint
                    if (typeof onFailure === 'function') {
                        onFailure();
                    }
                }
            });
        }
    });
</script>
@endpush
