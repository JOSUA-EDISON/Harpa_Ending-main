<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Illuminate\Support\Facades\Log;

class ShippingApiController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            $provinces = Province::all();
            return response()->json([
                'status' => 'success',
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting provinces: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve provinces: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities based on provinceId.
     */
    public function getCities($provinceId)
    {
        try {
            $cities = City::where('province_id', $provinceId)->orderBy('name')->get();

            // Return consistent response format even when no cities found
            if ($cities->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No cities found for this province',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cities retrieved successfully',
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving cities: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve cities. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'origin' => 'required|numeric',
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|in:jne,pos,tiki'
        ]);

        try {
            Log::info('API: Shipping calculation request:', $request->all());

            // Check if origin and destination are valid
            if (!City::find($request->origin)) {
                Log::error('API: Invalid origin city ID: ' . $request->origin);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kota asal tidak valid'
                ], 400);
            }

            if (!City::find($request->destination)) {
                Log::error('API: Invalid destination city ID: ' . $request->destination);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kota tujuan tidak valid'
                ], 400);
            }

            $payload = [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ];

            Log::info('API: Sending request to RajaOngkir with payload:', $payload);

            try {
            $result = RajaOngkir::ongkosKirim($payload)->get();
                Log::info('API: RajaOngkir raw response:', ['result' => $result]);
            } catch (\Exception $apiException) {
                Log::error('API: RajaOngkir API exception: ' . $apiException->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error communicating with shipping API: ' . $apiException->getMessage()
                ], 500);
            }

            // Validate the result structure to ensure it's what we expect
            if (empty($result)) {
                Log::error('API: RajaOngkir returned empty result');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Layanan pengiriman tidak mengembalikan data'
                ], 500);
            }

            // Process and validate each courier result
            $validatedResults = [];
            foreach ($result as $courierResult) {
                // Check if courier result has the necessary structure
                if (!isset($courierResult['costs']) || empty($courierResult['costs'])) {
                    Log::warning('API: Courier has no shipping services:', ['courier' => $courierResult['code'] ?? 'unknown']);
                    continue;
                }

                // Add only valid services (with costs that have value and etd)
                foreach ($courierResult['costs'] as $service) {
                    if (isset($service['cost']) && !empty($service['cost']) &&
                        isset($service['cost'][0]['value'])) {
                        // Ensure ETD exists or set default
                        if (!isset($service['cost'][0]['etd'])) {
                            $service['cost'][0]['etd'] = 'N/A';
                        }
                        $validatedResults[] = $service;
                    } else {
                        Log::warning('API: Service has invalid cost structure:', ['service' => $service]);
                    }
                }
            }

            Log::info('API: Shipping calculation processed:', [
                'success' => true,
                'data_count' => count($validatedResults)
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $validatedResults
            ]);
        } catch (\Exception $e) {
            Log::error('API: Shipping calculation error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }
}
