<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders
     */
    public function index()
    {
        $orders = Order::with(['user', 'invoice'])->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'invoice', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Show a list of orders with specific status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with(['user', 'invoice'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    /**
     * Export orders to CSV file
     */
    public function export(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with(['user', 'invoice'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        $fileName = 'orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'No. Pesanan',
            'Pelanggan',
            'Email',
            'Tanggal',
            'Total',
            'Status Pesanan',
            'Status Pembayaran',
            'Alamat Pengiriman',
            'Nomor Telepon'
        ];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['No. Pesanan'] = $order->order_number;
                $row['Pelanggan'] = $order->user->name;
                $row['Email'] = $order->user->email;
                $row['Tanggal'] = $order->created_at->format('d M Y H:i');
                $row['Total'] = number_format($order->total_amount, 0, ',', '.');

                // Status Pesanan
                if ($order->status == 'pending') {
                    $row['Status Pesanan'] = 'Menunggu';
                } elseif ($order->status == 'processing') {
                    $row['Status Pesanan'] = 'Diproses';
                } elseif ($order->status == 'completed') {
                    $row['Status Pesanan'] = 'Selesai';
                } elseif ($order->status == 'cancelled') {
                    $row['Status Pesanan'] = 'Dibatalkan';
                }

                // Status Pembayaran
                if ($order->invoice->status == 'unpaid') {
                    $row['Status Pembayaran'] = 'Belum Dibayar';
                } elseif ($order->invoice->status == 'waiting_confirmation') {
                    $row['Status Pembayaran'] = 'Menunggu Konfirmasi';
                } elseif ($order->invoice->status == 'paid') {
                    $row['Status Pembayaran'] = 'Lunas';
                } elseif ($order->invoice->status == 'cancelled') {
                    $row['Status Pembayaran'] = 'Dibatalkan';
                }

                $row['Alamat Pengiriman'] = $order->shipping_address;
                $row['Nomor Telepon'] = $order->phone_number;

                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
