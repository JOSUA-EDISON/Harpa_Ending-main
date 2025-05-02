<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\ProductDetailController;

Route::get('/', function () {
    $featuredProducts = Product::where('featured', true)->get();
    return view('welcome', compact('featuredProducts'));
})->name('welcome');

// Product API endpoint
Route::get('/api/products/{product}', [ProductController::class, 'getProductDetails']);
Route::post('/api/products/{product}/record-view', [ProductController::class, 'recordViewApi'])->middleware('auth');

Auth::routes([
    'reset' => true, // keep password.reset route for final password resetting
]);

// Override default password reset email route
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetOtpEmail'])
    ->name('password.email');

// Add OTP verification routes for password reset
Route::get('password/reset/otp/{email}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetOtpForm'])
    ->name('password.otp');
Route::post('password/reset/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.verify-otp');
Route::post('password/reset/resend-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resendOtp'])
    ->name('password.resend-otp');

// Email Verification Routes
Route::get('/verify-email/{email?}', [App\Http\Controllers\Auth\VerificationController::class, 'showVerificationForm'])
    ->name('verification.notice')
    ->middleware('guest');
Route::post('/verify-email', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware('guest');
Route::post('/verify-email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resendOtp'])
    ->name('verification.resend')
    ->middleware('guest');

// Google Authentication Routes - Add these after Auth::routes() and before middleware routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Google Invitation Routes
Route::get('invitation/{token}', [App\Http\Controllers\Auth\GoogleInvitationController::class, 'showAcceptInvitation'])->name('auth.google.accept');
Route::get('invitation/{token}/google', [App\Http\Controllers\Auth\GoogleInvitationController::class, 'redirectToGoogle'])->name('auth.google.invitation.redirect');
Route::get('invitation/google/callback', [App\Http\Controllers\Auth\GoogleInvitationController::class, 'handleGoogleCallback'])->name('auth.google.invitation.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');

    // Product Catalog - restricted to authenticated users
    Route::get('/products/catalog', [ProductCatalogController::class, 'index'])->name('products.catalog');

    // Product Detail
    Route::get('/products/{product}/detail', [ProductDetailController::class, 'show'])->name('products.detail');

    // Cart Routes - restricted to authenticated users
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'updateCartItem'])->name('cart.update-item');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'removeCartItem'])->name('cart.remove-item');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Payment Routes - Ensure these are ABOVE other potentially conflicting routes
    Route::get('/payment/success/{order_id}', [PaymentController::class, 'success'])
        ->name('payment.success');
    Route::get('/payment/pending/{order_id}', [PaymentController::class, 'pending'])
        ->name('payment.pending');
    Route::get('/payment/error/{order_id}', [PaymentController::class, 'error'])
        ->name('payment.error');
    Route::get('/payment/{order}', [PaymentController::class, 'show'])
        ->name('payment.show');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/from-cart', [OrderController::class, 'storeFromCart'])->name('orders.store-from-cart');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/pay', [OrderController::class, 'payNow'])->name('orders.pay-now');

    // User Invoice Routes
    Route::get('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/upload', [App\Http\Controllers\InvoiceController::class, 'uploadForm'])->name('invoices.upload-form');
    Route::post('/invoices/{invoice}/upload', [App\Http\Controllers\InvoiceController::class, 'uploadPaymentProof'])->name('invoices.upload');
    Route::get('/invoices/{invoice}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoices.print');

    // Midtrans Callback
    Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
        ->name('midtrans.callback')
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    // Shipping Calculator
    Route::post('/shipping/calculate', [ShippingController::class, 'calculate'])->name('shipping.calculate');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Admin Order Routes
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/filter', [App\Http\Controllers\Admin\OrderController::class, 'filterByStatus'])->name('orders.filter');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders-export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');

        // Admin Settings Routes
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings/rajaongkir', [App\Http\Controllers\Admin\SettingsController::class, 'updateRajaOngkir'])->name('settings.update-rajaongkir');
        Route::put('/settings/midtrans', [App\Http\Controllers\Admin\SettingsController::class, 'updateMidtrans'])->name('settings.update-midtrans');
        Route::post('/settings/test-rajaongkir', [App\Http\Controllers\Admin\SettingsController::class, 'testRajaOngkir'])->name('settings.test-rajaongkir');
        Route::post('/settings/test-midtrans', [App\Http\Controllers\Admin\SettingsController::class, 'testMidtrans'])->name('settings.test-midtrans');

        // Admin Invoice Routes
        Route::get('/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/filter', [App\Http\Controllers\Admin\InvoiceController::class, 'filterByStatus'])->name('invoices.filter');
        Route::get('/invoices/{invoice}', [App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
        Route::put('/invoices/{invoice}/status', [App\Http\Controllers\Admin\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
        Route::get('/invoices/{invoice}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoices.print');

        // Products Routes (expanded from resource)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('admin');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('admin');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('admin');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('admin');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('admin');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('admin');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('admin');
        Route::get('/products-cards', [ProductController::class, 'cards'])->name('products.cards')->middleware('admin');

        Route::get('/hakakses', [App\Http\Controllers\HakaksesController::class, 'index'])->name('hakakses.index')->middleware('admin');
        Route::get('/hakakses/create', [App\Http\Controllers\HakaksesController::class, 'create'])->name('hakakses.create')->middleware('admin');
        Route::post('/hakakses/store', [App\Http\Controllers\HakaksesController::class, 'store'])->name('hakakses.store')->middleware('admin');
        Route::get('/hakakses/edit/{id}', [App\Http\Controllers\HakaksesController::class, 'edit'])->name('hakakses.edit')->middleware('admin');
        Route::put('/hakakses/update/{id}', [App\Http\Controllers\HakaksesController::class, 'update'])->name('hakakses.update')->middleware('admin');
        Route::delete('/hakakses/delete/{id}', [App\Http\Controllers\HakaksesController::class, 'destroy'])->name('hakakses.delete')->middleware('admin');
        Route::patch('/hakakses/promote/{id}', [App\Http\Controllers\HakaksesController::class, 'promote'])->name('hakakses.promote')->middleware('admin');
        Route::patch('/hakakses/unpromote/{id}', [App\Http\Controllers\HakaksesController::class, 'unpromote'])->name('hakakses.unpromote')->middleware('admin');

        Route::post('/hakakses/{id}/send-otp', [App\Http\Controllers\HakaksesController::class, 'sendOtp'])->name('hakakses.send-otp')->middleware('admin');
        Route::post('/hakakses/{id}/verify-otp', [App\Http\Controllers\HakaksesController::class, 'verifyOtp'])->name('hakakses.verify-otp')->middleware('admin');
        Route::put('/hakakses/{id}/update-password', [App\Http\Controllers\HakaksesController::class, 'updatePassword'])->name('hakakses.update-password')->middleware('admin');

        // Google Auth invitation route
        Route::post('/hakakses/invite-google', [App\Http\Controllers\HakaksesController::class, 'inviteGoogle'])->name('hakakses.invite-google')->middleware('admin');

        Route::get('/table-example', [App\Http\Controllers\ExampleController::class, 'table'])->name('table.example');
        Route::get('/clock-example', [App\Http\Controllers\ExampleController::class, 'clock'])->name('clock.example');
        Route::get('/chart-example', [App\Http\Controllers\ExampleController::class, 'chart'])->name('chart.example');
        Route::get('/form-example', [App\Http\Controllers\ExampleController::class, 'form'])->name('form.example');
        Route::get('/map-example', [App\Http\Controllers\ExampleController::class, 'map'])->name('map.example');
        Route::get('/calendar-example', [App\Http\Controllers\ExampleController::class, 'calendar'])->name('calendar.example');
        Route::get('/gallery-example', [App\Http\Controllers\ExampleController::class, 'gallery'])->name('gallery.example');
        Route::get('/todo-example', [App\Http\Controllers\ExampleController::class, 'todo'])->name('todo.example');
        Route::get('/contact-example', [App\Http\Controllers\ExampleController::class, 'contact'])->name('contact.example');
        Route::get('/faq-example', [App\Http\Controllers\ExampleController::class, 'faq'])->name('faq.example');
        Route::get('/news-example', [App\Http\Controllers\ExampleController::class, 'news'])->name('news.example');
        Route::get('/about-example', [App\Http\Controllers\ExampleController::class, 'about'])->name('about.example');
    });
});

