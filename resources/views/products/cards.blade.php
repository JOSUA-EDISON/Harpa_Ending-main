@extends('layouts.app')

@section('title', 'Products Gallery')

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
                    <h2 class="section-title m-0">Products Gallery</h2>
                    <div class="d-flex">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-icon icon-left mr-2">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                            <i class="fas fa-list"></i> Table View
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row mt-4">
                    @forelse ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card product-card h-100">
                            @if ($product->featured)
                            <div class="ribbon ribbon-primary">Featured</div>
                            @endif
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-primary font-weight-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="card-text flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                <div class="card-actions mt-auto d-flex">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info flex-fill mr-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
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
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
