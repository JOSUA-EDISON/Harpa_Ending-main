<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Shipping Debug Tool</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="province" class="form-label">Province</label>
                                <select name="province_id" id="province" class="form-control">
                                    <option value="">-- Select Province --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <select name="city_id" id="city" class="form-control">
                                    <option value="">-- Select City --</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h5>Debug Information:</h5>
                                <div class="alert alert-secondary">
                                    <strong>Selected Province ID:</strong> <span id="selected-province-id">None</span>
                                </div>
                                <div class="alert alert-info">
                                    <strong>API Response:</strong>
                                    <pre id="api-response" style="max-height: 200px; overflow-y: auto;">None</pre>
                                </div>
                                <div id="error-container" class="alert alert-danger d-none">
                                    <strong>Error:</strong>
                                    <span id="error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#province').on('change', function() {
                const provinceId = $(this).val();
                $('#selected-province-id').text(provinceId || 'None');

                if (provinceId) {
                    // Clear previous city options
                    $('#city').html('<option value="">-- Select City --</option>');
                    $('#error-container').addClass('d-none');

                    // Use the test endpoint for debugging
                    $.ajax({
                        url: '/api/shipping/test-cities/' + provinceId,
                        type: 'GET',
                        beforeSend: function() {
                            $('#api-response').text('Loading...');
                        },
                        success: function(response) {
                            // Display raw API response
                            $('#api-response').text(JSON.stringify(response, null, 2));

                            // Process cities if available
                            if (response.status === 'success' && response.data && response.data.length > 0) {
                                $.each(response.data, function(key, city) {
                                    $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                });
                            } else {
                                console.error("No cities returned or error in response:", response);
                                $('#error-container').removeClass('d-none');
                                $('#error-message').text('No cities found or API returned an error. See console for details.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Ajax error:", xhr.responseText);
                            $('#api-response').text(xhr.responseText);
                            $('#error-container').removeClass('d-none');
                            $('#error-message').text('AJAX Error: ' + error);
                        }
                    });
                } else {
                    $('#city').html('<option value="">-- Select City --</option>');
                    $('#api-response').text('None');
                }
            });
        });
    </script>
</body>
</html>
