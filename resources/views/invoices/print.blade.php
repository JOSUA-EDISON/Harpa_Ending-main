<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/invoices/invoices.css') }}">
</head>
<body>
    <button class="print-button" onclick="window.print()">Cetak Invoice</button>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">Gallery Bejo</div>
            <div>
                <div><strong>Tanggal:</strong> {{ $invoice->created_at->format('d M Y') }}</div>
                <div><strong>Jatuh Tempo:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</div>
            </div>
        </div>

        <div class="invoice-title">
            <h1>INVOICE</h1>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
        </div>

        <div class="invoice-info">
            <div class="invoice-info-section">
                <h4>Dari:</h4>
                <div class="invoice-info-item">
                    <div class="value">Gallery Bejo</div>
                    <div>Jl. Singgalang No.5, Pisang Candi</div>
                    <div>Kec. Sukun, Kota Malang</div>
                    <div>Jawa Timur 65146</div>
                    <div>Email: info@gallerybejo.com</div>
                    <div>Telp: (0341) 123456</div>
                </div>
            </div>

            <div class="invoice-info-section">
                <h4>Kepada:</h4>
                <div class="invoice-info-item">
                    <div class="value">{{ $invoice->order->user->name }}</div>
                    <div>{{ $invoice->order->shipping_address }}</div>
                    <div>Telp: {{ $invoice->order->phone_number }}</div>
                </div>
            </div>
        </div>

        <div class="invoice-items">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-footer">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="invoice-total">
            <div>Status Pembayaran:
                @if ($invoice->status == 'unpaid')
                    <span class="status-badge status-unpaid">Belum Dibayar</span>
                @elseif ($invoice->status == 'waiting_confirmation')
                    <span class="status-badge status-waiting">Menunggu Konfirmasi</span>
                @elseif ($invoice->status == 'paid')
                    <span class="status-badge status-paid">Lunas</span>
                @elseif ($invoice->status == 'cancelled')
                    <span class="status-badge status-cancelled">Dibatalkan</span>
                @endif
            </div>
            <div class="total-amount">Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
        </div>

        <div class="invoice-notes mt-4">
            <h5>Catatan:</h5>
            <p>Terima kasih telah berbelanja di Gallery Bejo. Untuk pembayaran, silakan transfer ke rekening berikut:</p>
            <ul>
                <li>Bank BCA: 1234567890 a.n. Gallery Bejo</li>
                <li>Bank Mandiri: 0987654321 a.n. Gallery Bejo</li>
            </ul>

            @if ($invoice->status == 'unpaid')
                <p>Harap lakukan pembayaran sebelum {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}.</p>
            @endif
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Gallery Bejo. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
