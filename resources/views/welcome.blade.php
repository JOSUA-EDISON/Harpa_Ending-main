@extends('layouts.landing')

@push('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/landing/tes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing/welcome-modals.css') }}">
    <style>
        .login-prompt {
            background-color: rgba(103, 119, 239, 0.1);
            padding: 8px 15px;
            border-radius: 5px;
            margin-top: 10px;
            border-left: 3px solid #6777ef;
            font-size: 14px;
        }
        .login-prompt a {
            color: #6777ef;
            font-weight: 600;
            text-decoration: underline;
        }
        /* Improved card styling */
        .nft-list .item {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .nft-list .item .info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .nft-list .item img.product-image {
            width: 100%;
            height: 180px;
            object-fit: contain;
            padding: 15px;
            transition: transform 0.3s ease;
        }
        .nft-list h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .nft-list .btc p {
            font-weight: 700;
            color: #6777ef;
            font-size: 15px;
        }
        .product-description {
            margin: 10px 0;
            overflow: hidden;
        }
        .product-description-list p {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .details-btn {
            background: linear-gradient(135deg, #6777ef, #3d4eda);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(103, 119, 239, 0.3);
            margin-top: auto;
            align-self: flex-start;
        }
        .details-btn:hover {
            background: linear-gradient(135deg, #5a67d8, #354ada);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(103, 119, 239, 0.4);
        }
        .nft-list .bid {
            padding: 15px;
            border-top: 1px solid #f0f0f0;
            background-color: #fafafa;
        }
        .btn-add-to-cart {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #6777ef, #3d4eda);
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(103, 119, 239, 0.3);
        }
        .btn-add-to-cart:hover {
            background: linear-gradient(135deg, #5a67d8, #354ada);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(103, 119, 239, 0.4);
        }
        /* View count styling */
        .view-count {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .view-count i {
            margin-right: 4px;
            color: #6777ef;
        }
        /* Improved modal styling */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        #productDetailContent {
            padding: 0;
        }
        .product-detail {
            display: flex;
            flex-direction: column;
        }
        .product-image {
            width: 100%;
            padding: 20px;
            background-color: #f9f9f9;
            text-align: center;
        }
        .product-info {
            padding: 25px;
        }
        .product-info h3 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .price {
            font-size: 20px;
            font-weight: 700;
            color: #6777ef;
            margin-bottom: 20px;
        }
        .description {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }
        .add-to-cart-form {
            margin-top: 20px;
        }
        .quantity-container {
            margin-bottom: 15px;
        }
        .quantity-input {
            display: flex;
            align-items: center;
            width: 140px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 50px;
            overflow: hidden;
        }
        .quantity-btn {
            width: 40px;
            height: 40px;
            background-color: #f0f0f0;
            border: none;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .quantity-btn:hover {
            background-color: #e0e0e0;
        }
        .quantity-input input {
            width: 60px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }
        .quantity-input input:focus {
            outline: none;
        }
        .add-to-cart-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: linear-gradient(135deg, #6777ef, #3d4eda);
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(103, 119, 239, 0.3);
        }
        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #5a67d8, #354ada);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(103, 119, 239, 0.4);
        }
        .stock {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .stock-label {
            font-weight: 600;
            color: #444;
            margin-right: 8px;
        }
        .stock-value {
            color: #28a745;
            font-weight: 500;
        }
        @media (min-width: 768px) {
            .product-detail {
                flex-direction: row;
            }
            .product-image {
                width: 40%;
                padding: 30px;
            }
            .product-info {
                width: 60%;
            }
        }
        @media (max-width: 576px) {
            .nft-list .item img.product-image {
                height: 150px;
            }
        }
    </style>
@endpush

@section('title', 'Gallery Bejo - UMKM Bersuara')

@section('content')
    <div class="main-content">
        @include('components.navbar')

        <header id="home">
            <div class="left">
                <h1>Harpa Mulut – Saatnya UMKM Bersuara! <span>Gallery Bejo</span></h1>
                <p>Kami percaya bahwa setiap UMKM memiliki cerita unik.</p>
                @auth
                <a href="{{ route('products.catalog') }}">
                    <i class='bx bx-basket'></i>
                    <span>Pesan Sekarang</span>
                </a>
                @else
                <a href="{{ route('login') }}">
                    <i class='bx bx-log-in'></i>
                    <span>Login untuk Belanja</span>
                </a>
                @endauth
            </div>
            <img src="{{ asset('img/img/tp.png') }}">
        </header>

        <h2 id="promosi-unggulan" class="separator">
            Promosi Unggulan
        </h2>

        <div class="nft-shop">
            <div class="nft-list">
                @forelse($featuredProducts as $product)
                    <div class="item">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image"
                            onclick="openModal('{{ Storage::url($product->image) }}', '{{ $product->name }}')">
                        <div class="info">
                            <div>
                                <h5>{{ $product->name }}</h5>
                                <div class="btc">
                                    <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="view-count">
                                    <i class='bx bx-show'></i> <span>{{ $product->views ?? 0 }} kali dilihat</span>
                                </div>
                            </div>
                            <div class="product-description">
                                <div class="product-description-list">
                                    @php
                                        $lines = explode("\n", $product->description);
                                        $shortDescription = array_slice($lines, 0, 3);
                                    @endphp
                                    @if(count($shortDescription) > 0)
                                        @foreach($shortDescription as $line)
                                            @if(trim($line))
                                                <p>{{ $line }}</p>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-muted">Tidak ada deskripsi produk</p>
                                    @endif
                                </div>
                            </div>
                            <div class="product-details">
                                <button class="details-btn" onclick="showDetails('{{ $product->id }}')">
                                    <i class='bx bx-info-circle'></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                        <div class="bid">
                            @auth
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect_to" value="{{ route('cart.index') }}">
                                <button type="submit" class="btn-add-to-cart">
                                    <i class='bx bx-cart-add'></i> Tambahkan ke Keranjang
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn-add-to-cart">
                                <i class='bx bx-log-in'></i> Login untuk Membeli
                            </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No featured products available.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Product Detail Modal -->
        <div id="productDetailModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeDetailModal()">&times;</span>
                <div id="productDetailContent"></div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                <img id="modalImage" src="" alt="">
                <div id="modalCaption"></div>
            </div>
        </div>

        <!-- Contact Section -->
        <section id="contact" class="contact">
            <h2><span>Kontak</span> Kami</h2>

            <div class="row">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.291823424536!2d112.60701217386206!3d-7.968763192056161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e788285c228f26f%3A0xf13c282db677a0b1!2sJl.%20Singgalang%20No.5%2C%20Pisang%20Candi%2C%20Kec.%20Sukun%2C%20Kota%20Malang%2C%20Jawa%20Timur%2065146!5e0!3m2!1sid!2sid!4v1689173406403!5m2!1sid!2sid"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map">
                </iframe>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <i data-feather="user"></i>
                        <input type="text" name="name" placeholder="nama">
                    </div>
                    <div class="input-group">
                        <i data-feather="mail"></i>
                        <input type="email" name="email" placeholder="email">
                    </div>
                    <div class="input-group">
                        <i data-feather="phone"></i>
                        <input type="text" name="phone" placeholder="no hp">
                    </div>
                    <button type="submit" class="btn">Kirim Pesan</button>
                </form>
            </div>
        </section>

        <footer>
            <h3>Create, Explore, Find & Collect Your Want Here</h3>
            <div class="right">
                <div class="links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Cooperation</a>
                    <a href="#">Sponsorship</a>
                    <a href="#">Contact Us</a>
                </div>
                <div class="social">
                    <i class='bx bxl-instagram'></i>
                    <i class='bx bxl-facebook-square'></i>
                    <i class='bx bxl-github'></i>
                </div>
                <p>Copyright © {{ date('Y') }} Gallery Bejo, All Rights Reserved.</p>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });

        // Format currency function
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Image modal functions
        function openModal(imgSrc, caption) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const captionText = document.getElementById('modalCaption');

            modalImg.src = imgSrc;
            captionText.innerHTML = caption;

            modal.style.display = "block";
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }

        // Close product detail modal
        function closeDetailModal() {
            const modal = document.getElementById('productDetailModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }

        // Product detail functions
        function showDetails(productId) {
            const modal = document.getElementById('productDetailModal');
            const contentDiv = document.getElementById('productDetailContent');

            // Show loading state
            modal.style.display = "block";
            contentDiv.innerHTML = '<div style="text-align: center; padding: 40px;"><i data-feather="loader" class="spinner"></i><p>Memuat detail produk...</p></div>';
            feather.replace(); // Replace the feather icon

            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Record view for authenticated users
            @auth
            fetch(`/api/products/${productId}/record-view`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            @endauth

            // Use AJAX to fetch product details
            fetch(`/api/products/${productId}`)
                .then(response => response.json())
                .then(data => {
                    let isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                    let description = data.description && data.description.trim() !== ''
                        ? data.description.replace(/\\n/g, '<br>')
                        : '<span class="text-muted">Tidak ada detail produk</span>';
                    let productHtml = `
                        <div class="product-detail">
                            <div class="product-image">
                                <img src="${data.image_url}" alt="${data.name}" style="max-width: 100%; max-height: 300px;">
                            </div>
                            <div class="product-info">
                                <h3>${data.name}</h3>
                                <div class="price">${formatCurrency(data.price)}</div>
                                <div class="description">${description}</div>
                                <div class="stock">
                                    <span class="stock-label">Stok:</span>
                                    <span class="stock-value">${data.stock_quantity ?? 'Tersedia'}</span>
                                </div>
                    `;

                    if (isLoggedIn) {
                        productHtml += `
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="${data.id}">
                                    <input type="hidden" name="redirect_to" value="{{ route('cart.index') }}">
                                    <div class="quantity-container">
                                        <label for="quantity">Jumlah:</label>
                                        <div class="quantity-input">
                                            <button type="button" class="quantity-btn minus" onclick="decrementQuantity(this)">-</button>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="${data.stock_quantity || 99}">
                                            <button type="button" class="quantity-btn plus" onclick="incrementQuantity(this)">+</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="add-to-cart-btn">
                                        <i data-feather="shopping-cart"></i>
                                        Tambahkan ke Keranjang
                                    </button>
                                </form>
                        `;
                    } else {
                        productHtml += `
                                <div class="login-prompt" style="background-color: rgba(103, 119, 239, 0.1); padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 3px solid #6777ef;">
                                    <p>Untuk menambahkan produk ke keranjang, silakan <a href="{{ route('login') }}" style="color: #6777ef; font-weight: bold;">login</a> terlebih dahulu.</p>
                                </div>
                                <a href="{{ route('login') }}" class="add-to-cart-btn" style="display: inline-flex; text-decoration: none; align-items: center; justify-content: center; gap: 10px; background: linear-gradient(135deg, #6777ef, #3d4eda); color: white; padding: 12px 20px; border-radius: 50px; font-weight: 600; margin-top: 10px;">
                                    <i data-feather="log-in"></i>
                                    Login untuk Membeli
                                </a>
                        `;
                    }

                    productHtml += `
                            </div>
                        </div>
                    `;

                    contentDiv.innerHTML = productHtml;
                    feather.replace(); // Replace feather icons in the new content
                })
                .catch(error => {
                    contentDiv.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i data-feather="alert-circle" style="width: 48px; height: 48px; color: #fc544b;"></i>
                            <p>Terjadi kesalahan saat memuat detail produk.</p>
                            <button onclick="closeDetailModal()" class="btn-close-modal">Tutup</button>
                        </div>
                    `;
                    feather.replace();
                });
        }

        // Quantity functions for product detail
        function incrementQuantity(button) {
            const input = button.parentNode.querySelector('input');
            const max = parseInt(input.getAttribute('max'));
            const currentValue = parseInt(input.value);

            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decrementQuantity(button) {
            const input = button.parentNode.querySelector('input');
            const currentValue = parseInt(input.value);

            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
@endpush
