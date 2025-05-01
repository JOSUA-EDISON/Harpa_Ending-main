<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'amount',
        'status',
        'due_date',
        'payment_date',
        'payment_method',
        'payment_proof'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    /**
     * Get the order that owns the invoice
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');
        $randomString = strtoupper(substr(uniqid(), -4));

        return $prefix . $date . $randomString;
    }
}
