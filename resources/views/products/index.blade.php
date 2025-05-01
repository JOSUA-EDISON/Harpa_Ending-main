@extends('layouts.app')

@section('title', 'Products Management')

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
                    <h2 class="section-title m-0">Products List</h2>
                    <div class="d-flex">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-icon icon-left mr-2">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                        <a href="{{ route('admin.products.cards') }}" class="btn btn-light">
                            <i class="fas fa-th"></i> Gallery View
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Featured</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($product->featured)
                                        <span class="badge badge-primary">Featured</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-icon btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <h2>No products found</h2>
                                        <p class="lead">
                                            You haven't added any products yet.
                                        </p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-4">
                                            <i class="fas fa-plus"></i> Add New Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Simple initialization without the complex DataTables features
        $('#products-table').DataTable({
            responsive: true,
            searching: true,
            ordering: true,
            paging: true,
            info: true,
            lengthChange: true,
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            "dom": '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>t<"d-flex justify-content-between align-items-center mt-3"<"d-flex align-items-center"i><"d-flex"p>>'
        });

        // Customize DataTables search box
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_filter input').attr('placeholder', 'Search...');
        $('.dataTables_filter label').addClass('mb-0');
        $('.dataTables_length select').addClass('custom-select custom-select-sm');
    });
</script>
@endpush
