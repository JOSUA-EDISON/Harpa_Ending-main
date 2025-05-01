<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Group settings by category
        $rajaongkirSettings = Setting::where('key', 'like', 'rajaongkir_%')->get();
        $midtransSettings = Setting::where('key', 'like', 'midtrans_%')->get();

        // Pass the Setting class to view
        return view('admin.settings.index', compact('rajaongkirSettings', 'midtransSettings'))
            ->with('Setting', Setting::class);
    }

    /**
     * Update RajaOngkir API settings.
     */
    public function updateRajaOngkir(Request $request)
    {
        $request->validate([
            'rajaongkir_api_key' => 'required|string',
            'rajaongkir_package' => 'required|in:starter,basic,pro',
        ]);

        try {
            // Update settings
            Setting::updateOrCreateSetting('rajaongkir_api_key', $request->rajaongkir_api_key, 'RajaOngkir API Key');
            Setting::updateOrCreateSetting('rajaongkir_package', $request->rajaongkir_package, 'RajaOngkir Package (starter, basic, pro)');

            // Clear cache if you're caching settings
            Cache::forget('settings.rajaongkir');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan RajaOngkir berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update RajaOngkir settings: ' . $e->getMessage());

            return redirect()->route('admin.settings.index')
                ->with('error', 'Gagal memperbarui pengaturan RajaOngkir: ' . $e->getMessage());
        }
    }

    /**
     * Update Midtrans API settings.
     */
    public function updateMidtrans(Request $request)
    {
        $request->validate([
            'midtrans_merchant_id' => 'required|string',
            'midtrans_client_key' => 'required|string',
            'midtrans_server_key' => 'required|string',
            'midtrans_is_production' => 'required|in:true,false',
        ]);

        try {
            // Update settings
            Setting::updateOrCreateSetting('midtrans_merchant_id', $request->midtrans_merchant_id, 'Midtrans Merchant ID');
            Setting::updateOrCreateSetting('midtrans_client_key', $request->midtrans_client_key, 'Midtrans Client Key');
            Setting::updateOrCreateSetting('midtrans_server_key', $request->midtrans_server_key, 'Midtrans Server Key');
            Setting::updateOrCreateSetting('midtrans_is_production', $request->midtrans_is_production, 'Midtrans Production Mode (true/false)');

            // Clear cache if you're caching settings
            Cache::forget('settings.midtrans');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan Midtrans berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update Midtrans settings: ' . $e->getMessage());

            return redirect()->route('admin.settings.index')
                ->with('error', 'Gagal memperbarui pengaturan Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Test RajaOngkir API connection
     */
    public function testRajaOngkir(Request $request)
    {
        Log::info('RajaOngkir test request received', $request->only('api_key'));

        $request->validate([
            'api_key' => 'required|string'
        ]);

        try {
            // Use the correct API endpoint based on package (default to starter)
            $package = Setting::getValueByKey('rajaongkir_package', 'starter');
            $apiUrl = "https://api.rajaongkir.com/{$package}/province";

            Log::info("Making request to RajaOngkir with key: " . substr($request->api_key, 0, 5) . "... to endpoint: {$apiUrl}");

            $response = Http::timeout(15)
                ->withHeaders([
                    'key' => $request->api_key
                ])
                ->get($apiUrl);

            // Log the raw response for debugging
            Log::info('RajaOngkir raw response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Parse the response body
            $responseData = $response->json();

            // Check if the response has the expected structure
            if (isset($responseData['rajaongkir']['status']['code']) && $responseData['rajaongkir']['status']['code'] == 200) {
                Log::info('RajaOngkir connection successful');
                return response()->json([
                    'success' => true,
                    'message' => 'API RajaOngkir berhasil terhubung',
                    'data' => [
                        'package' => $package,
                        'status' => $responseData['rajaongkir']['status']
                    ]
                ]);
            } else {
                $errorMessage = $responseData['rajaongkir']['status']['description'] ?? 'Respons API tidak valid';
                Log::error('RajaOngkir API invalid response', ['response' => $responseData]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'invalid_response'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('RajaOngkir API test failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'api_key_prefix' => substr($request->api_key, 0, 5)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke API RajaOngkir: ' . $e->getMessage(),
                'error_code' => 'connection_error'
            ]);
        }
    }

    /**
     * Test Midtrans API connection
     */
    public function testMidtrans(Request $request)
    {
        Log::info('Midtrans test request received', $request->only('server_key'));

        $request->validate([
            'server_key' => 'required|string'
        ]);

        try {
            // Use the appropriate Midtrans API URL based on environment setting
            $isProduction = Setting::getValueByKey('midtrans_is_production', 'false') === 'true';
            $baseUrl = $isProduction
                ? 'https://api.midtrans.com'
                : 'https://api.sandbox.midtrans.com';

            // Use a dummy transaction_id for testing
            $dummyTransactionId = 'test-' . time();
            $encodedServerKey = base64_encode($request->server_key . ':');
            $apiUrl = "{$baseUrl}/v2/{$dummyTransactionId}/status";

            Log::info("Making request to Midtrans with server key: " . substr($request->server_key, 0, 5) . "... to endpoint: {$apiUrl}");

            $response = Http::timeout(15)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $encodedServerKey
                ])
                ->get($apiUrl);

            // Log the raw response for debugging
            Log::info('Midtrans raw response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Parse the response body
            $responseData = $response->json();

            // If we get a 404, that's actually expected since the transaction doesn't exist
            // But it means the authentication was successful
            if ($response->status() == 404) {
                Log::info('Midtrans connection successful (404 expected)');
                return response()->json([
                    'success' => true,
                    'message' => 'API Midtrans berhasil terhubung',
                    'data' => [
                        'environment' => $isProduction ? 'production' : 'sandbox',
                        'status' => $response->status()
                    ]
                ]);
            }
            // If we get a 401, authentication failed
            else if ($response->status() == 401) {
                $errorMessage = $responseData['error_messages'][0] ?? 'Otentikasi gagal. Server Key tidak valid.';
                Log::error('Midtrans API authentication failed', ['response' => $responseData]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'auth_failed'
                ]);
            }
            // For other response codes, return the message
            else {
                $errorMessage = $responseData['status_message'] ?? 'Respons API tidak valid';
                Log::error('Midtrans API unexpected response', ['response' => $responseData]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'unexpected_response'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans API test failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'server_key_prefix' => substr($request->server_key, 0, 5)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke API Midtrans: ' . $e->getMessage(),
                'error_code' => 'connection_error'
            ]);
        }
    }
}
