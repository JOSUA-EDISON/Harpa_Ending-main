<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductDetailController extends Controller
{
    /**
     * Menampilkan halaman detail produk
     *
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Catat produk sebagai dilihat dengan mekanisme cooldown 1 menit
        if (Auth::check()) {
            $viewRecorded = $product->recordView(Auth::id());

            if ($viewRecorded) {
                Log::info("View recorded for product ID: {$product->id} by user ID: " . Auth::id());
            }
        }

        // Memanggil view product detail
        return view('products.detail', compact('product'));
    }
}
