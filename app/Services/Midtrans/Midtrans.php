<?php

namespace App\Services\Midtrans;

use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class Midtrans {
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');

        // Log the configuration
        Log::debug('Midtrans configuration loaded', [
            'server_key_set' => !empty($this->serverKey),
            'is_production' => $this->isProduction,
            'is_sanitized' => $this->isSanitized,
            'is_3ds' => $this->is3ds
        ]);

        $this->_configureMidtrans();
    }

    protected function _configureMidtrans()
    {
        // Verify that required configuration is present
        if (empty($this->serverKey)) {
            Log::warning('Midtrans is being configured with an empty server key. Payment operations will likely fail.');
        }

        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }
}
