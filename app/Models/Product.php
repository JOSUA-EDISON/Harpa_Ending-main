<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'featured',
        'stock_quantity',
        'track_inventory',
        'views'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean',
        'stock_quantity' => 'integer',
        'track_inventory' => 'boolean',
        'views' => 'integer'
    ];

    /**
     * Check if the product is in stock
     */
    public function inStock()
    {
        return !$this->track_inventory || $this->stock_quantity > 0;
    }

    /**
     * Get the stock status text
     */
    public function getStockStatusAttribute()
    {
        if (!$this->track_inventory) {
            return 'Tersedia';
        }

        if ($this->stock_quantity > 10) {
            return 'Tersedia';
        } elseif ($this->stock_quantity > 0) {
            return 'Stok Terbatas';
        } else {
            return 'Habis';
        }
    }

    /**
     * Increment view count for this product
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get product views by users
     */
    public function productViews()
    {
        return $this->hasMany(ProductView::class);
    }

    /**
     * Record view for this product with cooldown
     *
     * @param int $userId The user ID viewing the product
     * @return bool Whether the view was counted
     */
    public function recordView($userId)
    {
        if (!$userId) {
            return false; // Guest users aren't tracked
        }

        $now = now();
        $productView = $this->productViews()
            ->where('user_id', $userId)
            ->first();

        if ($productView) {
            // Jika sudah lewat 1 menit dari view terakhir, tambahkan view baru
            if ($productView->last_viewed_at->addMinute()->lt($now)) {
                $productView->update([
                    'last_viewed_at' => $now,
                    'view_count' => $productView->view_count + 1
                ]);

                // Increment the product's total views counter
                $this->increment('views');

                return true;
            }

            return false; // Masih dalam cooldown 1 menit
        } else {
            // First time viewing this product
            $this->productViews()->create([
                'user_id' => $userId,
                'last_viewed_at' => $now,
                'view_count' => 1
            ]);

            // Increment the product's total views counter
            $this->increment('views');

            return true;
        }
    }
}
