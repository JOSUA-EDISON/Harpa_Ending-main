@extends('layouts.app')

@section('title', 'Manajemen Invoice')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/invoices/invoices.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Invoice</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Daftar Invoice</h2>
            <p class="section-lead">
                Kelola semua invoice pelanggan.
            </p>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Invoice</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.invoices.filter') }}" method="GET" id="filter-form">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status Pembayaran</label>
                                            <select class="form-control" name="status"
                                                onchange="document.getElementById('filter-form').submit()">
                                                <option value="">Semua Status</option>
                                                <option value="unpaid"
                                                    {{ isset($status) && $status == 'unpaid' ? 'selected' : '' }}>Belum
                                                    Dibayar</option>
                                                <option value="waiting_confirmation"
                                                    {{ isset($status) && $status == 'waiting_confirmation' ? 'selected' : '' }}>
                                                    Menunggu Konfirmasi</option>
                                                <option value="paid"
                                                    {{ isset($status) && $status == 'paid' ? 'selected' : '' }}>Lunas
                                                </option>
                                                <option value="cancelled"
                                                    {{ isset($status) && $status == 'cancelled' ? 'selected' : '' }}>
                                                    Dibatalkan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-group mb-0 w-100">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-filter mr-1"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Invoice</h4>
                            <div class="card-header-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari invoice..." id="searchInput">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped table-md" id="invoiceTable">
                                    <thead>
                                        <tr>
                                            <th>No. Invoice</th>
                                            <th>No. Pesanan</th>
                                            <th>Pelanggan</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="font-weight-bold text-primary">
                                                        {{ $invoice->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->order->order_number }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar mr-2">
                                                            <div class="avatar-initial rounded-circle bg-light">
                                                                {{ substr($invoice->order->user->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div>{{ $invoice->order->user->name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                                <td class="font-weight-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($invoice->status == 'unpaid')
                                                        <span class="status-badge status-unpaid">Belum Dibayar</span>
                                                    @elseif ($invoice->status == 'waiting_confirmation')
                                                        <span class="status-badge status-waiting">Menunggu Konfirmasi</span>
                                                    @elseif ($invoice->status == 'paid')
                                                        <span class="status-badge status-paid">Lunas</span>
                                                    @elseif ($invoice->status == 'cancelled')
                                                        <span class="status-badge status-cancelled">Dibatalkan</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                            class="btn btn-sm btn-info" title="Detail Invoice">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                        <a href="{{ route('admin.orders.show', $invoice->order) }}"
                                                            class="btn btn-sm btn-primary" title="Detail Pesanan">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                        <a href="{{ route('admin.invoices.print', $invoice) }}"
                                                            class="btn btn-sm btn-light" target="_blank" title="Cetak Invoice">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        @if($invoice->status == 'waiting_confirmation')
                                                        <form action="{{ route('admin.invoices.update-status', $invoice) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="paid">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Konfirmasi Pembayaran" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </div>
                                                        <h2>Tidak ada data invoice</h2>
                                                        <p class="lead">
                                                            Tidak ada invoice yang ditemukan sesuai dengan filter yang dipilih.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="float-right mt-4">
                                {{ $invoices->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Simple search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('invoiceTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            let found = false;
            const cells = rows[i].getElementsByTagName('td');

            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.indexOf(searchValue) > -1) {
                    found = true;
                    break;
                }
            }

            if (found) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
</script>
@endpush
