/**
 * Admin API Settings JavaScript
 * This file contains functionality for the API settings admin panel
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Settings JS loaded');

    // Toggle password visibility for API keys
    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.api-key-group').querySelector('input');

            // Toggle between password and text type
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });

    // Add confirmation for switching to production mode
    const productionToggle = document.getElementById('midtrans_is_production');

    if (productionToggle) {
        productionToggle.addEventListener('change', function() {
            if (this.value === 'true') {
                const confirmed = confirm('PERHATIAN: Anda akan mengaktifkan mode produksi. Ini akan menggunakan pembayaran nyata. Apakah Anda yakin?');

                if (!confirmed) {
                    this.value = 'false';
                }
            }
        });
    }

    // Test API connection functions
    const testRajaOngkirBtn = document.getElementById('test-rajaongkir');
    const testMidtransBtn = document.getElementById('test-midtrans');

    if (testRajaOngkirBtn) {
        console.log('RajaOngkir test button found');
        testRajaOngkirBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Test RajaOngkir button clicked');
            testRajaOngkirConnection();
        });
    } else {
        console.warn('RajaOngkir test button not found');
    }

    if (testMidtransBtn) {
        console.log('Midtrans test button found');
        testMidtransBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Test Midtrans button clicked');
            testMidtransConnection();
        });
    } else {
        console.warn('Midtrans test button not found');
    }

    // Copy API key to clipboard
    const copyButtons = document.querySelectorAll('.copy-api-key');

    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.api-key-group').querySelector('input');

            // Change to text type temporarily if it's password
            const wasPassword = input.type === 'password';
            if (wasPassword) {
                input.type = 'text';
            }

            // Select and copy
            input.select();
            document.execCommand('copy');

            // Restore password type if needed
            if (wasPassword) {
                input.type = 'password';
            }

            // Clear selection
            window.getSelection().removeAllRanges();

            // Show copied notification
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.add('btn-success');
            this.classList.remove('btn-secondary');

            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('btn-success');
                this.classList.add('btn-secondary');
            }, 2000);
        });
    });

    // Auto-check API connection if credentials are present
    // Delay this to ensure all DOM elements are properly loaded
    setTimeout(() => {
        checkAPIConnectionsIfCredentialsExist();
    }, 500);

    // Debug buttons
    const debugRajaOngkirBtn = document.getElementById('debug-rajaongkir');
    const debugMidtransBtn = document.getElementById('debug-midtrans');

    if (debugRajaOngkirBtn) {
        debugRajaOngkirBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Debug RajaOngkir button clicked');
            testDirectRequest('rajaongkir');
        });
    }

    if (debugMidtransBtn) {
        debugMidtransBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Debug Midtrans button clicked');
            testDirectRequest('midtrans');
        });
    }
});

/**
 * Automatically check API connections if credentials exist
 */
function checkAPIConnectionsIfCredentialsExist() {
    console.log('Checking for API credentials...');
    const rajaongkirApiKey = document.getElementById('rajaongkir_api_key');
    const midtransServerKey = document.getElementById('midtrans_server_key');

    // Check RajaOngkir connection if API key exists
    if (rajaongkirApiKey && rajaongkirApiKey.value.trim() !== '') {
        console.log('RajaOngkir API key found, will test connection');
        setTimeout(() => {
            testRajaOngkirConnection();
        }, 1000);
    } else {
        console.log('No RajaOngkir API key found');
    }

    // Check Midtrans connection if server key exists
    if (midtransServerKey && midtransServerKey.value.trim() !== '') {
        console.log('Midtrans server key found, will test connection');
        setTimeout(() => {
            testMidtransConnection();
        }, 1500);
    } else {
        console.log('No Midtrans server key found');
    }
}

/**
 * Show success popup notification
 * @param {string} title - Title of the notification
 * @param {string} message - Message body
 * @param {Object} data - Additional data to display in console
 */
