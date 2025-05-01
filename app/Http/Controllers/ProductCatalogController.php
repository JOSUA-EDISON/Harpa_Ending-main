<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    /**
     * Display a catalog of all products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        // Get products with pagination
        $products = $query->paginate(12);

        // Check if the layout parameter is set to landing
        $useLandingLayout = $request->query('layout') === 'landing';

        return view('products.catalog', compact('products', 'useLandingLayout'));
    }
}
