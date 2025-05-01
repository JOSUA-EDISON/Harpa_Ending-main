<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'shipping_address',
        'phone_number',
        'notes',
        'shipping_cost',
        'shipping_service'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the invoice for the order
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $date = now()->format('Ymd');
        $randomString = strtoupper(substr(uniqid(), -4));

        return $prefix . $date . $randomString;
    }
}
