<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run if the settings table exists and we're not in the console (for migrations)
        if (!$this->app->runningInConsole() && Schema::hasTable('settings')) {
            // Load RajaOngkir settings
            $this->loadRajaOngkirSettings();

            // Load Midtrans settings
            $this->loadMidtransSettings();
        }
    }

    /**
     * Load RajaOngkir settings from the database
     */
    private function loadRajaOngkirSettings(): void
    {
        try {
            $rajaongkirApiKey = Setting::getValueByKey('rajaongkir_api_key');
            $rajaongkirPackage = Setting::getValueByKey('rajaongkir_package');

            if ($rajaongkirApiKey) {
                Config::set('shipping.api_key', $rajaongkirApiKey);
            }

            if ($rajaongkirPackage) {
                Config::set('shipping.package', $rajaongkirPackage);
            }
        } catch (\Exception $e) {
            Log::error('Failed to load RajaOngkir settings: ' . $e->getMessage());
        }
    }

    /**
     * Load Midtrans settings from the database
     */
    private function loadMidtransSettings(): void
    {
        try {
            $midtransMerchantId = Setting::getValueByKey('midtrans_merchant_id');
            $midtransClientKey = Setting::getValueByKey('midtrans_client_key');
            $midtransServerKey = Setting::getValueByKey('midtrans_server_key');
            $midtransIsProduction = Setting::getValueByKey('midtrans_is_production') === 'true';

            if ($midtransMerchantId) {
                Config::set('midtrans.merchant_id', $midtransMerchantId);
            }

            if ($midtransClientKey) {
                Config::set('midtrans.client_key', $midtransClientKey);
            }

            if ($midtransServerKey) {
                Config::set('midtrans.server_key', $midtransServerKey);
            }

            Config::set('midtrans.is_production', $midtransIsProduction);
        } catch (\Exception $e) {
            Log::error('Failed to load Midtrans settings: ' . $e->getMessage());
        }
    }
}
