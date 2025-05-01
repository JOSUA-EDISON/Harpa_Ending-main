<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ShippingApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Shipping API Routes
Route::prefix('shipping')->group(function() {
    Route::get('/provinces', [ShippingApiController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [ShippingApiController::class, 'getCities']);
    Route::post('/calculate', [ShippingApiController::class, 'calculateShipping']);
});

// Explicitly add the calculation route
Route::post('shipping/calculate', [ShippingApiController::class, 'calculateShipping']);
