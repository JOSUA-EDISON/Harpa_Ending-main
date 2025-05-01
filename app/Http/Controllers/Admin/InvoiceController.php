<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of all invoices
     */
    public function index()
    {
        $invoices = Invoice::with(['order.user'])->latest()->paginate(15);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['order.items', 'order.user']);

        return view('invoices.detail', compact('invoice'));
    }

    /**
     * Update the invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,waiting_confirmation,paid,cancelled',
        ]);

        $invoice->status = $validated['status'];

        // If invoice is marked as paid, update the payment date
        if ($validated['status'] === 'paid' && !$invoice->payment_date) {
            $invoice->payment_date = now();

            // Also update the order status to completed if it's in processing
            if ($invoice->order->status === 'processing') {
                $invoice->order->status = 'completed';
                $invoice->order->save();
            }
        }

        $invoice->save();

        return back()->with('success', 'Status invoice berhasil diperbarui.');
    }

    /**
     * Show a list of invoices with specific status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->query('status');

        $query = Invoice::with(['order.user'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $invoices = $query->paginate(15)->withQueryString();

        return view('admin.invoices.index', compact('invoices', 'status'));
    }
}
