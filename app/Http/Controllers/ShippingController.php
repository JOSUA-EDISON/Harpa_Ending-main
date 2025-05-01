<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    /**
     * Display the shipping cost calculator page
     */
    public function checkOngkir()
    {
        // Get provinces from database
        $provinces = Province::all();

        return view('shipping.check-ongkir', compact('provinces'));
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        // Get provinces from database
        $provinces = Province::all();

        return response()->json(['status' => 'success', 'data' => $provinces]);
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId)
    {
        try {
            Log::info('Getting cities for province: ' . $provinceId);

            // Get cities by province ID
            $cities = City::where('province_id', $provinceId)->get();

            Log::info('Found ' . count($cities) . ' cities for province ' . $provinceId);

            return response()->json([
                'status' => 'success',
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cities: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
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
            // Log the calculation request for debugging
            Log::info('Shipping calculation request:', $request->all());

            // Set the payload for RajaOngkir
            $payload = [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ];

            Log::info('Sending request to RajaOngkir with payload:', $payload);

            $result = RajaOngkir::ongkosKirim($payload)->get();

            // Validate the result structure to ensure it's what we expect
            if (empty($result)) {
                Log::error('RajaOngkir returned empty result');
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
                    Log::warning('Courier has no shipping services:', ['courier' => $courierResult['code'] ?? 'unknown']);
                    continue;
                }

                // Add only valid services (with costs that have value and etd)
                foreach ($courierResult['costs'] as $service) {
                    if (isset($service['cost']) && !empty($service['cost']) &&
                        isset($service['cost'][0]['value']) && isset($service['cost'][0]['etd'])) {
                        $validatedResults[] = $service;
                    } else {
                        Log::warning('Service has invalid cost structure:', ['service' => $service]);
                    }
                }
            }

            Log::info('Shipping calculation result processed:', [
                'success' => true,
                'original_data_count' => count($result),
                'validated_data_count' => count($validatedResults)
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $validatedResults
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Shipping calculation error:', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the tracking page
     */
    public function trackPackage()
    {
        $couriers = [
            'jne' => 'JNE',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'wahana' => 'Wahana',
            'jnt' => 'J&T',
            'rpx' => 'RPX',
            'sap' => 'SAP',
            'sicepat' => 'SiCepat',
            'pcp' => 'PCP',
            'jet' => 'JET',
            'dse' => 'DSE',
            'first' => 'First',
            'ninja' => 'Ninja',
            'lion' => 'Lion',
            'idl' => 'IDL'
        ];

        return view('shipping.track-package', compact('couriers'));
    }

    /**
     * Track a package by waybill number
     */
    public function trackWaybill(Request $request)
    {
        $request->validate([
            'waybill' => 'required|string',
            'courier' => 'required|string'
        ]);

        try {
            $result = RajaOngkir::waybill([
                'waybill' => $request->waybill,
                'courier' => $request->courier,
            ])->get();

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display city dropdown test page
     */
    public function cityDropdownTest()
    {
        try {
            $provinces = Province::orderBy('name', 'asc')->get();
            return view('shipping.city-dropdown-test', compact('provinces'));
        } catch (\Exception $e) {
            Log::error('Error loading city dropdown test page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman test dropdown kota');
        }
    }

    /**
     * Test method for getting cities, with more verbose logging and error handling
     */
    public function testCities($provinceId)
    {
        try {
            Log::info('TEST API: Getting cities for province: ' . $provinceId);

            // Log database connection status
            try {
                DB::connection()->getPdo();
                Log::info('TEST API: Database connection established successfully');
            } catch (\Exception $e) {
                Log::error('TEST API: Database connection failed: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Database connection failed',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Validate province exists
            $province = Province::find($provinceId);
            if (!$province) {
                Log::warning('TEST API: Province not found: ' . $provinceId);

                // Check if the province ID format is valid
                if (!is_numeric($provinceId)) {
                    Log::error('TEST API: Invalid province ID format: ' . $provinceId);
                }

                // List a few provinces for debugging
                $sampleProvinces = Province::limit(5)->get();
                Log::info('TEST API: Sample provinces available: ' . json_encode($sampleProvinces->pluck('id', 'name')));

                return response()->json([
                    'status' => 'error',
                    'message' => 'Province not found',
                    'debug_info' => [
                        'provided_id' => $provinceId,
                        'sample_provinces' => $sampleProvinces->pluck('id', 'name')
                    ]
                ], 404);
            }

            Log::info('TEST API: Province found: ' . $province->name);

            // Get cities by province ID with verbose query logging
            Log::info('TEST API: Executing query: City::where(\'province_id\', ' . $provinceId . ')->get()');
            $cities = City::where('province_id', $provinceId)->get();

            Log::info('TEST API: Found ' . count($cities) . ' cities for province ' . $provinceId);

            if (count($cities) === 0) {
                Log::warning('TEST API: No cities found for province: ' . $provinceId);

                // Check if any cities exist at all
                $totalCities = City::count();
                Log::info('TEST API: Total cities in database: ' . $totalCities);

                // Check if there's a cities table schema issue
                $cityColumns = DB::getSchemaBuilder()->getColumnListing('cities');
                Log::info('TEST API: Cities table columns: ' . json_encode($cityColumns));

                return response()->json([
                    'status' => 'warning',
                    'message' => 'No cities found for this province',
                    'data' => [],
                    'debug_info' => [
                        'province_name' => $province->name,
                        'province_id' => $province->id,
                        'total_cities_in_db' => $totalCities,
                        'city_table_columns' => $cityColumns
                    ]
                ]);
            } else {
                Log::info('TEST API: First city: ' . $cities->first()->name . ' (ID: ' . $cities->first()->id . ')');
                Log::info('TEST API: Sample cities: ' . json_encode($cities->take(3)->pluck('name', 'id')));
            }

            return response()->json([
                'status' => 'success',
                'data' => $cities,
                'meta' => [
                    'province' => $province->name,
                    'province_id' => $province->id,
                    'count' => count($cities)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('TEST API: Error getting cities: ' . $e->getMessage());
            Log::error('TEST API: ' . $e->getTraceAsString());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function testPage()
    {
        $provinces = Province::all();
        return view('shipping-test', compact('provinces'));
    }

    public function getCitiesByProvince($provinceId)
    {
        try {
            $cities = City::where('province_id', $provinceId)->get();
            return response()->json([
                'success' => true,
                'data' => $cities,
                'count' => $cities->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
