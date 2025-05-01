<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        // Check if the invoice belongs to the authenticated user
        if ($invoice->order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load('order.items');

        return view('invoices.detail', compact('invoice'));
    }

    /**
     * Show the form for uploading payment proof
     */
    public function uploadForm(Invoice $invoice)
    {
        // Check if the invoice belongs to the authenticated user
        if ($invoice->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('invoices.upload', compact('invoice'));
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request, Invoice $invoice)
    {
        // Check if the invoice belongs to the authenticated user
        if ($invoice->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|max:2048', // Max 2MB
            'payment_method' => 'required|string',
        ]);

        // Store the payment proof
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update the invoice
        $invoice->payment_proof = $path;
        $invoice->payment_method = $validated['payment_method'];
        $invoice->status = 'waiting_confirmation';
        $invoice->payment_date = now();
        $invoice->save();

        // Update the order status
        $invoice->order->status = 'processing';
        $invoice->order->save();

        return redirect()->route('orders.show', $invoice->order)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.');
    }

    /**
     * Generate a printable invoice
     */
    public function print(Invoice $invoice)
    {
        // Check if the invoice belongs to the authenticated user
        if ($invoice->order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load('order.items');

        return view('invoices.print', compact('invoice'));
    }
}
