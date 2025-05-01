/**
 * Shipping Calculator for Harpa Ecommerce
 * Enhanced version with robust error handling
 */

// Global variables
let originCity = 501; // Origin city for shipping (Jakarta Pusat)
let destinationCity;
let weight = 1000; // Default weight in grams
let selectedCourier;

/**
 * Calculate shipping cost using RajaOngkir API
 * @param {number} provinceId - The province ID
 * @param {number} cityId - The city ID
 * @param {string} courier - The courier code (jne, pos, tiki)
 */
function calculateShippingCost(provinceId, cityId, courier) {
    // Check if required parameters are provided
    if (!provinceId || !cityId || !courier) {
        console.error('Missing required parameters for shipping calculation');
        return;
    }

    // Set destination city and selected courier
    destinationCity = cityId;
    selectedCourier = courier;

    // Display loading message
    $('#shipping-services-container').html(`
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Menghitung ongkos kirim...</p>
        </div>
    `);

    // Make AJAX request to calculate shipping
    $.ajax({
        url: '/api/shipping/calculate',
        type: 'POST',
        dataType: 'json',
        data: {
            origin: originCity,
            destination: destinationCity,
            weight: weight,
            courier: selectedCourier
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: handleShippingResponse,
        error: handleShippingError
    });
}

/**
 * Handle successful shipping response
 * @param {Object} response - Response from shipping API
 */
function handleShippingResponse(response) {
    console.log('Shipping response:', response);

    if (response.status === 'success' && response.data) {
        // Check if the data contains shipping services directly
        if (Array.isArray(response.data) && response.data.length > 0) {
            displayShippingOptions(response.data);
            return;
        }

        // Handle original format where results is an array with courier results
        if (response.data.results && Array.isArray(response.data.results) && response.data.results.length > 0) {
            const result = response.data.results[0];

            if (result.costs && result.costs.length > 0) {
                displayShippingOptions(result.costs);
                return;
            }
        }

        // No valid shipping services found
        $('#shipping-services-container').html(`
            <div class="alert alert-warning">
                <i class="bx bx-info-circle mr-2"></i>
                Tidak ada layanan pengiriman yang tersedia untuk tujuan ini.
            </div>
        `);
    } else {
        $('#shipping-services-container').html(`
            <div class="alert alert-danger">
                <i class="bx bx-error-circle mr-2"></i>
                Gagal menghitung ongkos kirim: ${response.message || 'Terjadi kesalahan'}
            </div>
        `);
    }
}

/**
 * Handle shipping calculation error
 * @param {Object} xhr - XHR object
 * @param {string} status - Error status
 * @param {string} error - Error message
 */
function handleShippingError(xhr, status, error) {
    console.error('Shipping calculation error:', status, error);
    let errorMessage = 'Gagal menghitung ongkos kirim.';

    if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage += ' ' + xhr.responseJSON.message;
    }

    $('#shipping-services-container').html(`
        <div class="alert alert-danger">
            <i class="bx bx-error-circle mr-2"></i>
            ${errorMessage}
        </div>
    `);
}

/**
 * Display shipping options in the UI
 * @param {Array} services - Array of shipping services
 */
function displayShippingOptions(services) {
    if (!services || services.length === 0) {
        $('#shipping-services-container').html(`
            <div class="alert alert-warning">
                <i class="bx bx-info-circle mr-2"></i>
                Tidak ada layanan pengiriman yang tersedia.
            </div>
        `);
        return;
    }

    let optionsHtml = `
        <div class="shipping-options">
            <div class="form-group">
                <label class="form-label">Pilih Layanan Pengiriman</label>
                <div class="shipping-services-list">
    `;

    services.forEach(service => {
        service.cost.forEach(cost => {
            const serviceCode = `${selectedCourier.toUpperCase()}-${service.service}`;
            const serviceName = service.service;
            const serviceDescription = service.description || '';
            const price = cost.value;
            const etd = cost.etd || '1-3';
            const formattedPrice = formatCurrency(price);

            optionsHtml += `
                <div class="form-check shipping-option mb-3">
                    <input class="form-check-input shipping-service-radio"
                           type="radio"
                           name="shipping_service_option"
                           id="service_${serviceCode}"
                           value="${serviceCode}"
                           data-service="${serviceName}"
                           data-cost="${price}">
                    <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="service_${serviceCode}">
                            <div>
                            <div class="font-weight-bold">${selectedCourier.toUpperCase()} ${serviceName}</div>
                            <div class="text-muted small">${serviceDescription}</div>
                            <div class="text-muted small">Estimasi ${etd} hari</div>
                        </div>
                        <div class="shipping-price font-weight-bold">${formattedPrice}</div>
                    </label>
                </div>
            `;
        });
    });

    optionsHtml += `
                </div>
            </div>
            </div>
    `;

    $('#shipping-services-container').html(optionsHtml);

    // Add click handler for shipping options
    $('.shipping-service-radio').on('click', function() {
        const serviceName = $(this).data('service');
        const serviceCost = parseInt($(this).data('cost'));
        const serviceCode = $(this).val();

        // Set hidden field values
        $('#shipping_cost').val(serviceCost);
        $('#shipping_service').val(serviceCode);

        // Update displayed costs
        updateShippingCost(serviceCost);
    });
}

/**
 * Update shipping cost and total in the UI
 * @param {number} cost - Shipping cost amount
 */
function updateShippingCost(cost = 0) {
    // Get subtotal from the page
    const subtotalText = $('.order-subtotal-value').text().replace('Rp ', '').replace(/\./g, '').replace(',', '.');
    const subtotal = parseFloat(subtotalText) || 0;

    // Calculate new total
    const total = subtotal + cost;

    // Update displayed values
    $('.shipping-cost-value').text(`Rp ${formatCurrency(cost)}`);
    $('.order-total-value').text(`Rp ${formatCurrency(total)}`);

    // Enable checkout button - this is important!
    $('#checkout-button').prop('disabled', false);

    console.log('Updated shipping cost:', cost);
    console.log('Updated total:', total);
}

/**
 * Format currency in Indonesian Rupiah format
 * @param {number} amount - Amount to format
 * @returns {string} Formatted amount
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID').format(amount);
}

// Export functions for use in other scripts
window.calculateShippingCost = calculateShippingCost;
window.updateShippingCost = updateShippingCost;

// Log that the script has loaded
console.log('Shipping.js loaded successfully');

// Auto-initialize if all required fields are filled
$(document).ready(function() {
    console.log('Document ready in shipping.js');

    // Try to auto-calculate shipping if all fields are filled
    const provinceId = $('#province_id').val();
    const cityId = $('#city_id').val();
    const courier = $('#courier').val();

    if (provinceId && cityId && courier) {
        console.log('Auto-calculating shipping with pre-filled values');
        calculateShippingCost(provinceId, cityId, courier);
    }
});
