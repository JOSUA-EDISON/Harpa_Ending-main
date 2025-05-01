@extends('layouts.app')

@section('title', 'Test Shipping')

@push('css')
<style>
    .shipping-option {
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .shipping-option:hover {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.1);
    }

    .shipping-option.selected {
        border-color: #28a745;
        background-color: #f8fff8;
    }

    .shipping-option .radio-container {
        display: flex;
        align-items: center;
    }

    .location-display {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.5rem;
        background-color: #f8f9fa;
        flex-grow: 1;
        margin-right: 10px;
    }

    .location-display.selected {
        border-color: #28a745;
        background-color: #f0fff4;
    }
</style>
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Test Shipping Integration</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Shipping Options</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">Alamat Pengiriman</h5>

                        <!-- Location Selector Test -->
                        <div class="mb-4">
                            <label class="form-label">Lokasi Pengiriman</label>
                            <div class="d-flex align-items-center">
                                <div id="selected-location-display" class="location-display text-muted">
                                    Pilih lokasi pengiriman
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="openLocationSelectorBtn">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Pilih Lokasi
                                </button>
                            </div>
                        </div>

                        <!-- Shipping Options Display -->
                        <div class="mt-4">
                            <h5 class="mb-3">Opsi Pengiriman</h5>
                            <div id="shipping-loading" class="d-none text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Menghitung biaya pengiriman...</p>
                            </div>
                            <div id="shipping-service-details">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-1"></i> Pilih lokasi pengiriman untuk melihat opsi pengiriman yang tersedia.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Ringkasan Pesanan</h4>
                            </div>
                            <div class="card-body">
                                <div class="checkout-summary-totals">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal Produk</span>
                                        <span class="order-subtotal-value">Rp 250.000</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Biaya Pengiriman</span>
                                        <span class="shipping-cost-value">Rp 0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total Pembayaran</span>
                                        <span class="order-total-value">Rp 250.000</span>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary btn-block mt-3" disabled>
                                    <i class="fas fa-credit-card mr-1"></i> Lanjut ke Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include province-city selector component -->
@include('cart.province-city-selector')
@endsection

@push('js')
<!-- Include pure JavaScript modal implementation -->
@include('cart.pure-js-modal')

<!-- Optional jQuery-based shipping logic -->
<script src="{{ asset('js/shipping.js') }}"></script>

<!-- Additional debug info -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Shipping test page loaded with pure JS modal');
    });
</script>
@endpush
