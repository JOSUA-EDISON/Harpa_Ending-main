@extends('layouts.landing')

@section('title', 'Pembayaran Tertunda - Harpa')

@push('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .pending-section {
            padding: 50px 0;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pending-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .pending-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            text-align: center;
            padding: 40px;
        }

        .pending-icon {
            width: 100px;
            height: 100px;
            background-color: #ffa426;
            color: white;
            font-size: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .pending-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .pending-message {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .instruction-box {
            background-color: #fff8e1;
            border-left: 4px solid #ffa426;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
            border-radius: 5px;
        }

        .instruction-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .instruction-list {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .instruction-list li {
            margin-bottom: 8px;
        }

        .instruction-list li:last-child {
            margin-bottom: 0;
        }

        .btn-primary {
            background: linear-gradient(to right, #6777ef, #3d4eda);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 119, 239, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background-color: white;
            border: 1px solid #e4e6fc;
            color: #6c757d;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            border-color: #6777ef;
            color: #6777ef;
            text-decoration: none;
        }

        .btn i {
            margin-right: 8px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
    </style>
@endpush

@section('content')
<div class="pending-section">
    <div class="pending-container">
        <div class="pending-card">
            <div class="pending-icon">
                <i class='bx bx-time'></i>
            </div>
            <h1 class="pending-title">Pembayaran Tertunda</h1>
            <p class="pending-message">
                Pesanan Anda telah dibuat, namun pembayaran Anda masih menunggu konfirmasi.
            </p>

            <div class="order-details">
                <div class="detail-item">
                    <span>Order Number:</span>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="detail-item">
                    <span>Tanggal Order:</span>
                    <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="detail-item">
                    <span>Status:</span>
                    <span class="text-warning">Menunggu Pembayaran</span>
                </div>
            </div>

            <div class="instruction-box">
                <h5 class="instruction-title">Petunjuk:</h5>
                <ul class="instruction-list">
                    <li>Silakan selesaikan pembayaran sesuai dengan instruksi yang diberikan pada metode pembayaran.</li>
                    <li>Jangan tutup browser atau aplikasi bank Anda selama proses pembayaran berlangsung.</li>
                    <li>Setelah pembayaran berhasil, status pesanan Anda akan otomatis diperbarui.</li>
                    <li>Pembayaran akan kedaluwarsa dalam 24 jam jika tidak diselesaikan.</li>
                </ul>
            </div>

            <div class="actions">
                <a href="{{ route('orders.show', $order) }}" class="btn-primary">
                    <i class='bx bx-package'></i> Lihat Detail Pesanan
                </a>
                <a href="{{ route('orders.index') }}" class="btn-secondary">
                    <i class='bx bx-list-ul'></i> Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
