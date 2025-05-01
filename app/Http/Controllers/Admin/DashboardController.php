<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total sales (from all completed orders)
        $totalSales = Order::where('status', 'completed')
            ->sum('total_amount');

        // Monthly sales (from completed orders in current month)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Product sales for current month
        $monthlySoldProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(price * quantity) as total_sales')
            )
            ->whereHas('order', function($query) use ($startOfMonth, $endOfMonth) {
                $query->where('status', 'completed')
                      ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->with('product')
            ->limit(10)
            ->get();

        // Total monthly product sales
        $totalMonthlySoldItems = $monthlySoldProducts->sum('total_quantity');

        // Most popular products (based on order items quantity)
        $popularProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('product')
            ->get();

        // Most viewed products based on views column and view tracking
        $mostViewedProducts = Product::orderByDesc('views')
            ->limit(5)
            ->get();

        // Total registered users with 'user' role
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'totalSales',
            'monthlySales',
            'monthlySoldProducts',
            'totalMonthlySoldItems',
            'popularProducts',
            'mostViewedProducts',
            'totalUsers'
        ));
    }
}