function showSuccessNotification(title, message, data = null) {
    // Log detailed information to console
    console.group('API Connection Test - Success');
    console.log('Title:', title);
    console.log('Message:', message);
    if (data) {
        console.log('Response Data:', data);
    }
    console.groupEnd();

    // Create popup notification
    const popup = document.createElement('div');
    popup.className = 'api-test-popup success-popup';
    popup.innerHTML = `
        <div class="popup-header">
            <i class="fas fa-check-circle"></i>
            <h3>${title}</h3>
            <button class="close-popup">&times;</button>
        </div>
        <div class="popup-body">
            <p>${message}</p>
        </div>
    `;

    // Add to document
    document.body.appendChild(popup);

    // Add event listener to close button
    popup.querySelector('.close-popup').addEventListener('click', function() {
        popup.classList.add('popup-hiding');
        setTimeout(() => {
            if (document.body.contains(popup)) {
                document.body.removeChild(popup);
            }
        }, 300);
    });

    // Auto-close after 5 seconds
    setTimeout(() => {
        if (document.body.contains(popup)) {
            popup.classList.add('popup-hiding');
            setTimeout(() => {
                if (document.body.contains(popup)) {
                    document.body.removeChild(popup);
                }
            }, 300);
        }
    }, 5000);
}

/**
 * Update connection info display
 * @param {string} apiType - 'rajaongkir' or 'midtrans'
 * @param {boolean} success - Whether the connection was successful
 * @param {Object} data - Response data from the API
 */
