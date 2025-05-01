<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Midtrans\CallbackService;
use App\Models\Order;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $callback = new CallbackService();

        if ($callback->isSignatureKeyVerified()) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();

            if ($callback->isSuccess()) {
                $order->payment_status = 2; // Paid
            } else if ($callback->isPending()) {
                $order->payment_status = 1; // Pending payment
            } else if ($callback->isExpire()) {
                $order->payment_status = 3; // Expired
            } else if ($callback->isCancelled()) {
                $order->payment_status = 4; // Cancelled
            } else if ($callback->isDenied()) {
                $order->payment_status = 4; // Cancelled/Denied
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Signature key not verified'
            ], 403);
        }
    }
}
