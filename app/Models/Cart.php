<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate and update the cart total amount.
     */
    public function updateTotal(): void
    {
        $this->total_amount = $this->items->sum('subtotal');
        $this->save();
    }

    /**
     * Get or create a cart for the user.
     */
    public static function getOrCreateCart($userId = null, $sessionId = null): Cart
    {
        if ($userId) {
            $cart = self::firstOrCreate(['user_id' => $userId]);
        } elseif ($sessionId) {
            $cart = self::firstOrCreate(['session_id' => $sessionId]);
        } else {
            throw new \Exception('Either user_id or session_id is required');
        }

        return $cart;
    }
}
