@extends('layouts.app')

@section('title', 'Add New Product')

@push('css')
<link rel="stylesheet" href="{{ asset('css/products/products.css') }}">
@endpush

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Products Management</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title m-0">Add New Product</h2>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-icon icon-left">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Price</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control currency-input @error('price') is-invalid @enderror" name="price" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" required placeholder="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Format contoh: 150.000 (tanpa Rp dan tanpa desimal)</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" style="height: 150px">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan deskripsi produk dan spesifikasi di sini.</small>

                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Template Deskripsi Produk</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Template untuk:</span>
                                        <select id="template-selector" class="form-control form-control-sm" style="width: auto;">
                                            <option value="harmonika">Harmonika Modern</option>
                                            <option value="traditional">Harpa Mulut Tradisional</option>
                                        </select>
                                    </div>
                                    <div id="harmonika-template">
                                        <button type="button" class="btn btn-sm btn-primary mb-2" id="insert-template">Masukkan Template</button>
                                        <div class="template-preview p-2 bg-light" style="font-size: 0.85rem; max-height: 300px; overflow-y: auto;">

                                            <div class="form-group">
                                                <label><strong>Jenis Harmonika:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis[]" value="Diatonik"> Diatonik</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis[]" value="Kromatik"> Kromatik</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis[]" value="Tremolo"> Tremolo</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Jenis lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Jumlah Lubang (Holes):</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="lubang[]" value="10"> 10</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="lubang[]" value="12"> 12</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="lubang[]" value="16"> 16</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="lubang[]" value="24"> 24</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Jumlah lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Nada Dasar (Key):</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="nada[]" value="C Mayor"> C Mayor</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="nada[]" value="G Mayor"> G Mayor</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="nada[]" value="A Minor"> A Minor</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Nada lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Material Comb (Badan Harmonika):</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="material[]" value="Plastik ABS"> Plastik ABS</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="material[]" value="Kayu"> Kayu</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="material[]" value="Logam"> Logam</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Material lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Bahan Reed Plate (Lidah Nada):</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="reed[]" value="Kuningan"> Kuningan</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="reed[]" value="Fosfor Perunggu"> Fosfor Perunggu</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="reed[]" value="Stainless Steel"> Stainless Steel</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Bahan lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Cover Plate:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="cover[]" value="Stainless steel"> Stainless steel</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="cover[]" value="Chrome-plated"> Chrome-plated</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="cover[]" value="Aluminium"> Aluminium</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Material lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Ukuran:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div>Panjang ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="10-12"> cm,</div>
                                                    <div class="ml-2">Lebar ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="2.5-3.5"> cm,</div>
                                                    <div class="ml-2">Tinggi ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="2-3"> cm</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Berat:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div>± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="80-150"> gram</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Sistem Nada:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="sistem[]" value="Richter"> Richter</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="sistem[]" value="Solo Tuning"> Solo Tuning</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="sistem[]" value="Asian Tuning"> Asian Tuning</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Sistem lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Fitur Tambahan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="fitur[]" value="Anti-slip grip"> Anti-slip grip</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="fitur[]" value="Lubang ventilasi"> Lubang ventilasi</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Fitur lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Kelengkapan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="kelengkapan[]" value="Hardcase"> Hardcase</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="kelengkapan[]" value="Soft pouch"> Soft pouch</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="kelengkapan[]" value="Kain pembersih"> Kain pembersih</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="kelengkapan[]" value="Buku panduan"> Buku panduan</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Kelengkapan lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Rekomendasi Penggunaan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="rekomendasi[]" value="Pemula"> Pemula</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="rekomendasi[]" value="Menengah"> Menengah</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="rekomendasi[]" value="Profesional"> Profesional</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Rekomendasi lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Warna Tersedia:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="warna[]" value="Hitam"> Hitam</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="warna[]" value="Silver"> Silver</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="warna[]" value="Emas"> Emas</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="warna[]" value="Merah"> Merah</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="warna[]" value="Biru"> Biru</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Warna lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Brand/Merek:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="brand[]" value="Suzuki"> Suzuki</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="brand[]" value="Hohner"> Hohner</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="brand[]" value="Easttop"> Easttop</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Brand lainnya">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="traditional-template" style="display: none;">
                                        <button type="button" class="btn btn-sm btn-primary mb-2" id="insert-template-traditional">Masukkan Template</button>
                                        <div class="template-preview p-2 bg-light" style="font-size: 0.85rem; max-height: 300px; overflow-y: auto;">

                                            <div class="form-group">
                                                <label><strong>Jenis Harpa Mulut:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis_trad[]" value="Karinding"> Karinding</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis_trad[]" value="Genggong"> Genggong</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="jenis_trad[]" value="Jaw harp"> Jaw harp</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Jenis lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Bahan Utama:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="bahan_trad[]" value="Bambu tua"> Bambu tua</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="bahan_trad[]" value="Besi"> Besi</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="bahan_trad[]" value="Kuningan"> Kuningan</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="bahan_trad[]" value="Kayu keras"> Kayu keras</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Bahan lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Asal Budaya/Daerah:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="asal_trad[]" value="Jawa Barat"> Jawa Barat</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="asal_trad[]" value="Bali"> Bali</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="asal_trad[]" value="Kalimantan"> Kalimantan</div>
                                                    <div class="form-check mr-3"><input type="checkbox" class="form-check-input" name="asal_trad[]" value="Papua"> Papua</div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Daerah lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Ukuran:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div>Panjang ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="10-15"> cm,</div>
                                                    <div class="ml-2">Lebar ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="2-3"> cm,</div>
                                                    <div class="ml-2">Ketebalan ± <input type="text" class="form-control form-control-sm d-inline" style="width: 80px" value="0.5-1"> cm</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Teknik Permainan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="teknik_trad[]" value="Ditempelkan ke bibir/mulut">
                                                        Ditempelkan ke bibir/mulut
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="teknik_trad[]" value="Dipetik dengan jari">
                                                        Dipetik dengan jari
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Teknik lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Ciri Suara:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="suara_trad[]" value="Getaran alami">
                                                        Getaran alami
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="suara_trad[]" value="Nada rendah">
                                                        Nada rendah
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="suara_trad[]" value="Nada tinggi">
                                                        Nada tinggi
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="suara_trad[]" value="Resonansi unik">
                                                        Resonansi unik
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Ciri lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Manfaat:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="manfaat_trad[]" value="Media relaksasi">
                                                        Media relaksasi
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="manfaat_trad[]" value="Kesenian daerah">
                                                        Kesenian daerah
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="manfaat_trad[]" value="Upacara adat">
                                                        Upacara adat
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="manfaat_trad[]" value="Pendidikan budaya">
                                                        Pendidikan budaya
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Manfaat lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Rekomendasi Pengguna:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="rekomendasi_trad[]" value="Kolektor alat musik">
                                                        Kolektor alat musik
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="rekomendasi_trad[]" value="Pengajar seni dan budaya">
                                                        Pengajar seni dan budaya
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="rekomendasi_trad[]" value="Pecinta musik etnik">
                                                        Pecinta musik etnik
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Rekomendasi lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Warna & Tampilan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="warna_trad[]" value="Warna alami bambu">
                                                        Warna alami bambu
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="warna_trad[]" value="Warna alami logam">
                                                        Warna alami logam
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="warna_trad[]" value="Ukiran motif khas">
                                                        Ukiran motif khas
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Tampilan lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Perawatan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="perawatan_trad[]" value="Simpan di tempat kering">
                                                        Simpan di tempat kering
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="perawatan_trad[]" value="Bersihkan dengan kain kering">
                                                        Bersihkan dengan kain kering
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Perawatan lainnya">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><strong>Kemasan:</strong></label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="kemasan_trad[]" value="Kemasan anyaman">
                                                        Kemasan anyaman
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="kemasan_trad[]" value="Kain tradisional">
                                                        Kain tradisional
                                                    </div>
                                                    <div class="form-check mr-3">
                                                        <input type="checkbox" class="form-check-input" name="kemasan_trad[]" value="Kotak kayu">
                                                        Kotak kayu
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" style="width: 150px" placeholder="Kemasan lainnya">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Stock Management</label>
                        <div class="col-sm-9">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="stock_quantity">Stock Quantity</label>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0">
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch mt-4">
                                        <input type="checkbox" class="custom-control-input" name="track_inventory" id="track_inventory" value="1" {{ old('track_inventory', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="track_inventory">Track Inventory</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" required>
                                <label class="custom-file-label" for="image">Choose file</label>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <img id="image-preview" src="#" alt="Image Preview" class="img-thumbnail" style="max-height: 200px; display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Featured</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" class="custom-control-input" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="featured">Show in featured section on homepage</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary">Create Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light ml-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Preview image before upload
        $("#image").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#image-preview").attr('src', event.target.result);
                    $("#image-preview").css('display', 'block');
                }
                reader.readAsDataURL(file);
            }
        });

        // Display selected filename
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // Currency formatting for rupiah
        $(".currency-input").on("input", function() {
            // Get the input value and remove non-digit characters
            let value = $(this).val().replace(/[^\d]/g, "");

            // Format with thousands separator
            if (value !== "") {
                let formattedValue = Number(value).toLocaleString('id-ID');
                $(this).val(formattedValue);
            }
        });

        // Toggle stock quantity input based on inventory tracking
        $("#track_inventory").change(function() {
            if($(this).is(":checked")) {
                $("#stock_quantity").prop("disabled", false);
            } else {
                $("#stock_quantity").prop("disabled", true);
            }
        });

        // Template selector toggle
        $("#template-selector").change(function() {
            const selectedTemplate = $(this).val();
            if (selectedTemplate === 'harmonika') {
                $("#harmonika-template").show();
                $("#traditional-template").hide();
            } else if (selectedTemplate === 'traditional') {
                $("#harmonika-template").hide();
                $("#traditional-template").show();
            }
        });

        // Product description template
        $("#insert-template").click(function() {
            const templateType = $("#template-selector").val();
            let templateText = '';

            if (templateType === 'harmonika') {
                // Collect all checked items and input values
                let jenisValues = [];
                $("input[name='jenis[]']:checked").each(function() {
                    jenisValues.push($(this).val());
                });
                let jenisCustom = $("input[name='jenis[]']").closest(".d-flex").find("input[type='text']").val();
                if (jenisCustom) jenisValues.push(jenisCustom);

                let lubangValues = [];
                $("input[name='lubang[]']:checked").each(function() {
                    lubangValues.push($(this).val());
                });
                let lubangCustom = $("input[name='lubang[]']").closest(".d-flex").find("input[type='text']").val();
                if (lubangCustom) lubangValues.push(lubangCustom);

                let nadaValues = [];
                $("input[name='nada[]']:checked").each(function() {
                    nadaValues.push($(this).val());
                });
                let nadaCustom = $("input[name='nada[]']").closest(".d-flex").find("input[type='text']").val();
                if (nadaCustom) nadaValues.push(nadaCustom);

                let materialValues = [];
                $("input[name='material[]']:checked").each(function() {
                    materialValues.push($(this).val());
                });
                let materialCustom = $("input[name='material[]']").closest(".d-flex").find("input[type='text']").val();
                if (materialCustom) materialValues.push(materialCustom);

                let reedValues = [];
                $("input[name='reed[]']:checked").each(function() {
                    reedValues.push($(this).val());
                });
                let reedCustom = $("input[name='reed[]']").closest(".d-flex").find("input[type='text']").val();
                if (reedCustom) reedValues.push(reedCustom);

                let coverValues = [];
                $("input[name='cover[]']:checked").each(function() {
                    coverValues.push($(this).val());
                });
                let coverCustom = $("input[name='cover[]']").closest(".d-flex").find("input[type='text']").val();
                if (coverCustom) coverValues.push(coverCustom);

                // Get ukuran values
                let panjang = $("label:contains('Ukuran:')").closest(".form-group").find("input").eq(0).val();
                let lebar = $("label:contains('Ukuran:')").closest(".form-group").find("input").eq(1).val();
                let tinggi = $("label:contains('Ukuran:')").closest(".form-group").find("input").eq(2).val();

                // Get berat value
                let berat = $("label:contains('Berat:')").closest(".form-group").find("input").val();

                let sistemValues = [];
                $("input[name='sistem[]']:checked").each(function() {
                    sistemValues.push($(this).val());
                });
                let sistemCustom = $("input[name='sistem[]']").closest(".d-flex").find("input[type='text']").val();
                if (sistemCustom) sistemValues.push(sistemCustom);

                let fiturValues = [];
                $("input[name='fitur[]']:checked").each(function() {
                    fiturValues.push($(this).val());
                });
                let fiturCustom = $("input[name='fitur[]']").closest(".d-flex").find("input[type='text']").val();
                if (fiturCustom) fiturValues.push(fiturCustom);

                let kelengkapanValues = [];
                $("input[name='kelengkapan[]']:checked").each(function() {
                    kelengkapanValues.push($(this).val());
                });
                let kelengkapanCustom = $("input[name='kelengkapan[]']").closest(".d-flex").find("input[type='text']").val();
                if (kelengkapanCustom) kelengkapanValues.push(kelengkapanCustom);

                let rekomendasiValues = [];
                $("input[name='rekomendasi[]']:checked").each(function() {
                    rekomendasiValues.push($(this).val());
                });
                let rekomendasiCustom = $("input[name='rekomendasi[]']").closest(".d-flex").find("input[type='text']").val();
                if (rekomendasiCustom) rekomendasiValues.push(rekomendasiCustom);

                let warnaValues = [];
                $("input[name='warna[]']:checked").each(function() {
                    warnaValues.push($(this).val());
                });
                let warnaCustom = $("input[name='warna[]']").closest(".d-flex").find("input[type='text']").val();
                if (warnaCustom) warnaValues.push(warnaCustom);

                let brandValues = [];
                $("input[name='brand[]']:checked").each(function() {
                    brandValues.push($(this).val());
                });
                let brandCustom = $("input[name='brand[]']").closest(".d-flex").find("input[type='text']").val();
                if (brandCustom) brandValues.push(brandCustom);

                // Generate template text
                templateText = `Jenis Harmonika: ${jenisValues.length > 0 ? jenisValues.join(', ') : '[Pilih jenis]'}
Jumlah Lubang (Holes): ${lubangValues.length > 0 ? lubangValues.join(', ') : '[Pilih jumlah]'} lubang
Nada Dasar (Key): ${nadaValues.length > 0 ? nadaValues.join(', ') : '[Pilih nada]'}
Material Comb (Badan Harmonika): ${materialValues.length > 0 ? materialValues.join(', ') : '[Pilih material]'}
Bahan Reed Plate (Lidah Nada): ${reedValues.length > 0 ? reedValues.join(', ') : '[Pilih bahan]'}
Cover Plate: ${coverValues.length > 0 ? coverValues.join(', ') : '[Pilih cover]'}
Ukuran: Panjang ± ${panjang} cm, Lebar ± ${lebar} cm, Tinggi ± ${tinggi} cm
Berat: ± ${berat} gram
Sistem Nada: ${sistemValues.length > 0 ? sistemValues.join(', ') : '[Pilih sistem]'}
Fitur Tambahan: ${fiturValues.length > 0 ? fiturValues.join(', ') : '[Pilih fitur]'}
Kelengkapan: ${kelengkapanValues.length > 0 ? kelengkapanValues.join(', ') : '[Pilih kelengkapan]'}
Rekomendasi Penggunaan: ${rekomendasiValues.length > 0 ? rekomendasiValues.join(', ') : '[Pilih rekomendasi]'}
Warna Tersedia: ${warnaValues.length > 0 ? warnaValues.join(', ') : '[Pilih warna]'}
Brand/Merek: ${brandValues.length > 0 ? brandValues.join(', ') : '[Pilih brand]'}`;
            }

            // Insert template
            const descriptionField = $("#description");
            const currentText = descriptionField.val();

            // Ask for confirmation if there's already text
            if (currentText && currentText.trim() !== '') {
                if (confirm('Mengganti teks deskripsi yang ada dengan template?')) {
                    descriptionField.val(templateText);
                }
            } else {
                descriptionField.val(templateText);
            }
        });

        // Traditional product description template
        $("#insert-template-traditional").click(function() {
            let templateText = '';

            // Collect all checked items and input values
            let jenisValues = [];
            $("input[name='jenis_trad[]']:checked").each(function() {
                jenisValues.push($(this).val());
            });
            let jenisCustom = $("input[name='jenis_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (jenisCustom) jenisValues.push(jenisCustom);

            let bahanValues = [];
            $("input[name='bahan_trad[]']:checked").each(function() {
                bahanValues.push($(this).val());
            });
            let bahanCustom = $("input[name='bahan_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (bahanCustom) bahanValues.push(bahanCustom);

            let asalValues = [];
            $("input[name='asal_trad[]']:checked").each(function() {
                asalValues.push($(this).val());
            });
            let asalCustom = $("input[name='asal_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (asalCustom) asalValues.push(asalCustom);

            // Get ukuran values
            let panjang = $("#traditional-template label:contains('Ukuran:')").closest(".form-group").find("input").eq(0).val();
            let lebar = $("#traditional-template label:contains('Ukuran:')").closest(".form-group").find("input").eq(1).val();
            let ketebalan = $("#traditional-template label:contains('Ukuran:')").closest(".form-group").find("input").eq(2).val();

            let teknikValues = [];
            $("input[name='teknik_trad[]']:checked").each(function() {
                teknikValues.push($(this).val());
            });
            let teknikCustom = $("input[name='teknik_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (teknikCustom) teknikValues.push(teknikCustom);

            let suaraValues = [];
            $("input[name='suara_trad[]']:checked").each(function() {
                suaraValues.push($(this).val());
            });
            let suaraCustom = $("input[name='suara_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (suaraCustom) suaraValues.push(suaraCustom);

            let manfaatValues = [];
            $("input[name='manfaat_trad[]']:checked").each(function() {
                manfaatValues.push($(this).val());
            });
            let manfaatCustom = $("input[name='manfaat_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (manfaatCustom) manfaatValues.push(manfaatCustom);

            let rekomendasiValues = [];
            $("input[name='rekomendasi_trad[]']:checked").each(function() {
                rekomendasiValues.push($(this).val());
            });
            let rekomendasiCustom = $("input[name='rekomendasi_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (rekomendasiCustom) rekomendasiValues.push(rekomendasiCustom);

            let warnaValues = [];
            $("input[name='warna_trad[]']:checked").each(function() {
                warnaValues.push($(this).val());
            });
            let warnaCustom = $("input[name='warna_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (warnaCustom) warnaValues.push(warnaCustom);

            let perawatanValues = [];
            $("input[name='perawatan_trad[]']:checked").each(function() {
                perawatanValues.push($(this).val());
            });
            let perawatanCustom = $("input[name='perawatan_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (perawatanCustom) perawatanValues.push(perawatanCustom);

            let kemasanValues = [];
            $("input[name='kemasan_trad[]']:checked").each(function() {
                kemasanValues.push($(this).val());
            });
            let kemasanCustom = $("input[name='kemasan_trad[]']").closest(".d-flex").find("input[type='text']").val();
            if (kemasanCustom) kemasanValues.push(kemasanCustom);

            // Generate template text
            templateText = `Deskripsi Khusus (Spesifikasi Harpa Mulut Tradisional)

Jenis: ${jenisValues.length > 0 ? jenisValues.join(', ') : '[Pilih jenis]'}

Bahan Utama: ${bahanValues.length > 0 ? bahanValues.join(', ') : '[Pilih bahan]'}

Asal Budaya/Daerah: ${asalValues.length > 0 ? asalValues.join(', ') : '[Pilih daerah asal]'}

Ukuran:
- Panjang: ± ${panjang} cm
- Lebar: ± ${lebar} cm
- Ketebalan: ± ${ketebalan} cm

Teknik Permainan: ${teknikValues.length > 0 ? teknikValues.join(', ') : '[Pilih teknik]'}

Ciri Suara: ${suaraValues.length > 0 ? suaraValues.join(', ') : '[Pilih ciri suara]'}

Manfaat: ${manfaatValues.length > 0 ? manfaatValues.join(', ') : '[Pilih manfaat]'}

Rekomendasi Pengguna: ${rekomendasiValues.length > 0 ? rekomendasiValues.join(', ') : '[Pilih rekomendasi]'}

Warna & Tampilan: ${warnaValues.length > 0 ? warnaValues.join(', ') : '[Pilih warna/tampilan]'}

Perawatan: ${perawatanValues.length > 0 ? perawatanValues.join(', ') : '[Pilih cara perawatan]'}

Kemasan: ${kemasanValues.length > 0 ? kemasanValues.join(', ') : '[Pilih kemasan]'}`;

            // Insert template
            const descriptionField = $("#description");
            const currentText = descriptionField.val();

            // Ask for confirmation if there's already text
            if (currentText && currentText.trim() !== '') {
                if (confirm('Mengganti teks deskripsi yang ada dengan template?')) {
                    descriptionField.val(templateText);
                }
            } else {
                descriptionField.val(templateText);
            }
        });

        // Form submit handler to convert formatted currency back to number
        $("form").on("submit", function() {
            let currencyInput = $(".currency-input");
            let value = currencyInput.val().replace(/\./g, "");
            currencyInput.val(value);
        });
    });
</script>
@endpush
