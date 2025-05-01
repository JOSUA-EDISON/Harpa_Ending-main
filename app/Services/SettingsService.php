<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get all settings from a specific group
     *
     * @param string $prefix
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSettingsByPrefix(string $prefix)
    {
        return Setting::where('key', 'like', $prefix . '_%')->get();
    }

    /**
     * Get RajaOngkir settings
     *
     * @return array
     */
    public static function getRajaOngkirSettings()
    {
        return Cache::remember('settings.rajaongkir', 3600, function () {
            $settings = self::getSettingsByPrefix('rajaongkir');
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });
    }

    /**
     * Get Midtrans settings
     *
     * @return array
     */
    public static function getMidtransSettings()
    {
        return Cache::remember('settings.midtrans', 3600, function () {
            $settings = self::getSettingsByPrefix('midtrans');
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });
    }

    /**
     * Get a specific setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Setting::getValueByKey($key, $default);
    }

    /**
     * Update a specific setting
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return \App\Models\Setting
     */
    public static function update(string $key, $value, $description = null)
    {
        $setting = Setting::updateOrCreateSetting($key, $value, $description);

        // Clear cache
        if (strpos($key, 'rajaongkir_') === 0) {
            Cache::forget('settings.rajaongkir');
        } elseif (strpos($key, 'midtrans_') === 0) {
            Cache::forget('settings.midtrans');
        }

        return $setting;
    }
}