// Contact form submission route
Route::post('/contact', function (Illuminate\Http\Request $request) {
    // Process contact form
    return redirect()->back()->with('status', 'Pesan Anda telah dikirim. Terima kasih!');
})->name('contact.submit');

// Shipping routes
Route::prefix('shipping')->group(function() {
    Route::get('/check-ongkir', [ShippingController::class, 'checkOngkir'])->name('shipping.check-ongkir');
    Route::get('/track', [ShippingController::class, 'trackPackage'])->name('shipping.track');
    Route::post('/track', [ShippingController::class, 'trackWaybill'])->name('shipping.track.search');

    // Shipping cost calculation
    Route::post('/calculate', [ShippingController::class, 'calculateShipping'])->name('shipping.calculate');

    // Debug/test routes
    Route::get('/city-dropdown-test', [ShippingController::class, 'cityDropdownTest'])->name('shipping.city-dropdown-test');
    Route::get('/test-cities/{provinceId}', [ShippingController::class, 'testCities']);

    // Legacy API-style routes within web routes
    Route::get('/provinces', [ShippingController::class, 'getProvinces'])->name('shipping.provinces');
    Route::get('/cities/{provinceId}', [ShippingController::class, 'getCities'])->name('shipping.cities');
});

// API endpoints for shipping (as a fallback if Laravel API routes aren't loading)
Route::prefix('api/shipping')->group(function() {
    Route::get('/provinces', [App\Http\Controllers\API\ShippingApiController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [App\Http\Controllers\API\ShippingApiController::class, 'getCities']);
    Route::post('/calculate', [App\Http\Controllers\API\ShippingApiController::class, 'calculateShipping']);
    Route::post('/cost', [App\Http\Controllers\API\ShippingApiController::class, 'calculateShipping']);
});

// Shipping test routes
// Route::get('/checkout/modal-test', function () {
//     return view('checkout.modal-test');
// })->name('checkout.modal-test');

// Route::get('/checkout/location-test', function () {
//     return view('checkout.location-test');
// })->name('checkout.location-test');

// Route::get('/checkout/test-selector', function () {
//     return view('checkout.test-selector-standalone');
// })->name('checkout.test-selector');

// Route::get('/checkout/direct-selector', function () {
//     return view('checkout.direct-selector');
// })->name('checkout.direct-selector');
