<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $order;
    protected $serverKey;

    public function __construct()
    {
        parent::__construct();
        $this->serverKey = config('midtrans.server_key');

        // Get notification from Midtrans
        try {
            $this->notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            exit;
        }
    }

    public function isSignatureKeyVerified()
    {
        return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    public function isSuccess()
    {
        return ($this->notification->transaction_status == 'capture' || $this->notification->transaction_status == 'settlement');
    }

    public function isPending()
    {
        return ($this->notification->transaction_status == 'pending');
    }

    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function isDenied()
    {
        return ($this->notification->transaction_status == 'deny');
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getOrder()
    {
        if (!$this->order) {
            $this->order = Order::where('order_number', $this->notification->order_id)->firstOrFail();
        }

        return $this->order;
    }

    protected function _createLocalSignatureKey()
    {
        $orderId = $this->notification->order_id;
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->notification->gross_amount;
        $serverKey = $this->serverKey;
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');

        return $signature;
    }
}