function updateConnectionInfo(apiType, success, data = {}) {
    const infoElement = document.getElementById(`${apiType}-connection-info`);

    if (!infoElement) {
        console.warn(`Connection info element for ${apiType} not found`);
        return;
    }

    console.log(`Updating ${apiType} connection info. Success: ${success}`, data);

    // Remove previous status classes
    infoElement.classList.remove('success', 'error');

    // Add appropriate class based on success status
    infoElement.classList.add(success ? 'success' : 'error');

    // Format current timestamp
    const now = new Date();
    const timestamp = `${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;

    // Create details based on API type
    let detailsHtml = '';

    if (apiType === 'rajaongkir') {
        if (success) {
            const packageName = data.data?.package || 'starter';
            detailsHtml = `
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">Terhubung</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Package:</span>
                    <span class="detail-value">${packageName.toUpperCase()}</span>
                </div>
                <div class="connection-timestamp">Terakhir diperbarui: ${timestamp}</div>
            `;
        } else {
            detailsHtml = `
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">Gagal Terhubung</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pesan Error:</span>
                    <span class="detail-value">${data.message || 'Error tidak diketahui'}</span>
                </div>
                <div class="connection-timestamp">Terakhir diperbarui: ${timestamp}</div>
            `;
        }
    } else { // midtrans
        if (success) {
            const environment = data.data?.environment || 'sandbox';
            const envText = environment === 'production' ? 'PRODUCTION (Live)' : 'SANDBOX (Testing)';
            detailsHtml = `
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">Terhubung</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Mode:</span>
                    <span class="detail-value">${envText}</span>
                </div>
                <div class="connection-timestamp">Terakhir diperbarui: ${timestamp}</div>
            `;
        } else {
            detailsHtml = `
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">Gagal Terhubung</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pesan Error:</span>
                    <span class="detail-value">${data.message || 'Error tidak diketahui'}</span>
                </div>
                <div class="connection-timestamp">Terakhir diperbarui: ${timestamp}</div>
            `;
        }
    }

    // Update the contents
    const detailsElement = infoElement.querySelector('.connection-details');
    if (detailsElement) {
        detailsElement.innerHTML = detailsHtml;
    } else {
        console.warn(`Connection details element for ${apiType} not found`);
    }
}

/**
 * Test RajaOngkir API connection
 */
function testRajaOngkirConnection() {
    console.log('Testing RajaOngkir connection...');
    const apiKey = document.getElementById('rajaongkir_api_key')?.value;
    const statusIndicator = document.getElementById('rajaongkir-status');

    if (!statusIndicator) {
        console.error('RajaOngkir status indicator element not found');
        return;
    }

    if (!apiKey || apiKey.trim() === '') {
        statusIndicator.innerHTML = '<span class="badge badge-danger">API Key Kosong</span>';
        console.warn('RajaOngkir API key is empty');
        return;
    }

    // Show loading state
    statusIndicator.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="badge badge-warning">Mengecek...</span>';

    // Get the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF Token tidak ditemukan!');
        statusIndicator.innerHTML = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Error: CSRF Token tidak tersedia</span>';
        return;
    }

    console.log('Sending RajaOngkir test request with API key:', apiKey.substring(0, 3) + '...');

    // Make a test request to RajaOngkir
    fetch('/admin/settings/test-rajaongkir', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ api_key: apiKey })
    })
    .then(response => {
        console.log('RajaOngkir response received:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP status ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('RajaOngkir data:', data);
        if (data.success) {
            statusIndicator.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Berhasil Terhubung</span>';

            // Update connection info section
            updateConnectionInfo('rajaongkir', true, data);

            // Show success popup with RajaOngkir details
            const packageName = data.data?.package || 'starter';
            showSuccessNotification(
                'RajaOngkir Berhasil Terhubung',
                `Koneksi API RajaOngkir berhasil dengan package ${packageName.toUpperCase()}.`,
                data
            );
        } else {
            statusIndicator.innerHTML = `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Gagal: ${data.message || 'Error tidak diketahui'}</span>`;
            // Update connection info with error
            updateConnectionInfo('rajaongkir', false, data);
        }
    })
    .catch(error => {
        console.error('RajaOngkir Error:', error);
        statusIndicator.innerHTML = `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Error: ${error.message || 'Koneksi gagal'}</span>`;
        // Update connection info with error
        updateConnectionInfo('rajaongkir', false, { message: error.message || 'Koneksi gagal' });
    });
}

/**
 * Test Midtrans API connection
 */
function testMidtransConnection() {
    console.log('Testing Midtrans connection...');
    const serverKey = document.getElementById('midtrans_server_key')?.value;
    const statusIndicator = document.getElementById('midtrans-status');

    if (!statusIndicator) {
        console.error('Midtrans status indicator element not found');
        return;
    }

    if (!serverKey || serverKey.trim() === '') {
        statusIndicator.innerHTML = '<span class="badge badge-danger">Server Key Kosong</span>';
        console.warn('Midtrans server key is empty');
        return;
    }

    // Show loading state
    statusIndicator.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="badge badge-warning">Mengecek...</span>';

    // Get the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF Token tidak ditemukan!');
        statusIndicator.innerHTML = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Error: CSRF Token tidak tersedia</span>';
        return;
    }

    console.log('Sending Midtrans test request with server key:', serverKey.substring(0, 3) + '...');

    // Make a test request to Midtrans
    fetch('/admin/settings/test-midtrans', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ server_key: serverKey })
    })
    .then(response => {
        console.log('Midtrans response received:', response.status);
        if (!response.ok && response.status !== 404) { // 404 is expected for test transaction
            throw new Error(`HTTP status ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Midtrans data:', data);
        if (data.success) {
            statusIndicator.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Berhasil Terhubung</span>';

            // Update connection info section
            updateConnectionInfo('midtrans', true, data);

            // Show success popup with Midtrans details
            const environment = data.data?.environment || 'sandbox';
            const envText = environment === 'production' ? 'PRODUCTION (Live)' : 'SANDBOX (Testing)';
            showSuccessNotification(
                'Midtrans Berhasil Terhubung',
                `Koneksi API Midtrans berhasil dalam mode ${envText}.`,
                data
            );
        } else {
            statusIndicator.innerHTML = `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Gagal: ${data.message || 'Error tidak diketahui'}</span>`;
            // Update connection info with error
            updateConnectionInfo('midtrans', false, data);
        }
    })
    .catch(error => {
        console.error('Midtrans Error:', error);
        statusIndicator.innerHTML = `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Error: ${error.message || 'Koneksi gagal'}</span>`;
        // Update connection info with error
        updateConnectionInfo('midtrans', false, { message: error.message || 'Koneksi gagal' });
    });
}

