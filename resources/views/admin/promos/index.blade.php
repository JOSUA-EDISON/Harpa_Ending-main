@extends('layouts.app')

@section('title', 'Manajemen Promo')

@push('css')
<style>
    .card-promo {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card-promo:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .promo-item {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        position: relative;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .promo-item:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .promo-header {
        padding: 15px;
        background: linear-gradient(135deg, #6777ef, #3abaf4);
        color: white;
        position: relative;
    }

    .promo-title {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .promo-code {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-bottom: 10px;
    }

    .promo-date {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .promo-badge {
        position: absolute;
        right: 15px;
        top: 15px;
        background: #63ed7a;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .promo-badge.expired {
        background: #fc544b;
    }

    .promo-badge.coming-soon {
        background: #ffa426;
    }

    .promo-content {
        padding: 15px;
        background: white;
    }

    .promo-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .promo-info-item {
        text-align: center;
        flex: 1;
    }

    .promo-info-item:not(:last-child) {
        border-right: 1px solid #f1f1f1;
    }

    .promo-info-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .promo-info-value {
        font-weight: 700;
        color: #333;
    }

    .promo-info-value.discount {
        color: #6777ef;
    }

    .promo-info-value.usage {
        color: #63ed7a;
    }

    .promo-description {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .promo-actions {
        display: flex;
        gap: 10px;
    }

    .btn-promo {
        flex: 1;
        border-radius: 5px;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 0.8rem;
        text-align: center;
    }

    .btn-edit-promo {
        background: #6777ef;
        color: white;
    }

    .btn-delete-promo {
        background: #fc544b;
        color: white;
    }

    .btn-create-promo {
        padding: 10px 20px;
        border-radius: 30px;
        background-image: linear-gradient(135deg, #6777ef, #3abaf4);
        color: white;
        font-weight: 600;
        border: none;
        box-shadow: 0 5px 15px rgba(103, 119, 239, 0.2);
        transition: all 0.3s ease;
    }

    .btn-create-promo:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.3);
    }

    .btn-create-promo i {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Manajemen Promo</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Manajemen Promo</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Kelola Promo & Diskon</h2>
        <p class="section-lead">Kelola semua promo dan diskon untuk pelanggan Anda.</p>

        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card card-promo">
                    <div class="card-header">
                        <h4>Tambah Promo Baru</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Promo</label>
                            <input type="text" class="form-control" placeholder="Masukkan nama promo">
                        </div>

                        <div class="form-group">
                            <label>Kode Promo</label>
                            <input type="text" class="form-control" placeholder="Contoh: SUMMER2023">
                        </div>

                        <div class="form-group">
                            <label>Jenis Diskon</label>
                            <select class="form-control">
                                <option>Persentase</option>
                                <option>Nominal Tetap</option>
                                <option>Gratis Ongkir</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nilai Diskon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-percentage"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" placeholder="Masukkan nilai diskon">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Minimal Pembelian</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        Rp
                                    </div>
                                </div>
                                <input type="number" class="form-control" placeholder="Masukkan minimal pembelian">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tanggal Berakhir</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" rows="3" placeholder="Deskripsi promo"></textarea>
                        </div>

                        <button class="btn btn-create-promo btn-block">
                            <i class="fas fa-plus"></i> Buat Promo Baru
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card card-promo">
                    <div class="card-header">
                        <h4>Daftar Promo Aktif</h4>
                        <div class="card-header-action">
                            <div class="btn-group">
                                <button class="btn btn-primary">Semua</button>
                                <button class="btn">Aktif</button>
                                <button class="btn">Berakhir</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="promo-item">
                            <div class="promo-header">
                                <div class="promo-title">Diskon Akhir Tahun</div>
                                <div class="promo-code">YEAREND2023</div>
                                <div class="promo-date">Berlaku hingga 31 Des 2023</div>
                                <span class="promo-badge">Aktif</span>
                            </div>
                            <div class="promo-content">
                                <div class="promo-info">
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Jenis</div>
                                        <div class="promo-info-value">Persentase</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Diskon</div>
                                        <div class="promo-info-value discount">20%</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Min. Belanja</div>
                                        <div class="promo-info-value">Rp 150.000</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Penggunaan</div>
                                        <div class="promo-info-value usage">124x</div>
                                    </div>
                                </div>
                                <div class="promo-description">
                                    Diskon 20% untuk semua produk dengan minimal belanja Rp 150.000
                                </div>
                                <div class="promo-actions">
                                    <a href="#" class="btn btn-promo btn-edit-promo">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-promo btn-delete-promo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="promo-item">
                            <div class="promo-header">
                                <div class="promo-title">Gratis Ongkir</div>
                                <div class="promo-code">FREESHIP</div>
                                <div class="promo-date">Berlaku hingga 15 Nov 2023</div>
                                <span class="promo-badge">Aktif</span>
                            </div>
                            <div class="promo-content">
                                <div class="promo-info">
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Jenis</div>
                                        <div class="promo-info-value">Gratis Ongkir</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Diskon</div>
                                        <div class="promo-info-value discount">100%</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Min. Belanja</div>
                                        <div class="promo-info-value">Rp 200.000</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Penggunaan</div>
                                        <div class="promo-info-value usage">75x</div>
                                    </div>
                                </div>
                                <div class="promo-description">
                                    Gratis ongkir untuk pembelian dengan minimum transaksi Rp 200.000
                                </div>
                                <div class="promo-actions">
                                    <a href="#" class="btn btn-promo btn-edit-promo">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-promo btn-delete-promo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="promo-item">
                            <div class="promo-header">
                                <div class="promo-title">Promo Pembelian Pertama</div>
                                <div class="promo-code">NEWUSER</div>
                                <div class="promo-date">Tidak ada batas waktu</div>
                                <span class="promo-badge">Aktif</span>
                            </div>
                            <div class="promo-content">
                                <div class="promo-info">
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Jenis</div>
                                        <div class="promo-info-value">Nominal Tetap</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Diskon</div>
                                        <div class="promo-info-value discount">Rp 50.000</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Min. Belanja</div>
                                        <div class="promo-info-value">Rp 100.000</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Penggunaan</div>
                                        <div class="promo-info-value usage">210x</div>
                                    </div>
                                </div>
                                <div class="promo-description">
                                    Potongan Rp 50.000 untuk pengguna baru dengan minimum belanja Rp 100.000
                                </div>
                                <div class="promo-actions">
                                    <a href="#" class="btn btn-promo btn-edit-promo">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-promo btn-delete-promo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="promo-item">
                            <div class="promo-header">
                                <div class="promo-title">Diskon Musim Panas</div>
                                <div class="promo-code">SUMMER2023</div>
                                <div class="promo-date">Berlaku: 1 Jun - 31 Agt 2023</div>
                                <span class="promo-badge expired">Berakhir</span>
                            </div>
                            <div class="promo-content">
                                <div class="promo-info">
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Jenis</div>
                                        <div class="promo-info-value">Persentase</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Diskon</div>
                                        <div class="promo-info-value discount">15%</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Min. Belanja</div>
                                        <div class="promo-info-value">Rp 100.000</div>
                                    </div>
                                    <div class="promo-info-item">
                                        <div class="promo-info-label">Penggunaan</div>
                                        <div class="promo-info-value usage">183x</div>
                                    </div>
                                </div>
                                <div class="promo-description">
                                    Diskon musim panas 15% untuk semua produk
                                </div>
                                <div class="promo-actions">
                                    <a href="#" class="btn btn-promo btn-edit-promo">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-promo btn-delete-promo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
