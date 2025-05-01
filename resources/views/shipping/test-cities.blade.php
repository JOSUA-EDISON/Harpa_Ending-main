<!DOCTYPE html>
<html>
<head>
    <title>Test Cities Loading</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Loading Cities</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" id="debug-info">
                            <p>Select a province to test city loading. Debug info will appear here.</p>
                        </div>

                        <div class="form-group">
                            <label>Provinsi</label>
                            <select class="form-control" id="province">
                                <option value="">Pilih Provinsi</option>
                                @foreach (\App\Models\Province::all() as $province)
                                    <option value="{{ $province->province_id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kota/Kabupaten</label>
                            <select class="form-control" id="city">
                                <option value="">Pilih Provinsi Terlebih Dahulu</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button id="test-direct" class="btn btn-primary">Test Direct API Call</button>
                            <button id="test-ajax" class="btn btn-success ml-2">Test Ajax</button>
                            <button id="test-checkout" class="btn btn-warning ml-2">Test Checkout Style</button>
                            <button id="test-shipping-api" class="btn btn-danger ml-2">Test Shipping API</button>
                        </div>

                        <div class="mt-3">
                            <h5>Response:</h5>
                            <pre id="response" class="bg-light p-3" style="max-height: 300px; overflow: auto;"></pre>
                        </div>

                        <div id="shipping-test-container" class="mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Display province ID for selected province
            $('#province').on('change', function() {
                const provinceId = $(this).val();
                $('#debug-info').html(`
                    <p>Selected province ID: ${provinceId}</p>
                    <p>Type: ${typeof provinceId}</p>
                    <p>API endpoint: /shipping/cities/${provinceId}</p>
                `);
            });

            // Test direct API call with fetch
            $('#test-direct').on('click', function() {
                const provinceId = $('#province').val();
                if (!provinceId) {
                    alert('Please select a province first');
                    return;
                }

                fetch(`/shipping/cities/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        $('#response').text(JSON.stringify(data, null, 2));

                        // Populate cities if successful
                        if (data.status === 'success') {
                            $('#city').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                            data.data.forEach(city => {
                                const cityType = city.type === 'Kabupaten' ? 'Kab. ' : 'Kota ';
                                $('#city').append(`<option value="${city.city_id}">${cityType}${city.name}</option>`);
                            });
                        }
                    })
                    .catch(error => {
                        $('#response').text('Error: ' + error);
                    });
            });

            // Test with jQuery Ajax
            $('#test-ajax').on('click', function() {
                const provinceId = $('#province').val();
                if (!provinceId) {
                    alert('Please select a province first');
                    return;
                }

                $.ajax({
                    url: `/shipping/cities/${provinceId}`,
                    type: 'GET',
                    beforeSend: function() {
                        $('#city').empty().append('<option value="">Loading...</option>');
                        $('#response').text('Loading...');
                    },
                    success: function(response) {
                        $('#response').text(JSON.stringify(response, null, 2));

                        // Populate cities if successful
                        if (response.status === 'success') {
                            $('#city').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                            $.each(response.data, function(key, city) {
                                const cityType = city.type === 'Kabupaten' ? 'Kab. ' : 'Kota ';
                                $('#city').append(`<option value="${city.city_id}">${cityType}${city.name}</option>`);
                            });
                        } else {
                            $('#city').empty().append('<option value="">Kota tidak tersedia</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#response').text('Error: ' + error + '\n\nResponse: ' + xhr.responseText);
                        $('#city').empty().append('<option value="">Gagal memuat kota</option>');
                    }
                });
            });

            // Test checkout shipping calculation flow
            $('#test-checkout').on('click', function() {
                const cityId = $('#city').val();
                if (!cityId) {
                    alert('Please select a province and city first');
                    return;
                }

                // Show shipping test container
                $('#shipping-test-container').show();

                // Basic courier selection
                const courier = 'jne';

                // Show loading animation
                $('#shipping-test-container').html(`
                    <h5>Simulating Checkout Shipping Calculation:</h5>
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between border rounded p-2">
                                <div class="d-flex align-items-center">
                                    <img src="/img/${courier}-logo.png" alt="${courier.toUpperCase()}" width="60" class="mr-3" onerror="this.src='/img/default-courier.png'">
                                    <div>
                                        <h6 class="mb-0">${courier.toUpperCase()}</h6>
                                        <span class="text-muted">Menghitung...</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // Simulate calculating shipping
                $.ajax({
                    url: '/shipping/calculate',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        origin: 151, // Default from config
                        destination: cityId,
                        weight: 1000, // 1kg
                        courier: courier
                    },
                    success: function(response) {
                        $('#response').text(JSON.stringify(response, null, 2));

                        if (response.status === 'success') {
                            let courierData = response.data[0];
                            let availableServices = courierData.costs;

                            if (availableServices && availableServices.length > 0) {
                                // Show all available services
                                let servicesHtml = '<h5>Available Shipping Services:</h5>';

                                $.each(availableServices, function(index, service) {
                                    const serviceName = service.service;
                                    const shippingCost = service.cost[0].value;
                                    const estimatedDays = service.cost[0].etd.replace(' HARI', '').replace(' hari', '');
                                    const courierName = courierData.name;

                                    servicesHtml += `
                                        <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2">
                                            <div class="d-flex align-items-center">
                                                <img src="/img/${courier}-logo.png" alt="${courierName}" width="60" class="mr-3" onerror="this.src='/img/default-courier.png'">
                                                <div>
                                                    <h6 class="mb-0">${courierName} - ${serviceName}</h6>
                                                    <span class="text-muted">Estimasi ${estimatedDays} hari</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-weight-bold text-success">Rp ${formatNumber(shippingCost)}</span>
                                                <i class="fas fa-check-circle text-success ml-2"></i>
                                            </div>
                                        </div>
                                    `;
                                });

                                $('#shipping-test-container').html(servicesHtml);
                            } else {
                                $('#shipping-test-container').html(`
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Tidak ada layanan pengiriman yang tersedia untuk rute ini
                                    </div>
                                `);
                            }
                        } else {
                            $('#shipping-test-container').html(`
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                    ${response.message || "Gagal menghitung biaya pengiriman"}
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#response').text('Error: ' + error + '\n\nResponse: ' + xhr.responseText);
                        $('#shipping-test-container').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                Terjadi kesalahan saat menghitung ongkos kirim: ${error}
                            </div>
                        `);
                    }
                });
            });

            // Format number helper function
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Test shipping API directly
            $('#test-shipping-api').on('click', function() {
                const cityId = $('#city').val();
                if (!cityId) {
                    alert('Please select a province and city first');
                    return;
                }

                $('#response').text('Testing shipping calculation API...');
                $('#shipping-test-container').show().html(`
                    <div class="alert alert-info">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span class="ml-2">Sending direct request to shipping calculation API...</span>
                    </div>
                `);

                const data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origin: 151, // Default from config
                    destination: cityId,
                    weight: 1000, // 1kg
                    courier: 'jne'
                };

                // Use jQuery AJAX for form submission
                $.ajax({
                    url: '/shipping/calculate',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        console.log('Direct test - sending data:', data);
                    },
                    success: function(response) {
                        console.log('Direct test - response:', response);
                        $('#response').text(JSON.stringify(response, null, 2));
                        $('#shipping-test-container').html(`
                            <div class="alert alert-success">
                                <strong>Success!</strong> Shipping calculation API returned successfully.
                            </div>
                        `);
                    },
                    error: function(xhr, status, error) {
                        console.error('Direct test - error:', {xhr, status, error});
                        $('#response').text(`Error: ${error}\nResponse: ${xhr.responseText}`);
                        $('#shipping-test-container').html(`
                            <div class="alert alert-danger">
                                <strong>Error!</strong> ${error}
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>
</html>
