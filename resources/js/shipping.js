/**
 * Shipping Calculator for Harpa Ecommerce
 */

function updateShippingCost() {
    const provinceId = $('#province_id').val();
    const cityId = $('#city_id').val();
    const courier = $('#courier').val() || 'jne';
    const isLandingView = $('body').hasClass('landing-page');
    const containerSelector = '#shipping-services-container';

    console.log('Updating shipping cost:', { provinceId, cityId, courier });

    if (!provinceId || !cityId) {
        console.error('Missing province or city ID');
        return;
    }

    // Calculate weight (100g per item)
    let totalItems = $('.checkout-items-list .checkout-item').length;
    let totalWeight = totalItems * 100;
    totalWeight = Math.max(totalWeight, 100); // Minimum 100g
    totalWeight = Math.min(totalWeight, 30000); // Maximum 30kg

    // Disable submit button while calculating
    $('.btn-order, .btn-primary[type="submit"]').prop('disabled', true);

    // Show loading state
    if (isLandingView) {
        $(containerSelector).html(`
            <div class="shipping-service">
                <div class="shipping-logo">
                    <img src="/img/${courier}-logo.png" alt="${courier.toUpperCase()}" onerror="this.src='/img/default-courier.png'">
                </div>
                <div class="shipping-info">
                    <span class="shipping-name">${courier.toUpperCase()}</span>
                    <span class="shipping-price">Menghitung...</span>
                    <span class="shipping-estimate">Estimasi pengiriman</span>
                </div>
                <div class="shipping-check">
                    <i class='bx bx-loader-circle bx-spin'></i>
                </div>
            </div>
        `);
    } else {
        $(containerSelector).html(`
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
        `);
    }

    // Make AJAX request to calculate shipping
    $.ajax({
        url: "/shipping/calculate",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            origin: "153", // Jakarta Pusat (default origin)
            destination: cityId,
            weight: totalWeight,
            courier: courier
        },
        success: function(response) {
            console.log("Shipping calculation succeeded:", response);

            if (response.status === 'success' && response.data && response.data.length > 0) {
                // Get courier data
                let courierData = response.data[0];
                let availableServices = courierData.costs;

                if (availableServices && availableServices.length > 0) {
                    // Choose cheapest service
                    let cheapestService = availableServices.reduce((prev, curr) => {
                        return (prev.cost[0].value < curr.cost[0].value) ? prev : curr;
                    });

                    let serviceName = cheapestService.service;
                    let shippingCost = cheapestService.cost[0].value;
                    let estimatedDays = cheapestService.cost[0].etd.replace(' HARI', '').replace(' hari', '');
                    let courierName = courierData.name;

                    console.log(`Selected service: ${serviceName}, cost: ${shippingCost}`);

                    // Update UI based on view
                    if (isLandingView) {
                        $(containerSelector).html(`
                            <div class="shipping-service active">
                                <div class="shipping-logo">
                                    <img src="/img/${courier}-logo.png" alt="${courierName}" onerror="this.src='/img/default-courier.png'">
                                </div>
                                <div class="shipping-info">
                                    <span class="shipping-name">${courierName} - ${serviceName}</span>
                                    <span class="shipping-price">Rp ${formatNumber(shippingCost)}</span>
                                    <span class="shipping-estimate">Estimasi ${estimatedDays} hari</span>
                                </div>
                                <div class="shipping-check">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                            </div>
                        `);
                    } else {
                        $(containerSelector).html(`
                            <div class="d-flex align-items-center justify-content-between border rounded p-2">
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
                        `);
                    }

                    // Update hidden fields
                    $('#shipping_cost').val(shippingCost);
                    $('#shipping_service').val(`${courierName} - ${serviceName}`);

                    // Update total
                    updateOrderTotal(shippingCost);

                    // Enable submit button
                    $('.btn-order, .btn-primary[type="submit"]').prop('disabled', false);
                } else {
                    handleShippingError(containerSelector, isLandingView, courier, "Tidak ada layanan pengiriman yang tersedia untuk rute ini");
                }
            } else {
                handleShippingError(containerSelector, isLandingView, courier, response.message || "Gagal menghitung biaya pengiriman");
            }
        },
        error: function(xhr, status, error) {
            console.error("Shipping calculation failed:", {status, error, response: xhr.responseText});
            handleShippingError(containerSelector, isLandingView, courier, "Terjadi kesalahan saat menghitung ongkos kirim");
        }
    });
}

// Handle shipping errors
function handleShippingError(containerSelector, isLanding, courier, message) {
    if (isLanding) {
        $(containerSelector).html(`
            <div class="shipping-service">
                <div class="shipping-logo">
                    <img src="/img/${courier}-logo.png" alt="${courier.toUpperCase()}" onerror="this.src='/img/default-courier.png'">
                </div>
                <div class="shipping-info">
                    <span class="shipping-name">${courier.toUpperCase()}</span>
                    <span class="shipping-price text-danger">${message}</span>
                    <span class="shipping-estimate">Silakan coba lagi nanti</span>
                </div>
                <div class="shipping-check">
                    <i class='bx bx-error-circle text-danger'></i>
                </div>
            </div>
        `);
    } else {
        $(containerSelector).html(`
            <div class="d-flex align-items-center justify-content-between border rounded p-2">
                <div class="d-flex align-items-center">
                    <img src="/img/${courier}-logo.png" alt="${courier.toUpperCase()}" width="60" class="mr-3" onerror="this.src='/img/default-courier.png'">
                    <div>
                        <h6 class="mb-0">${courier.toUpperCase()}</h6>
                        <span class="text-danger">${message}</span>
                    </div>
                </div>
                <div class="text-right">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
            </div>
        `);
    }

    // Disable submit button
    $('.btn-order, .btn-primary[type="submit"]').prop('disabled', true);
}

// Update total pesanan dengan biaya pengiriman
function updateOrderTotal(shippingCost) {
    let subtotal = parseInt($('.order-subtotal-value').text().replace(/[^\d]/g, '')) || 0;
    let total = subtotal + parseInt(shippingCost);

    $('.shipping-cost-value').text('Rp ' + formatNumber(shippingCost));
    $('.order-total-value').text('Rp ' + formatNumber(total));
}

// Format angka menjadi format rupiah
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Make functions available globally
window.updateShippingCost = updateShippingCost;
window.handleShippingError = handleShippingError;
window.updateOrderTotal = updateOrderTotal;
window.formatNumber = formatNumber;
