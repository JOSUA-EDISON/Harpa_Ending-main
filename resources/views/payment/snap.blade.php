@extends('layouts.landing')

@section('title', 'Pembayaran - Harpa')

@push('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .payment-section {
            padding: 50px 0;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .payment-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .payment-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .payment-card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            font-weight: 600;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .payment-card-header i {
            margin-right: 10px;
            color: #6777ef;
            font-size: 18px;
        }

        .payment-card-body {
            padding: 20px;
        }

        .order-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-details-table th {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .order-details-table td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-details-table tr:last-child td {
            border-bottom: none;
        }

        .payment-step {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .payment-step:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .payment-step-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .payment-step-title i {
            margin-right: 10px;
            background-color: #6777ef;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .payment-method-card {
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            padding: 15px;
            width: calc(50% - 15px);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover {
            border-color: #6777ef;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 119, 239, 0.1);
        }

        .payment-method-card.selected {
            border-color: #6777ef;
            background-color: #f0f3ff;
        }

        .payment-method-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .payment-method-card-header img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 10px;
        }

        .payment-actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(to right, #6777ef, #3d4eda);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 119, 239, 0.3);
        }

        .btn-secondary {
            background-color: white;
            border: 1px solid #e4e6fc;
            color: #6c757d;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            border-color: #6777ef;
            color: #6777ef;
        }

        .btn i {
            margin-right: 8px;
        }

        @media (max-width: 767px) {
            .payment-method-card {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="payment-section">
    <div class="payment-container">
        <div class="payment-card">
            <div class="payment-card-header">
                <i class='bx bx-receipt'></i> Ringkasan Pesanan
            </div>
            <div class="payment-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p><strong>Status:</strong> Menunggu Pembayaran</p>
                    </div>
                </div>

                <table class="order-details-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @if($order->shipping_cost > 0)
                        <tr>
                            <td>Ongkos Kirim ({{ $order->shipping_service }})</td>
                            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            <td>1</td>
                            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="payment-card">
            <div class="payment-card-header">
                <i class='bx bx-credit-card'></i> Pembayaran
            </div>
            <div class="payment-card-body">
                <div class="payment-step">
                    <div class="payment-step-title">
                        <i class='bx bx-check'></i> Pilih Metode Pembayaran
                    </div>
                    <p>Silakan klik tombol "Bayar Sekarang" untuk memilih metode pembayaran melalui Midtrans.</p>

                    <div class="payment-actions">
                        <button id="pay-button" class="btn btn-primary">
                            <i class='bx bx-credit-card'></i> Bayar Sekarang
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="alert alert-info" role="alert">
                        <i class='bx bx-info-circle mr-2'></i>
                        Pesanan akan otomatis dibatalkan jika pembayaran tidak dilakukan dalam 1x24 jam.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function() {
        // Trigger snap popup
        snap.pay('{{ $snapToken }}', {
            // Optional
            onSuccess: function(result) {
                // Handle success - redirect to success page
                window.location.href = "{{ route('payment.success', ['order_id' => $order->id]) }}";
            },
            onPending: function(result) {
                // Handle pending - redirect to pending page
                window.location.href = "{{ route('payment.pending', ['order_id' => $order->id]) }}";
            },
            onError: function(result) {
                // Handle error - redirect to error page
                window.location.href = "{{ route('payment.error', ['order_id' => $order->id]) }}";
            },
            onClose: function() {
                // User closed the popup without finishing the payment
                alert('Anda belum menyelesaikan pembayaran. Silakan klik "Bayar Sekarang" untuk mencoba lagi.');
            }
        });
    };
</script>
@endpush
