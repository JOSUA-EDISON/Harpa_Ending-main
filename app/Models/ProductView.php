<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'last_viewed_at',
        'view_count'
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
    ];

    /**
     * Get the user that viewed the product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was viewed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
