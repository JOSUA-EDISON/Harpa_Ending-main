<!-- Location Selector Modal -->
<div class="modal fade" id="locationSelectorModal" tabindex="-1" role="dialog" aria-labelledby="locationSelectorLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationSelectorLabel">Pilih Lokasi Pengiriman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Province Selector -->
                <div class="form-group">
                    <label for="provinceSelector">Provinsi</label>
                    <select class="form-control" id="provinceSelector">
                        <option value="">Pilih Provinsi</option>
                        <!-- Provinces will be loaded via AJAX -->
                    </select>
                    <div id="province-loading" class="text-center mt-2 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span class="ml-2">Memuat provinsi...</span>
                    </div>
                </div>

                <!-- City Selector -->
                <div class="form-group">
                    <label for="citySelector">Kota/Kabupaten</label>
                    <select class="form-control" id="citySelector" disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                        <!-- Cities will be loaded via AJAX -->
                    </select>
                    <div id="city-loading" class="text-center mt-2 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span class="ml-2">Memuat kota/kabupaten...</span>
                    </div>
                </div>

                <!-- Courier Selection -->
                <div class="form-group">
                    <label for="courierSelector">Kurir Pengiriman</label>
                    <select class="form-control" id="courierSelector" disabled>
                        <option value="jne">JNE</option>
                        <option value="pos">POS Indonesia</option>
                        <option value="tiki">TIKI</option>
                    </select>
                </div>

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle mr-1"></i> Silakan pilih provinsi dan kota/kabupaten untuk melihat opsi pengiriman.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmLocationBtn" disabled>Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input fields to store selected location -->
<input type="hidden" id="province_id" name="province_id" value="{{ old('province_id', $provinceId ?? '') }}">
<input type="hidden" id="city_id" name="city_id" value="{{ old('city_id', $cityId ?? '') }}">
<input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', $provinceName ?? '') }}">
<input type="hidden" id="city_name" name="city_name" value="{{ old('city_name', $cityName ?? '') }}">
<input type="hidden" id="courier" name="courier" value="{{ old('courier', 'jne') }}">
<input type="hidden" id="shipping_cost" name="shipping_cost" value="{{ old('shipping_cost', 0) }}">
<input type="hidden" id="shipping_service" name="shipping_service" value="{{ old('shipping_service', '') }}">

<!-- Hidden compatibility fields for old code -->
<input type="hidden" id="hidden_province" name="province" value="{{ old('province', '') }}">
<input type="hidden" id="hidden_destination" name="destination" value="{{ old('destination', '') }}">

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Province-city-selector: DOMContentLoaded event fired');
    initializeLocationSelector();
});

function initializeLocationSelector() {
    console.log('Initializing location selector...');

    // Connect open location button to modal
    var openLocationButton = document.getElementById('openLocationSelectorBtn');
    if (openLocationButton) {
        openLocationButton.addEventListener('click', function() {
            console.log('Open location button clicked');
            $('#locationSelectorModal').modal('show');
        });
        console.log('Location button click handler added');
    } else {
        console.warn('Open location button not found in the DOM');
    }

    // Ensure the modal loads provinces when shown
    $('#locationSelectorModal').on('shown.bs.modal', function() {
        console.log('Modal shown event fired');
        if ($('#provinceSelector option').length <= 1) {
            loadProvinces();
        }
    });

    // Province change handler
    $('#provinceSelector').on('change', function() {
        let provinceId = $(this).val();
        console.log('Province changed to:', provinceId);

        // Reset city selector
        $('#citySelector').empty().append('<option value="">Pilih Kota/Kabupaten</option>').prop('disabled', true);

        // Disable courier selector and confirm button
        $('#courierSelector').prop('disabled', true);
        $('#confirmLocationBtn').prop('disabled', true);

        if (provinceId) {
            loadCities(provinceId);
        }
    });

    // City change handler
    $('#citySelector').on('change', function() {
        let cityId = $(this).val();
        console.log('City changed to:', cityId);

        // Enable/disable courier selector and confirm button
        if (cityId) {
            $('#courierSelector').prop('disabled', false);
            $('#confirmLocationBtn').prop('disabled', false);
        } else {
            $('#courierSelector').prop('disabled', true);
            $('#confirmLocationBtn').prop('disabled', true);
        }
    });

    // Courier change handler
    $('#courierSelector').on('change', function() {
        console.log('Courier changed to:', $(this).val());
    });

    // Confirm button handler
    $('#confirmLocationBtn').on('click', function() {
        let provinceId = $('#provinceSelector').val();
        let cityId = $('#citySelector').val();
        let provinceName = $('#provinceSelector option:selected').text();
        let cityName = $('#citySelector option:selected').text();
        let courier = $('#courierSelector').val();

        // Don't proceed if province or city is not selected
        if (!provinceId || !cityId) {
            alert('Silakan pilih provinsi dan kota terlebih dahulu');
            return;
        }

        console.log('Confirming location:', {
            provinceId: provinceId,
            cityId: cityId,
            provinceName: provinceName,
            cityName: cityName,
            courier: courier
        });

        // Update hidden fields
        $('#province_id').val(provinceId);
        $('#city_id').val(cityId);
        $('#province_name').val(provinceName);
        $('#city_name').val(cityName);
        $('#courier').val(courier);

        // Update compatibility fields for old code
        $('#hidden_province').val(provinceId);
        $('#hidden_destination').val(cityId);

        // Update visible location display
        $('#selected-location-display').text(cityName + ', ' + provinceName)
            .removeClass('text-muted').addClass('text-success');

        // Close modal
        $('#locationSelectorModal').modal('hide');

        // Trigger shipping calculation
        triggerShippingCalculation(provinceId, cityId, courier);

        // Trigger a custom event that other scripts can listen for
        var locationEvent = new CustomEvent('locationSelected', {
            detail: {
                provinceId: provinceId,
                cityId: cityId,
                provinceName: provinceName,
                cityName: cityName,
                courier: courier
            }
        });
        document.dispatchEvent(locationEvent);

        // Also trigger jQuery event for compatibility
        $(document).trigger('location:selected', {
            provinceId: provinceId,
            cityId: cityId,
            provinceName: provinceName,
            cityName: cityName,
            courier: courier
        });
    });

    // Check and display any pre-selected location data
    restoreSelectedLocation();
}

