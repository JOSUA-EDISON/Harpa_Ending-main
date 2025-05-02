@extends('layouts.app')

@section('title', 'Hak Akses')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/hakases/hakases.css') }}">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .table th:nth-child(1), .table td:nth-child(1) { /* ID column */
            width: 8%;
        }
        .table th:nth-child(2), .table td:nth-child(2) { /* Nama column */
            width: 20%;
        }
        .table th:nth-child(3), .table td:nth-child(3) { /* Email column */
            width: 30%;
        }
        .table th:nth-child(4), .table td:nth-child(4) { /* Role column */
            width: 12%;
            text-align: center;
        }
        .table th:nth-child(5), .table td:nth-child(5) { /* Action column */
            width: 30%;
            text-align: center;
        }
        .btn-sm {
            margin: 2px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Hak Akses</h1>
        </div>
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="section-body">
            <!-- Admin Table -->
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Admin</h4>
                    <div class="card-header-form d-flex">
                        <a href="{{ route('admin.hakakses.create') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                        <form>
                            <div class="input-group">
                                <input type="text" id="searchAdminInput" class="form-control" placeholder="Cari admin...">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-admin">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $adminCounter = 1; @endphp
                                @foreach ($hakakses as $item)
                                    @if($item->role == 'admin')
                                    <tr>
                                        <td>{{ $adminCounter++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ $item->role }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)"
                                                onclick="confirmEdit('{{ route('admin.hakakses.edit', $item->id) }}')"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.hakakses.delete', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.hakakses.unpromote', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="confirmUnpromote(event)">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-user"></i> Jadikan User
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- User Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Daftar User</h4>
                    <div class="card-header-form">
                        <div class="input-group">
                            <input type="text" id="searchUserInput" class="form-control" placeholder="Cari user...">
                            <div class="input-group-btn">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-user">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $userCounter = 1; @endphp
                                @foreach ($hakakses as $item)
                                    @if($item->role == 'user')
                                    <tr>
                                        <td>{{ $userCounter++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $item->role }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)"
                                                onclick="confirmEdit('{{ route('admin.hakakses.edit', $item->id) }}')"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.hakakses.delete', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.hakakses.promote', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="confirmPromote(event)">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-user-shield"></i> Jadikan Admin
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.sweet-alert')
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Add search functionality for admin table
            $("#searchAdminInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table-admin tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Add search functionality for user table
            $("#searchUserInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table-user tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Function to confirm promotion to admin
        function confirmPromote(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menjadikan user ini sebagai admin?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, jadikan admin',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Function to confirm unpromote to user
        function confirmUnpromote(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengubah admin ini kembali menjadi user?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, jadikan user',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
