@extends('layouts.app')

@section('title', 'Hak Akses')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/hakases/hakases.css') }}">
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
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Pengguna dan Hak Akses</h4>
                    <div class="card-header-form d-flex">
                        <a href="{{ route('admin.hakakses.create') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                        <form>
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-wrapper">
                        <table class="table table-striped" id="table-hakakses">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hakakses as $item)
                                    @if($item->role == 'admin')
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $item->role }}</span>
                                        </td>
                                        <td>
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
            // Add basic search functionality
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table-hakakses tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush
