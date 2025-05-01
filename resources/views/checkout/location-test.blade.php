@extends('layouts.app')

@section('title', 'Location Selector Test')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Location Selector Test</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Test Location Selector</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mb-4">
                            This is a lightweight test page for the location selector. It uses the exact same component
                            as the checkout page but in a simplified environment to help identify issues.
                        </p>

                        <!-- Location Selector -->
                        <div class="form-group">
                            <label>Lokasi Pengiriman</label>
                            <div class="input-group mb-3">
                                <div class="form-control" id="selected-location-display">
                                    Pilih lokasi pengiriman
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="openLocationSelectorBtn">
                                        <i class="fas fa-map-marker-alt"></i> Pilih Lokasi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Location Display -->
                        <div class="form-group">
                            <label>Selected Location Data:</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Province ID:</strong> <span id="display-province-id">-</span></p>
                                            <p><strong>City ID:</strong> <span id="display-city-id">-</span></p>
                                            <p><strong>Courier:</strong> <span id="display-courier">-</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Province Name:</strong> <span id="display-province-name">-</span></p>
                                            <p><strong>City Name:</strong> <span id="display-city-name">-</span></p>
                                            <p><strong>Shipping Cost:</strong> <span id="display-shipping-cost">-</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Results -->
                        <div class="form-group">
                            <label>Shipping Calculation Results:</label>
                            <div id="shipping-loading" class="d-none">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Calculating shipping...</span>
                                </div>
                            </div>
                            <div id="shipping-service-details">
                                <div class="alert alert-info">
                                    Please select a location to calculate shipping
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Standard hidden inputs to store location data -->
<input type="hidden" id="province_id" name="province_id" value="">
<input type="hidden" id="city_id" name="city_id" value="">
<input type="hidden" id="province_name" name="province_name" value="">
<input type="hidden" id="city_name" name="city_name" value="">
<input type="hidden" id="courier" name="courier" value="jne">
<input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
<input type="hidden" id="shipping_service" name="shipping_service" value="">

<!-- Include the province-city selector component -->
@include('cart.province-city-selector')
@endsection

@push('js')
<!-- Add shipping JS -->
<script src="{{ asset('js/shipping.js') }}"></script>

<script>
$(document).ready(function() {
    console.log('Location test page loaded');

    // Manual binding to location selector button
    $('#openLocationSelectorBtn').on('click', function() {
        console.log('Location button clicked from location-test.blade.php');
        $('#locationSelectorModal').modal('show');
    });

    // Update display when hidden inputs are changed
    function updateDisplayFromInputs() {
        $('#display-province-id').text($('#province_id').val() || '-');
        $('#display-city-id').text($('#city_id').val() || '-');
        $('#display-province-name').text($('#province_name').val() || '-');
        $('#display-city-name').text($('#city_name').val() || '-');
        $('#display-courier').text($('#courier').val() || '-');
        $('#display-shipping-cost').text('Rp ' + ($('#shipping_cost').val() || '0'));
    }

    // Listen for changes to the hidden inputs
    $('input[type="hidden"]').on('change', updateDisplayFromInputs);

    // Check inputs every second for changes
    setInterval(updateDisplayFromInputs, 1000);
});
</script>

<!-- Direct script for debugging modal issues -->
<script>
// Direct manual binding without jQuery
window.addEventListener('load', function() {
    var btn = document.getElementById('openLocationSelectorBtn');
    var modal = document.getElementById('locationSelectorModal');

    if (btn && modal) {
        btn.addEventListener('click', function() {
            console.log('Native click handler triggered');
            // Try multiple approaches
            try {
                // jQuery way
                if (window.jQuery) {
                    jQuery('#locationSelectorModal').modal('show');
                }

                // Direct way
                setTimeout(function() {
                    if (!modal.classList.contains('show')) {
                        modal.classList.add('show');
                        modal.style.display = 'block';
                        document.body.classList.add('modal-open');

                        var backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }, 100);
            } catch (e) {
                console.error('Error showing modal:', e);
            }
        });
    } else {
        console.error('Button or modal not found!');
        if (!btn) console.error('Button missing');
        if (!modal) console.error('Modal missing');
    }
});
</script>

<!-- Include hotfix script -->
@include('cart.hotfix-modal')
@endpush