// Show loading indicator for provinces
function showProvinceLoading(show = true) {
    if (show) {
        $('#province-loading').removeClass('d-none');
        $('#provinceSelector').prop('disabled', true);
    } else {
        $('#province-loading').addClass('d-none');
        $('#provinceSelector').prop('disabled', false);
    }
}

// Show loading indicator for cities
function showCityLoading(show = true) {
    if (show) {
        $('#city-loading').removeClass('d-none');
        $('#citySelector').prop('disabled', true);
    } else {
        $('#city-loading').addClass('d-none');
        $('#citySelector').prop('disabled', false);
    }
}

// Load provinces from API
function loadProvinces() {
    showProvinceLoading(true);
    console.log('Loading provinces...');

    $.ajax({
        url: "/api/shipping/provinces",
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log('Province API response:', response);
            var provinceSelector = $('#provinceSelector');
            provinceSelector.empty().append('<option value="">Pilih Provinsi</option>');

            if (response && response.status === 'success' && response.data && response.data.length > 0) {
                $.each(response.data, function(index, province) {
                    provinceSelector.append(
                        $('<option>', {
                            value: province.province_id,
                            text: province.name
                        })
                    );
                });

                // Restore previously selected province if any
                var savedProvinceId = $('#province_id').val();
                if (savedProvinceId) {
                    provinceSelector.val(savedProvinceId).trigger('change');
                }
            } else {
                console.error('No provinces returned from API');
                alert('Gagal memuat data provinsi. Silakan coba lagi.');
            }

            showProvinceLoading(false);
        },
        error: function(xhr, status, error) {
            console.error("Error loading provinces:", error);
            showProvinceLoading(false);
            alert('Gagal memuat data provinsi. Silakan coba lagi.');
        }
    });
}

// Load cities for selected province
function loadCities(provinceId) {
    showCityLoading(true);
    console.log('Loading cities for province ID:', provinceId);

    $.ajax({
        url: "/api/shipping/cities/" + provinceId,
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log('Cities API response:', response);
            var citySelector = $('#citySelector');
            citySelector.empty().append('<option value="">Pilih Kota/Kabupaten</option>');

            if (response && response.status === 'success' && response.data && response.data.length > 0) {
                $.each(response.data, function(index, city) {
                    citySelector.append(
                        $('<option>', {
                            value: city.city_id,
                            text: city.type + ' ' + city.name
                        })
                    );
                });

                // Enable city selector
                citySelector.prop('disabled', false);

                // Restore previously selected city if any
                var savedCityId = $('#city_id').val();
                if (savedCityId) {
                    citySelector.val(savedCityId).trigger('change');
                }
            } else {
                console.error('No cities returned from API');
                alert('Gagal memuat data kota. Silakan coba lagi.');
            }

            showCityLoading(false);
        },
        error: function(xhr, status, error) {
            console.error("Error loading cities:", error);
            showCityLoading(false);
            alert('Gagal memuat data kota. Silakan coba lagi.');
        }
    });
}

// Trigger shipping cost calculation
function triggerShippingCalculation(provinceId, cityId, courier) {
    // Enable submit button (as a fallback in case shipping.js isn't loaded)
    $('button[type="submit"]').prop('disabled', false);

    // Call shipping.js calculate function if available
    if (typeof window.calculateShippingCost === 'function') {
        console.log('Calling shipping.js calculateShippingCost()');
        window.calculateShippingCost(provinceId, cityId, courier);
    } else {
        console.warn('window.calculateShippingCost() not found - shipping.js may not be loaded');
    }
}

// Restore any pre-selected location data
function restoreSelectedLocation() {
    var provinceId = $('#province_id').val();
    var cityId = $('#city_id').val();
    var provinceName = $('#province_name').val();
    var cityName = $('#city_name').val();

    if (provinceId && cityId && provinceName && cityName) {
        console.log('Restoring selected location:', {
            provinceId: provinceId,
            cityId: cityId,
            provinceName: provinceName,
            cityName: cityName
        });

        $('#selected-location-display').text(cityName + ', ' + provinceName)
            .removeClass('text-muted').addClass('text-success');

        // Enable submit button
        $('button[type="submit"]').prop('disabled', false);
    }
}
</script>
