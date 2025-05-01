<!-- Direct Location Selector (No Bootstrap Modal) -->
<div id="directLocationSelector" class="location-selector-container" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background-color: rgba(0,0,0,0.5);">
    <div class="location-selector-dialog" style="background: white; width: 90%; max-width: 500px; margin: 50px auto; border-radius: 5px; box-shadow: 0 5px 15px rgba(0,0,0,0.5);">
        <div class="location-selector-header" style="padding: 15px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-size: 1.25rem;">Pilih Lokasi Pengiriman</h5>
            <button type="button" id="directCloseBtn" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">×</button>
        </div>
        <div class="location-selector-body" style="padding: 15px;">
            <!-- Province Selector -->
            <div class="form-group">
                <label for="directProvinceSelector">Provinsi</label>
                <select class="form-control" id="directProvinceSelector">
                    <option value="">Pilih Provinsi</option>
                </select>
                <div id="direct-province-loading" class="text-center mt-2 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span class="ml-2">Memuat provinsi...</span>
                </div>
            </div>

            <!-- City Selector -->
            <div class="form-group">
                <label for="directCitySelector">Kota/Kabupaten</label>
                <select class="form-control" id="directCitySelector" disabled>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
                <div id="direct-city-loading" class="text-center mt-2 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span class="ml-2">Memuat kota/kabupaten...</span>
                </div>
            </div>

            <!-- Courier Selection -->
            <div class="form-group">
                <label for="directCourierSelector">Kurir Pengiriman</label>
                <select class="form-control" id="directCourierSelector" disabled>
                    <option value="jne">JNE</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="tiki">TIKI</option>
                </select>
            </div>

            <div class="alert alert-info small">
                <i class="fas fa-info-circle mr-1"></i> Silakan pilih provinsi dan kota/kabupaten untuk melihat opsi pengiriman.
            </div>
        </div>
        <div class="location-selector-footer" style="padding: 15px; border-top: 1px solid #e9ecef; text-align: right;">
            <button type="button" class="btn btn-secondary" id="directCancelBtn">Batal</button>
            <button type="button" class="btn btn-primary" id="directConfirmBtn" disabled>Konfirmasi</button>
        </div>
    </div>
</div>

<!-- Add Direct Location Selector Button -->
<button type="button" id="directLocationSelectorBtn" class="btn btn-primary d-none" style="position: fixed; bottom: 10px; left: 10px; z-index: 9990;">
    <i class="fas fa-map-marker-alt"></i> Pilih Lokasi (Alternatif)
</button>

