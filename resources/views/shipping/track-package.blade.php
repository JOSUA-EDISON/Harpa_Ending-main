@extends('layouts.app')

@section('title', 'Lacak Kiriman - Harpa')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/shipping.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-search-location"></i> Lacak Kiriman</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item active">Lacak Kiriman</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lacak Status Pengiriman</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="alert alert-info">
                                    <p class="mb-0">Lacak status pengiriman paket Anda dengan memasukkan nomor resi dan memilih kurir yang sesuai.</p>
                                </div>
                            </div>
                        </div>

                        <form id="tracking-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Resi</label>
                                        <input type="text" class="form-control" id="waybill" placeholder="Masukkan nomor resi pengiriman" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kurir</label>
                                        <select class="form-control" id="courier" required>
                                            <option value="">Pilih Kurir</option>
                                            @foreach($couriers as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block" id="btn-track">
                                            <i class="fas fa-search"></i> Lacak Kiriman
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="tracking-results mt-4" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Pengiriman</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Nomor Resi</strong></td>
                                                    <td width="60%" id="result-waybill"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kurir</strong></td>
                                                    <td id="result-courier"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status</strong></td>
                                                    <td id="result-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Pengirim</strong></td>
                                                    <td id="result-sender"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Penerima</strong></td>
                                                    <td id="result-receiver"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Lokasi Pengiriman</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Asal</strong></td>
                                                    <td width="60%" id="result-origin"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tujuan</strong></td>
                                                    <td id="result-destination"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Berat</strong></td>
                                                    <td id="result-weight"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Pengiriman</strong></td>
                                                    <td id="result-shipment-date"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Diterima</strong></td>
                                                    <td id="result-delivery-date"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Pengiriman</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="20%">Tanggal</th>
                                                            <th width="30%">Lokasi</th>
                                                            <th width="50%">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="result-manifest">
                                                        <!-- Manifest will be filled here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tracking-not-found mt-4 text-center" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <h5><i class="fas fa-exclamation-triangle"></i> Pengiriman Tidak Ditemukan</h5>
                                        <p class="mb-0">Nomor resi tidak ditemukan atau belum terdata di sistem. Mohon periksa kembali nomor resi dan kurir yang Anda pilih.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="loading-overlay" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Sedang melacak pengiriman...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
@endpush

@push('scripts')
<!-- Add SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Track package
        $('#tracking-form').on('submit', function(e) {
            e.preventDefault();

            const waybill = $('#waybill').val();
            const courier = $('#courier').val();

            if (!waybill || !courier) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Nomor resi dan kurir harus diisi',
                    icon: 'error'
                });
                return;
            }

            trackPackage(waybill, courier);
        });

        // Function to track a package
        function trackPackage(waybill, courier) {
            $('.loading-overlay').show();
            $('.tracking-results').hide();
            $('.tracking-not-found').hide();

            $.ajax({
                url: '{{ route("shipping.track.search") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    waybill: waybill,
                    courier: courier
                },
                success: function(response) {
                    $('.loading-overlay').hide();

                    if (response.status === 'success') {
                        displayTrackingResults(response.data.rajaongkir.result);
                    } else {
                        $('.tracking-not-found').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('.loading-overlay').hide();
                    $('.tracking-not-found').show();
                }
            });
        }

        // Function to display tracking results
        function displayTrackingResults(data) {
            if (!data || data.delivered === false) {
                $('.tracking-not-found').show();
                return;
            }

            // Fill shipping information
            $('#result-waybill').text(data.summary.waybill_number || '-');
            $('#result-courier').text(data.summary.courier_name || '-');
            $('#result-status').text(data.delivery_status.status || '-');
            $('#result-sender').text(data.summary.shipper_name || '-');
            $('#result-receiver').text(data.summary.receiver_name || '-');

            // Fill location information
            $('#result-origin').text(data.summary.origin || '-');
            $('#result-destination').text(data.summary.destination || '-');
            $('#result-weight').text((data.summary.weight || '0') + ' gram');
            $('#result-shipment-date').text(data.summary.waybill_date || '-');
            $('#result-delivery-date').text(data.delivery_status.pod_date || '-');

            // Fill manifest
            let manifestHtml = '';

            if (data.manifest && data.manifest.length > 0) {
                $.each(data.manifest, function(key, item) {
                    manifestHtml += `
                        <tr>
                            <td>${item.manifest_date} ${item.manifest_time}</td>
                            <td>${item.city_name || '-'}</td>
                            <td>${item.manifest_description}</td>
                        </tr>
                    `;
                });
            } else {
                manifestHtml = `
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data riwayat pengiriman</td>
                    </tr>
                `;
            }

            $('#result-manifest').html(manifestHtml);

            // Show results
            $('.tracking-results').show();
        }
    });
</script>
@endpush
