<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;
use App\Models\Order;

class CreateRedirectUrlService extends Midtrans
{
    protected $order;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->order = $order;
    }

    public function getRedirectUrl()
    {
        $params = $this->buildParameters();
        return $this->createRedirectUrl($params);
    }

    protected function buildParameters()
    {
        // Adjust these data based on your Order model structure
        return [
            'transaction_details' => [
                'order_id' => $this->order->number,
                'gross_amount' => (int) $this->order->total_price,
            ],
            'item_details' => $this->getItemDetails(),
            'customer_details' => $this->getCustomerDetails(),
        ];
    }

    protected function getItemDetails()
    {
        $items = [];

        // Add your order items based on your database structure
        // This is an example - modify according to your relationship between Order and OrderItems
        foreach ($this->order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        }

        // Add shipping cost if applicable
        if ($this->order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $this->order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost',
            ];
        }

        return $items;
    }

    protected function getCustomerDetails()
    {
        // Adjust based on your user and address model structure
        return [
            'first_name' => $this->order->user->name ?? 'Guest',
            'email' => $this->order->user->email ?? 'guest@example.com',
            'phone' => $this->order->user->phone ?? '',
            'billing_address' => $this->getBillingAddress(),
            'shipping_address' => $this->getShippingAddress(),
        ];
    }

    protected function getBillingAddress()
    {
        // Adjust based on your address model structure
        return [
            'first_name' => $this->order->user->name ?? 'Guest',
            'phone' => $this->order->user->phone ?? '',
            'address' => $this->order->billing_address ?? '',
            'city' => $this->order->billing_city ?? '',
            'postal_code' => $this->order->billing_postal_code ?? '',
            'country_code' => 'IDN',
        ];
    }

    protected function getShippingAddress()
    {
        // Adjust based on your address model structure
        return [
            'first_name' => $this->order->user->name ?? 'Guest',
            'phone' => $this->order->user->phone ?? '',
            'address' => $this->order->shipping_address ?? '',
            'city' => $this->order->shipping_city ?? '',
            'postal_code' => $this->order->shipping_postal_code ?? '',
            'country_code' => 'IDN',
        ];
    }

    protected function createRedirectUrl($params)
    {
        // For redirect URL, we use the createVtDirectTransaction method
        // This gives us a redirect URL to Midtrans payment page
        $redirectUrl = Snap::createTransaction($params)->redirect_url;

        return $redirectUrl;
    }
}