<script>
(function() {
    console.log('Direct location selector loaded');

    function initWhenReady() {
        if (window.jQuery) {
            jQuery(function($) {
                // Show direct button if regular modal has issues
                $('#directLocationSelectorBtn').removeClass('d-none');

                // Initialize selectors
                function showProvinceLoading(show = true) {
                    if (show) {
                        $('#direct-province-loading').removeClass('d-none');
                        $('#directProvinceSelector').prop('disabled', true);
                    } else {
                        $('#direct-province-loading').addClass('d-none');
                        $('#directProvinceSelector').prop('disabled', false);
                    }
                }

                function showCityLoading(show = true) {
                    if (show) {
                        $('#direct-city-loading').removeClass('d-none');
                        $('#directCitySelector').prop('disabled', true);
                    } else {
                        $('#direct-city-loading').addClass('d-none');
                        $('#directCitySelector').prop('disabled', false);
                    }
                }

                // Load provinces
                function loadProvinces() {
                    showProvinceLoading(true);
                    console.log('Loading provinces for direct selector...');

                    $.ajax({
                        url: "/api/shipping/provinces",
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            console.log('Province API response for direct selector:', response);
                            var provinceSelector = $('#directProvinceSelector');
                            provinceSelector.find('option').not(':first').remove();

                            if (response && response.data && response.data.length > 0) {
                                $.each(response.data, function(index, province) {
                                    provinceSelector.append(
                                        $('<option>', {
                                            value: province.province_id,
                                            text: province.name,
                                            'data-name': province.name
                                        })
                                    );
                                });

                                // Select previously selected province if any
                                var savedProvinceId = $('#province_id').val();
                                if (savedProvinceId) {
                                    provinceSelector.val(savedProvinceId).trigger('change');
                                }
                            } else {
                                console.error('No provinces returned from API');
                            }

                            showProvinceLoading(false);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading provinces:", error);
                            showProvinceLoading(false);

                            // Show error message
                            $('#direct-province-loading').removeClass('d-none').html(
                                '<div class="alert alert-danger">Gagal memuat data provinsi. Silakan coba lagi.</div>'
                            );
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
                            var citySelector = $('#directCitySelector');
                            citySelector.find('option').not(':first').remove();

                            if (response && response.data && response.data.length > 0) {
                                $.each(response.data, function(index, city) {
                                    citySelector.append(
                                        $('<option>', {
                                            value: city.city_id,
                                            text: city.type + ' ' + city.name,
                                            'data-name': city.type + ' ' + city.name
                                        })
                                    );
                                });

                                // Enable city selector
                                citySelector.prop('disabled', false);

                                // Select previously selected city if any
                                var savedCityId = $('#city_id').val();
                                if (savedCityId) {
                                    citySelector.val(savedCityId).trigger('change');
                                }
                            } else {
                                console.error('No cities returned from API');
                            }

                            showCityLoading(false);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading cities:", error);
                            showCityLoading(false);

                            // Show error message
                            $('#direct-city-loading').removeClass('d-none').html(
                                '<div class="alert alert-danger">Gagal memuat data kota. Silakan coba lagi.</div>'
                            );
                        }
                    });
                }

                // Show selector
                $('#directLocationSelectorBtn').on('click', function() {
                    $('#directLocationSelector').fadeIn(300);

                    // Load provinces if not loaded yet
                    if ($('#directProvinceSelector').find('option').length <= 1) {
                        loadProvinces();
                    }
                });

                // Hide selector
                $('#directCloseBtn, #directCancelBtn').on('click', function() {
                    $('#directLocationSelector').fadeOut(300);
                });

                // Province change handler
                $('#directProvinceSelector').on('change', function() {
                    let provinceId = $(this).val();
                    console.log('Direct province changed to:', provinceId);

                    // Reset city selector
                    $('#directCitySelector').find('option').not(':first').remove();
                    $('#directCitySelector').val('').prop('disabled', true);

                    // Disable courier selector and confirm button
                    $('#directCourierSelector').prop('disabled', true);
                    $('#directConfirmBtn').prop('disabled', true);

                    if (provinceId) {
                        loadCities(provinceId);
                    }
                });

                // City change handler
                $('#directCitySelector').on('change', function() {
                    let cityId = $(this).val();
                    console.log('Direct city changed to:', cityId);

                    // Enable/disable courier selector and confirm button
                    if (cityId) {
                        $('#directCourierSelector').prop('disabled', false);
                        $('#directConfirmBtn').prop('disabled', false);
                    } else {
                        $('#directCourierSelector').prop('disabled', true);
                        $('#directConfirmBtn').prop('disabled', true);
                    }
                });

                // Confirm button handler
                $('#directConfirmBtn').on('click', function() {
                    let provinceId = $('#directProvinceSelector').val();
                    let cityId = $('#directCitySelector').val();
                    let provinceName = $('#directProvinceSelector').find('option:selected').data('name') ||
                                       $('#directProvinceSelector').find('option:selected').text();
                    let cityName = $('#directCitySelector').find('option:selected').data('name') ||
                                  $('#directCitySelector').find('option:selected').text();
                    let courier = $('#directCourierSelector').val();

                    // Update hidden fields
                    $('#province_id').val(provinceId);
                    $('#city_id').val(cityId);
                    $('#province_name').val(provinceName);
                    $('#city_name').val(cityName);
                    $('#courier').val(courier);

                    // Update display
                    $('.selected-location-display').text(cityName + ', ' + provinceName).removeClass('text-muted').addClass('text-success');
                    $('#selected-location-display').removeClass('text-muted').addClass('text-success');

                    // Calculate shipping costs if the function exists
                    if (typeof window.calculateShippingCost === 'function') {
                        window.calculateShippingCost(provinceId, cityId, courier);
                    } else {
                        console.warn('calculateShippingCost function not found');
                    }

                    // Hide the selector
                    $('#directLocationSelector').fadeOut(300);

                    console.log('Direct location selected:', {
                        provinceId,
                        cityId,
                        provinceName,
                        cityName,
                        courier
                    });
                });
            });
        } else {
            // jQuery not loaded yet, wait and try again
            console.log('Waiting for jQuery for direct location selector...');
            setTimeout(initWhenReady, 50);
        }
    }

    // Start initialization
    initWhenReady();
})();
</script>
