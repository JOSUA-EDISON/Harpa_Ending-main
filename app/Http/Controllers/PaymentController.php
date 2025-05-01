<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show payment page with Midtrans integration
     */
    public function show(Order $order, Request $request)
    {
        Log::info('Payment page accessed for order: ' . $order->id);

        // Check if order belongs to the user or if it's a guest order with session ID
        if (Auth::check() && $order->user_id !== Auth::id()) {
            Log::error('Unauthorized access to payment page for order: ' . $order->id);
            abort(403, 'Unauthorized action.');
        }

        // Check payment status - allow only if pending payment or null (for backward compatibility)
        if ($order->payment_status !== null && $order->payment_status != 1) {
            Log::info('Payment already processed for order: ' . $order->id . ', status: ' . $order->payment_status);
            return redirect()->route('orders.show', $order)->with('error', 'Order sudah dibayar atau kadaluarsa.');
        }

        // Check if Midtrans credentials are set
        if (empty(config('midtrans.server_key')) || empty(config('midtrans.client_key'))) {
            Log::warning('Midtrans credentials not set. Redirecting to manual payment page for order: ' . $order->id);
            return redirect()->route('invoices.upload-form', ['invoice' => $order->invoice->id])
                ->with('info', 'Sistem pembayaran otomatis tidak tersedia saat ini. Silakan upload bukti pembayaran manual.');
        }

        // Get the snap token for this order
        Log::info('Attempting to generate Midtrans token for order: ' . $order->id);
        $midtrans = new CreateSnapTokenService($order);
        try {
            $snapToken = $midtrans->getSnapToken();
            Log::info('Midtrans token generated successfully: ' . substr($snapToken, 0, 10) . '...');

            // Debug: verify Midtrans environment
            Log::info('Midtrans environment: ' .
                      'Server Key: ' . (config('midtrans.server_key') ? 'Set' : 'Not set') . ', ' .
                      'Client Key: ' . (config('midtrans.client_key') ? 'Set' : 'Not set') . ', ' .
                      'Is Production: ' . (config('midtrans.is_production') ? 'Yes' : 'No'));

            return view('payment.snap', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Error generating Midtrans token: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());

            // Redirect to manual payment upload form
            return redirect()->route('invoices.upload-form', ['invoice' => $order->invoice->id])
                ->with('info', 'Sistem pembayaran otomatis sedang mengalami gangguan. Silakan lakukan pembayaran manual dan upload bukti pembayaran.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::findOrFail($orderId);

        // Check if the status hasn't been updated by webhook
        if ($order->payment_status === null || $order->payment_status == 1) { // pending payment or null
            $order->payment_status = 2; // Set to paid
            $order->status = 'processing';
            $order->save();

            if ($order->invoice) {
                $order->invoice->status = 'paid';
                $order->invoice->payment_date = now();
                $order->invoice->save();
            }
        }

        return view('payment.success', ['order' => $order]);
    }

    /**
     * Handle pending payment
     */
    public function pending(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::findOrFail($orderId);

        return view('payment.pending', ['order' => $order]);
    }

    /**
     * Handle payment error
     */
    public function error(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::findOrFail($orderId);

        return view('payment.error', ['order' => $order]);
    }
}
