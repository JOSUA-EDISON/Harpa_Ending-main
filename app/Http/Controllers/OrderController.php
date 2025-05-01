<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('invoice')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create(Request $request)
    {
        $productId = $request->query('product_id');
        $product = null;

        if ($productId) {
            $product = Product::findOrFail($productId);
        }

        return view('orders.create', compact('product'));
    }

    /**
     * Store a newly created order in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'phone_number' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock if inventory is tracked
        if ($product->track_inventory && $product->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = new Order([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $product->price * $validated['quantity'],
                'shipping_address' => $validated['shipping_address'],
                'phone_number' => $validated['phone_number'],
                'notes' => $validated['notes'] ?? null,
                'payment_status' => 1, // 1 = pending payment
                'status' => 'pending'
            ]);

            $order->save();

            // Create order item
            $orderItem = new OrderItem([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $validated['quantity'],
            ]);

            $orderItem->save();

            // Create invoice
            $invoice = new Invoice([
                'order_id' => $order->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'amount' => $order->total_amount,
                'status' => 'unpaid',
                'due_date' => now()->addDays(3), // Set due date 3 days from now
            ]);

            $invoice->save();

            // Update stock if inventory is tracked
            if ($product->track_inventory) {
                $product->stock_quantity -= $validated['quantity'];
                $product->save();
            }

            DB::commit();

            return redirect()->route('payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.product', 'invoice']);

        return view('orders.show', compact('order'));
    }

    // Add a method to handle payment
    public function payNow(Order $order)
    {
        if ($order->payment_status !== null && $order->payment_status != 1) { // check if not pending payment
            return redirect()->route('orders.show', $order)->with('error', 'Order sudah dibayar atau kadaluarsa.');
        }

        return redirect()->route('payment.show', $order);
    }

    /**
     * Store a new order from the shopping cart
     */
    public function storeFromCart(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'phone_number' => 'required|string',
            'notes' => 'nullable|string',
            'shipping_cost' => 'required|numeric',
            'shipping_service' => 'required|string',
        ]);

        // User pasti sudah login karena route dilindungi oleh middleware auth
        $cart = Auth::user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Simpan cart items sebelum dihapus untuk digunakan nanti
        $cartItems = $cart->items->toArray();

        // Calculate subtotal from cart items
        $subtotal = 0;
        foreach ($cart->items as $cartItem) {
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        DB::beginTransaction();
        $order = null;

        try {
            // Create order
            $order = new Order([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $subtotal + $validated['shipping_cost'],
                'shipping_address' => $validated['shipping_address'],
                'phone_number' => $validated['phone_number'],
                'notes' => $validated['notes'] ?? null,
                'shipping_cost' => $validated['shipping_cost'],
                'shipping_service' => $validated['shipping_service'],
                'payment_status' => 1, // 1 = menunggu pembayaran
                'status' => 'pending'
            ]);

            $order->save();

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                // Get product name - either from cart item or from related product
                $productName = $cartItem->product_name;
                if (empty($productName) && $cartItem->product) {
                    $productName = $cartItem->product->name;
                } else if (empty($productName)) {
                    // Fallback for null product name
                    $productName = "Product #" . $cartItem->product_id;
                }

                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $productName,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                $orderItem->save();

                // Update product stock if inventory is tracked
                if ($cartItem->product && $cartItem->product->track_inventory) {
                    $cartItem->product->stock_quantity -= $cartItem->quantity;
                    $cartItem->product->save();
                }

                // Catat produk sebagai dilihat saat pembelian
                if ($cartItem->product) {
                    $cartItem->product->recordView(Auth::id());
                }
            }

            // Create invoice
            $invoice = new Invoice([
                'order_id' => $order->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'amount' => $order->total_amount,
                'status' => 'unpaid',
                'due_date' => now()->addDays(3),
            ]);

            $invoice->save();

            // Clear the cart
            $cart->items()->delete();
            $cart->updateTotal();

            DB::commit();

            // Log the order and redirect
            Log::info("Order created successfully. ID: {$order->id}, Number: {$order->order_number}. Redirecting to payment page.");

            // Redirect to payment page - ENSURE this is using the correct route name
            return redirect()->route('payment.show', ['order' => $order->id])->with('success', 'Pesanan berhasil dibuat. Lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating order: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