/**
 * Test API connection through direct XHR for debugging
 */
function testDirectRequest(apiType) {
    console.log(`Testing ${apiType} connection with direct XHR...`);

    const statusIndicator = document.getElementById(apiType === 'rajaongkir' ? 'rajaongkir-status' : 'midtrans-status');

    if (!statusIndicator) {
        console.error(`${apiType} status indicator element not found`);
        return;
    }

    let key, url, payload;

    if (apiType === 'rajaongkir') {
        key = document.getElementById('rajaongkir_api_key')?.value;
        url = '/admin/settings/test-rajaongkir';
        payload = { api_key: key };
    } else {
        key = document.getElementById('midtrans_server_key')?.value;
        url = '/admin/settings/test-midtrans';
        payload = { server_key: key };
    }

    if (!key || key.trim() === '') {
        console.error('API Key kosong');
        statusIndicator.innerHTML = '<span class="badge badge-danger">API Key Kosong</span>';
        return;
    }

    statusIndicator.innerHTML = '<span class="badge badge-warning">Debugging...</span>';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF Token tidak ditemukan!');
        statusIndicator.innerHTML = '<span class="badge badge-danger">Error: CSRF Token tidak tersedia</span>';
        return;
    }

    console.log(`Sending debug request for ${apiType} with key:`, key.substring(0, 3) + '...');

    // Create and send XHR request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function() {
        if (xhr.readyState < 4) {
            console.log(`XHR state change: ${xhr.readyState}`);
        }

        if (xhr.readyState === 4) {
            // Create a debug group in console
            console.group(`Debug API Test - ${apiType.toUpperCase()}`);
            console.log(`Status Code: ${xhr.status}`);
            console.log(`Headers: `, xhr.getAllResponseHeaders());
            console.log(`Raw Response: `, xhr.responseText);

            try {
                const response = JSON.parse(xhr.responseText);
                console.log(`Parsed Response:`, response);
                console.groupEnd();

                if (response.success) {
                    statusIndicator.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Debug: Berhasil</span>';

                    // Update connection info section with debug information
                    updateConnectionInfo(apiType, true, response);

                    // Show detailed popup for debugging success
                    let title, message;
                    if (apiType === 'rajaongkir') {
                        const packageName = response.data?.package || 'starter';
                        title = 'Debug RajaOngkir Berhasil';
                        message = `Debug koneksi API RajaOngkir berhasil dengan package ${packageName.toUpperCase()}.<br>
                                  <small class="text-muted">Status code: ${xhr.status}</small>`;
                    } else {
                        const environment = response.data?.environment || 'sandbox';
                        const envText = environment === 'production' ? 'PRODUCTION (Live)' : 'SANDBOX (Testing)';
                        title = 'Debug Midtrans Berhasil';
                        message = `Debug koneksi API Midtrans berhasil dalam mode ${envText}.<br>
                                  <small class="text-muted">Status code: ${xhr.status}</small>`;
                    }

                    showSuccessNotification(title, message, response);
                } else {
                    statusIndicator.innerHTML = `<span class="badge badge-danger">Debug: ${response.message || 'Gagal'}</span>`;
                    // Update connection info with error
                    updateConnectionInfo(apiType, false, response);
                }
            } catch (e) {
                console.error(`Parse Error:`, e);
                console.groupEnd();
                statusIndicator.innerHTML = `<span class="badge badge-danger">Debug Error: ${xhr.status}</span>`;
                // Update connection info with error
                updateConnectionInfo(apiType, false, { message: `Parse Error: ${e.message}` });
            }
        }
    };

    xhr.onerror = function(e) {
        console.error(`Network Error during ${apiType} debug test:`, e);
        statusIndicator.innerHTML = '<span class="badge badge-danger">Network Error</span>';
        // Update connection info with network error
        updateConnectionInfo(apiType, false, { message: 'Network Error' });
    };

    console.log(`Sending Debug Request (${apiType}):`, payload);
    xhr.send(JSON.stringify(payload));
}
