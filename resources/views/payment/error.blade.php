@extends('layouts.landing')

@section('title', 'Pembayaran Gagal - Harpa')

@push('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .error-section {
            padding: 50px 0;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .error-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            text-align: center;
            padding: 40px;
        }

        .error-icon {
            width: 100px;
            height: 100px;
            background-color: #fc544b;
            color: white;
            font-size: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .error-message {
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

        .error-box {
            background-color: #feeceb;
            border-left: 4px solid #fc544b;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
            border-radius: 5px;
        }

        .error-box-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
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
<div class="error-section">
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class='bx bx-x'></i>
            </div>
            <h1 class="error-title">Pembayaran Gagal</h1>
            <p class="error-message">
                Maaf, terjadi kesalahan saat memproses pembayaran Anda. Pembayaran tidak berhasil diproses.
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
                    <span class="text-danger">Pembayaran Gagal</span>
                </div>
            </div>

            <div class="error-box">
                <h5 class="error-box-title">Kemungkinan Penyebab:</h5>
                <ul>
                    <li>Saldo kartu kredit/debit Anda tidak mencukupi.</li>
                    <li>Bank Anda menolak transaksi karena alasan keamanan.</li>
                    <li>Terjadi masalah teknis pada gateway pembayaran.</li>
                    <li>Koneksi internet terputus saat proses pembayaran berlangsung.</li>
                </ul>
            </div>

            <div class="actions">
                <a href="{{ route('payment.show', $order) }}" class="btn-primary">
                    <i class='bx bx-refresh'></i> Coba Lagi
                </a>
                <a href="{{ route('orders.show', $order) }}" class="btn-secondary">
                    <i class='bx bx-package'></i> Lihat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
