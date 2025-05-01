<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Province;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart) {
            return view('cart.index', [
                'items' => [],
                'totalItems' => 0,
                'total' => 0,
            ]);
        }

        $items = $cart->items()->with('product')->get();

        return view('cart.index', [
            'items' => $items,
            'totalItems' => $items->sum('quantity'),
            'total' => $cart->total_amount,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'redirect_to' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock if inventory is tracked
        if ($product->track_inventory && $product->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart = Auth::user()->cart;

        // Initialize cart if it doesn't exist
        if (!$cart) {
            Log::info('Creating new cart for user: ' . Auth::id());
            $cart = new Cart([
                'user_id' => Auth::id(),
                'total' => 0
            ]);
            $cart->save();
            // No need to refresh, we already have the cart object
        }

        // Check if the product is already in the cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity if product already exists in cart
            Log::info('Updating existing cart item: ' . $cartItem->id . ' for product: ' . $product->id);
            $cartItem->quantity = $validated['quantity'];
            $cartItem->calculateSubtotal();
            $cartItem->save();
            $cart->updateTotal();
        } else {
            // Add new item to cart
            Log::info('Adding new cart item for product: ' . $product->id);
            $cartItem = new CartItem([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $validated['quantity'],
            ]);

            $result = $cart->items()->save($cartItem);
            Log::info('Save result: ' . ($result ? 'success' : 'failed'));
            $cart->updateTotal(); // Update cart total after adding new item
        }

        // Update cart total
        $cart->updateTotal();
        Log::info('Cart updated. Total: ' . $cart->total . ', Items count: ' . $cart->items()->count());

        // Redirect to the appropriate destination
        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update cart item quantity.
     */
    public function updateCartItem(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Verify the cart item belongs to the user's cart
        $cart = Auth::user()->cart;
        if ($cartItem->cart_id !== $cart->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check product stock
        $product = $cartItem->product;
        if ($product->track_inventory && $product->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Update quantity
        $cartItem->quantity = $validated['quantity'];
        $cartItem->calculateSubtotal();

        // Update cart total
        $cart->updateTotal();

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Remove item from cart.
     */
    public function removeCartItem(CartItem $cartItem)
    {
        // Verify the cart item belongs to the user's cart
        $cart = Auth::user()->cart;
        if ($cartItem->cart_id !== $cart->id) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();
        $cart->updateTotal();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Proceed to checkout.
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }

        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Calculate subtotal from cart items
        $subtotal = 0;
        foreach ($cart->items as $cartItem) {
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        // Get provinces for shipping calculator - load the full model objects
        $provinces = Province::all();

        return view('cart.checkout', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'provinces' => $provinces,
            'user' => $user,
            'view' => $request->query('view')
        ]);
    }
}
