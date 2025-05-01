<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Alamat Pengiriman</h5>
    </div>
    <div class="card-body">
        <!-- Location Selection Display -->
        <div class="mb-3">
            <label class="form-label">Lokasi Pengiriman</label>
            <div class="d-flex align-items-center">
                <div class="location-display">
                    @if(isset($cityName) && isset($provinceName))
                        <span class="selected-location-display">{{ $cityName }}, {{ $provinceName }}</span>
                    @else
                        <span class="selected-location-display text-muted">Pilih lokasi pengiriman</span>
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm ml-3" data-toggle="modal" data-target="#locationSelectorModal">
                    <i class="fas fa-map-marker-alt mr-1"></i> Ubah Lokasi
                </button>
            </div>
        </div>

        <!-- Shipping Options Display -->
        <div class="shipping-options mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Opsi Pengiriman</label>
                <div id="shipping-loading" class="d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span class="ml-2 small">Menghitung biaya pengiriman...</span>
                </div>
            </div>

            <!-- Shipping service details will be displayed here -->
            <div id="shipping-service-details" class="mt-2 mb-3">
                @if(isset($shippingCost) && $shippingCost > 0)
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2" id="shipping-service-name">{{ $shippingService ?? 'Layanan Pengiriman' }}</h6>
                            <div class="d-flex justify-content-between">
                                <span>Biaya Pengiriman</span>
                                <span class="font-weight-bold" id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Estimasi</span>
                                <span id="shipping-etd">{{ $shippingEtd ?? '1-3 hari' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info" id="shipping-info-message">
                        <i class="fas fa-info-circle mr-1"></i> Pilih lokasi pengiriman untuk melihat opsi pengiriman yang tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('cart.province-city-selector')
