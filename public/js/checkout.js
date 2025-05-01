/**
 * Checkout Script for Harpa Ecommerce
 */

// Initialize checkout functionality
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const checkoutForm = document.getElementById('checkout-form');
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const courierSelect = document.getElementById('courier');
    const checkoutButton = document.querySelector('.btn-order');

    // Shipping cost elements
    const shippingCostInput = document.getElementById('shipping_cost');
    const shippingServiceInput = document.getElementById('shipping_service');
    const shippingCostDisplay = document.querySelector('.shipping-cost-value');
    const orderTotalDisplay = document.querySelector('.order-total-value');
    const subtotalValue = document.querySelector('.order-subtotal-value');

    // Check if we're using Boxicons
    const usingBoxicons = document.querySelector('link[href*="boxicons"]') !== null;

    // Listen for shipping calculation updates
    if (provinceSelect && citySelect && courierSelect) {
        courierSelect.addEventListener('change', calculateShipping);
        citySelect.addEventListener('change', calculateShipping);
    }

    /**
     * Calculate shipping costs based on selected options
     */
    function calculateShipping() {
        const provinceId = provinceSelect.value;
        const cityId = citySelect.value;
        const courier = courierSelect.value;

        if (!provinceId || !cityId || !courier) {
            return;
        }

        // Show loading state
        const shippingContainer = document.getElementById('shipping-services-container');
        shippingContainer.innerHTML = usingBoxicons ?
            `<div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class='bx bx-loader-alt bx-spin mr-2'></i>
                    <span>Menghitung ongkos kirim...</span>
                </div>
            </div>` :
            `<div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm mr-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span>Menghitung ongkos kirim...</span>
                </div>
            </div>`;

        // Disable checkout button while calculating
        if (checkoutButton) {
            checkoutButton.disabled = true;
        }

        // Calculate weight (default to 1000g if no items)
        const itemElements = document.querySelectorAll('.checkout-item');
        let totalWeight = itemElements.length * 100; // 100g per item
        totalWeight = Math.max(totalWeight, 100); // Minimum 100g

        // Make AJAX request to RajaOngkir API
        fetch('/shipping/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                origin: '153', // Jakarta Pusat (default origin)
                destination: cityId,
                weight: totalWeight,
                courier: courier
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data && data.data.length > 0) {
                // Get courier data
                const courierData = data.data[0];
                const services = courierData.costs;

                if (services && services.length > 0) {
                    // Sort services by price (cheapest first)
                    services.sort((a, b) => a.cost[0].value - b.cost[0].value);

                    // Display available shipping options
                    let servicesHTML = '';
                    services.forEach((service, index) => {
                        const isActive = index === 0; // Make first (cheapest) service active
                        const serviceName = service.service;
                        const cost = service.cost[0].value;
                        const etd = service.cost[0].etd.replace(' HARI', '').replace(' hari', '');

                        // Check icon based on icon library in use
                        const checkIcon = usingBoxicons ?
                            `<i class='bx ${isActive ? 'bxs-check-circle' : 'bx-circle'}'></i>` :
                            `<i class="fas ${isActive ? 'fa-check-circle' : 'fa-circle'}"></i>`;

                        servicesHTML += `
                            <div class="shipping-service ${isActive ? 'active' : ''}" data-cost="${cost}" data-service="${courierData.code.toUpperCase()} - ${serviceName}">
                                <div class="shipping-logo">
                                    <img src="/img/${courier}-logo.png" alt="${courierData.name}" onerror="this.src='/img/default-courier.png'">
                                </div>
                                <div class="shipping-info">
                                    <span class="shipping-name">${courierData.name} - ${serviceName}</span>
                                    <span class="shipping-price">Rp ${formatNumber(cost)}</span>
                                    <span class="shipping-estimate">Estimasi ${etd} hari</span>
                                </div>
                                <div class="shipping-check">
                                    ${checkIcon}
                                </div>
                            </div>
                        `;

                        // Set default shipping cost and service (cheapest option)
                        if (isActive) {
                            updateShippingCost(cost, `${courierData.name} - ${serviceName}`);
                        }
                    });

                    shippingContainer.innerHTML = servicesHTML;

                    // Add click handler to shipping services
                    const serviceElements = document.querySelectorAll('.shipping-service');
                    serviceElements.forEach(el => {
                        el.addEventListener('click', function() {
                            // Remove active class from all services
                            serviceElements.forEach(service => {
                                service.classList.remove('active');

                                // Update icon based on library in use
                                if (usingBoxicons) {
                                    service.querySelector('.shipping-check i').classList.remove('bxs-check-circle');
                                    service.querySelector('.shipping-check i').classList.add('bx-circle');
                                } else {
                                    service.querySelector('.shipping-check i').classList.remove('fa-check-circle');
                                    service.querySelector('.shipping-check i').classList.add('fa-circle');
                                }
                            });

                            // Add active class to clicked service
                            this.classList.add('active');

                            // Update icon based on library in use
                            if (usingBoxicons) {
                                this.querySelector('.shipping-check i').classList.remove('bx-circle');
                                this.querySelector('.shipping-check i').classList.add('bxs-check-circle');
                            } else {
                                this.querySelector('.shipping-check i').classList.remove('fa-circle');
                                this.querySelector('.shipping-check i').classList.add('fa-check-circle');
                            }

                            // Update shipping cost and service
                            const cost = this.getAttribute('data-cost');
                            const service = this.getAttribute('data-service');
                            updateShippingCost(cost, service);
                        });
                    });

                    // Enable checkout button
                    if (checkoutButton) {
                        checkoutButton.disabled = false;
                    }
                } else {
                    handleShippingError('Tidak ada layanan pengiriman yang tersedia untuk rute ini');
                }
            } else {
                handleShippingError(data.message || 'Gagal menghitung biaya pengiriman');
            }
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            handleShippingError('Terjadi kesalahan saat menghitung ongkos kirim');
        });
    }

    /**
     * Update shipping cost and order total
     */
    function updateShippingCost(cost, serviceName) {
        // Update hidden inputs
        if (shippingCostInput && shippingServiceInput) {
            shippingCostInput.value = cost;
            shippingServiceInput.value = serviceName;
        }

        // Update displays
        if (shippingCostDisplay) {
            shippingCostDisplay.textContent = `Rp ${formatNumber(cost)}`;
        }

        // Update order total
        if (orderTotalDisplay && subtotalValue) {
            const subtotal = parseFloat(subtotalValue.textContent.replace(/[^\d]/g, ''));
            const total = subtotal + parseFloat(cost);
            orderTotalDisplay.textContent = `Rp ${formatNumber(total)}`;
        }
    }

    /**
     * Handle shipping calculation errors
     */
    function handleShippingError(message) {
        const shippingContainer = document.getElementById('shipping-services-container');
        if (shippingContainer) {
            // Error icon based on icon library in use
            const errorIcon = usingBoxicons ?
                `<i class='bx bx-error-circle mr-2'></i>` :
                `<i class="fas fa-exclamation-triangle mr-2"></i>`;

            shippingContainer.innerHTML = `
                <div class="alert alert-danger">
                    ${errorIcon} ${message}
                </div>
            `;
        }

        // Disable checkout button
        if (checkoutButton) {
            checkoutButton.disabled = true;
        }
    }

    /**
     * Format number as Indonesian currency
     */
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Add click handlers for shipping service selection
    document.addEventListener('click', function(e) {
        if (e.target.closest('.shipping-service')) {
            const serviceElement = e.target.closest('.shipping-service');
            const cost = serviceElement.getAttribute('data-cost');
            const service = serviceElement.getAttribute('data-service');

            // Remove active class from all services
            document.querySelectorAll('.shipping-service').forEach(el => {
                el.classList.remove('active');

                // Update icon based on icon library
                if (usingBoxicons) {
                    const icon = el.querySelector('.shipping-check i');
                    if (icon) {
                        icon.classList.remove('bxs-check-circle');
                        icon.classList.add('bx-circle');
                    }
                }
            });

            // Add active class to clicked service
            serviceElement.classList.add('active');

            // Update icon based on icon library
            if (usingBoxicons) {
                const icon = serviceElement.querySelector('.shipping-check i');
                if (icon) {
                    icon.classList.remove('bx-circle');
                    icon.classList.add('bxs-check-circle');
                }
            }

            // Add animation
            serviceElement.classList.add('pulse-animation');
            setTimeout(() => {
                serviceElement.classList.remove('pulse-animation');
            }, 1000);

            // Update shipping cost and service
            updateShippingCost(cost, service);
        }
    });
});
