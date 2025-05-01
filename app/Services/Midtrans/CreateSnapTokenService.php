<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->order = $order;
    }

    public function getSnapToken()
    {
        try {
            Log::info('Building Midtrans parameters for order: ' . $this->order->order_number);

            $params = [
                'transaction_details' => [
                    'order_id' => $this->order->order_number,
                    'gross_amount' => (int) $this->order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $this->order->user->name,
                    'email' => $this->order->user->email,
                    'phone' => $this->order->phone_number,
                ],
                'item_details' => $this->getItemDetails(),
                'shipping_address' => [
                    'address' => $this->order->shipping_address,
                ],
                'callbacks' => [
                    'finish' => route('payment.success', ['order_id' => $this->order->id]),
                ]
            ];

            Log::debug('Midtrans request params: ' . json_encode($params));

            // Verify configuration before making the request
            Log::info('Midtrans Config - Server Key: ' . (empty($this->serverKey) ? 'Not set' : 'Set') .
                      ', Is Production: ' . ($this->isProduction ? 'Yes' : 'No'));

            if (empty($this->serverKey)) {
                throw new \Exception('Midtrans server key is not configured properly.');
            }

            $snapToken = Snap::getSnapToken($params);
            Log::info('Midtrans token successfully generated for order: ' . $this->order->order_number);

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Failed to generate Midtrans token: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function getItemDetails()
    {
        $items = [];

        // Add order items
        foreach ($this->order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        }

        // Add shipping cost as an item
        if ($this->order->shipping_cost > 0) {
            $items[] = [
                'id' => 'shipping',
                'price' => (int) $this->order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost (' . $this->order->shipping_service . ')',
            ];
        }

        return $items;
    }
}
