<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Dropdown Provinsi & Kota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #debug-log {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 1rem;
            height: 300px;
            overflow-y: auto;
        }
        .log-entry {
            font-family: monospace;
            margin-bottom: 4px;
        }
        .log-info { color: #0d6efd; }
        .log-error { color: #dc3545; }
        .log-success { color: #198754; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Test Dropdown Provinsi & Kota</h1>

        <div class="alert alert-info">
            <strong>Info:</strong> Halaman ini dibuat untuk menguji masalah dropdown kota yang tidak bekerja setelah memilih provinsi.
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Test</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="province_id" class="form-label">Provinsi</label>
                            <select id="province_id" class="form-select" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="city_id" class="form-label">Kota/Kabupaten</label>
                            <select id="city_id" class="form-select" disabled required>
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="test-button" class="btn btn-primary">Test Shipping</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Debug Log</h4>
                        <button id="clear-log" class="btn btn-sm btn-outline-secondary">Clear Log</button>
                    </div>
                    <div class="card-body">
                        <div id="debug-log"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Info URLs</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>URL untuk API Kota:</strong> <code>{{ url('/shipping/cities') }}/<span id="selected-province-id">undefined</span></code></p>
                        <p><strong>URL alternatif untuk API Test Kota:</strong> <code>{{ url('/shipping/test-cities') }}/<span id="test-province-id">undefined</span></code></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('cart.checkout') }}" class="btn btn-secondary">Kembali ke Checkout</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Constants for API URLs
            const CITIES_API_URL = "{{ url('/shipping/cities') }}";
            const TEST_CITIES_API_URL = "{{ url('/shipping/test-cities') }}";

            // Debug logging functions
            function logInfo(message) {
                $('#debug-log').append(`<div class="log-entry log-info">[INFO] ${message}</div>`);
                console.log('[INFO]', message);
                scrollLogToBottom();
            }

            function logError(message) {
                $('#debug-log').append(`<div class="log-entry log-error">[ERROR] ${message}</div>`);
                console.error('[ERROR]', message);
                scrollLogToBottom();
            }

            function logSuccess(message) {
                $('#debug-log').append(`<div class="log-entry log-success">[SUCCESS] ${message}</div>`);
                console.log('[SUCCESS]', message);
                scrollLogToBottom();
            }

            function scrollLogToBottom() {
                const debugLog = document.getElementById('debug-log');
                debugLog.scrollTop = debugLog.scrollHeight;
            }

            // Clear log button
            $('#clear-log').click(function() {
                $('#debug-log').empty();
                logInfo('Debug log cleared');
            });

            // Log initialization
            logInfo('Page initialized');
            logInfo(`Regular API URL: ${CITIES_API_URL}`);
            logInfo(`Test API URL: ${TEST_CITIES_API_URL}`);

            // Province change event
            $('#province_id').change(function() {
                const provinceId = $(this).val();

                // Update display of province ID
                $('#selected-province-id').text(provinceId || 'undefined');
                $('#test-province-id').text(provinceId || 'undefined');

                // Log the change
                logInfo(`Province selected: "${provinceId}"`);

                // Reset city dropdown if no province selected
                if (!provinceId) {
                    $('#city_id').html('<option value="">Pilih Kota/Kabupaten</option>');
                    $('#city_id').prop('disabled', true);
                    logInfo('City dropdown reset and disabled');
                    return;
                }

                // Disable city dropdown while loading
                $('#city_id').html('<option value="">Memuat...</option>');
                $('#city_id').prop('disabled', true);

                // Try both APIs to see which one works
                // First try the regular API
                logInfo(`Making AJAX request to: ${CITIES_API_URL}/${provinceId}`);

                $.ajax({
                    url: `${CITIES_API_URL}/${provinceId}`,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        logInfo(`AJAX request started to ${CITIES_API_URL}/${provinceId}`);
                    },
                    success: function(response) {
                        logSuccess(`AJAX response received from ${CITIES_API_URL}/${provinceId}`);
                        logInfo(`Response status: ${response.status}`);

                        if (response.status === 'success') {
                            const cities = response.data;
                            logInfo(`Cities found: ${cities.length}`);

                            if (cities.length > 0) {
                                logInfo(`First city: ID=${cities[0].id}, Name=${cities[0].name}`);
                            }

                            let options = '<option value="">Pilih Kota/Kabupaten</option>';

                            $.each(cities, function(index, city) {
                                options += `<option value="${city.id}">${city.name}</option>`;
                            });

                            $('#city_id').html(options);
                            $('#city_id').prop('disabled', false);
                            logSuccess('City dropdown populated successfully');
                        } else {
                            logError(`API returned error status: ${response.message || 'Unknown error'}`);
                            $('#city_id').html('<option value="">Error memuat data</option>');

                            // Try the test API as fallback
                            tryTestApiAsFallback(provinceId);
                        }
                    },
                    error: function(xhr, status, error) {
                        logError(`AJAX error: ${error}`);
                        logError(`Status: ${status}`);

                        if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                logError(`Response error message: ${response.message || 'No message'}`);
                            } catch (e) {
                                logError(`Raw response: ${xhr.responseText.substring(0, 100)}...`);
                            }
                        }

                        $('#city_id').html('<option value="">Error memuat data</option>');

                        // Try the test API as fallback
                        tryTestApiAsFallback(provinceId);
                    }
                });
            });

            // Function to try the test API as fallback
            function tryTestApiAsFallback(provinceId) {
                logInfo(`Trying fallback API: ${TEST_CITIES_API_URL}/${provinceId}`);

                $.ajax({
                    url: `${TEST_CITIES_API_URL}/${provinceId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        logSuccess(`Fallback API response received`);
                        logInfo(`Fallback response status: ${response.status}`);

                        if (response.status === 'success') {
                            const cities = response.data;
                            logInfo(`Cities found (fallback): ${cities.length}`);

                            if (cities.length > 0) {
                                logInfo(`First city (fallback): ID=${cities[0].id}, Name=${cities[0].name}`);
                            }

                            let options = '<option value="">Pilih Kota/Kabupaten</option>';

                            $.each(cities, function(index, city) {
                                options += `<option value="${city.id}">${city.name}</option>`;
                            });

                            $('#city_id').html(options);
                            $('#city_id').prop('disabled', false);
                            logSuccess('City dropdown populated with fallback API');
                        } else {
                            logError(`Fallback API returned error: ${response.message || 'Unknown error'}`);
                            $('#city_id').html('<option value="">Error memuat data</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        logError(`Fallback AJAX error: ${error}`);
                        logError(`Fallback Status: ${status}`);
                        $('#city_id').html('<option value="">Gagal memuat data kota</option>');
                    }
                });
            }

            // Test button click event
            $('#test-button').click(function() {
                const provinceId = $('#province_id').val();
                const cityId = $('#city_id').val();

                logInfo(`Test Button Clicked - Province: ${provinceId}, City: ${cityId}`);

                if (!provinceId || !cityId) {
                    logError('Please select both province and city');
                    alert('Silakan pilih Provinsi dan Kota/Kabupaten terlebih dahulu.');
                    return;
                }

                alert(`Tes berhasil! Provinsi ID: ${provinceId}, Kota ID: ${cityId}`);
            });

            // Log page load complete
            logInfo('Page fully loaded and ready');
        });
    </script>
</body>
</html>
