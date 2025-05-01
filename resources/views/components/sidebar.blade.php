@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">STISLA</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">STISLA</a>
            </div>
            <ul class="sidebar-menu">
                <!-- profile ganti password (for both) -->
                <li class="menu-header">Profile</li>
                <li class="{{ Request::is('profile') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile') }}"><i class="far fa-user"></i>
                        <span>Profile</span></a>
                </li>
                {{-- <li class="{{ Request::is('profile/change-password') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ url('profile/change-password') }}"><i class="fas fa-key"></i> <span>Ganti
                                            Password</span></a>
                                </li> --}}
                <!-- Navigasi untuk Admin -->
                @if (Auth::user()->role == 'admin')
                    <li class="menu-header">Navigation</li>
                    <li>
                        <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home"></i><span>Beranda</span></a>
                    </li>
                    <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>

                    <li class="menu-header">Management</li>
                    <li class="{{ Request::is('admin/hakakses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.hakakses.index') }}"><i class="fas fa-user-shield"></i>
                            <span>Hak Akses</span></a>
                    </li>
                    <li class="{{ Request::is('admin/products*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.products.index') }}"><i class="fas fa-box"></i>
                            <span>Products</span></a>
                    </li>
                    <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart"></i>
                            <span>Pesanan</span></a>
                    </li>
                    <li class="{{ Request::is('admin/invoices*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.invoices.index') }}"><i class="fas fa-file-invoice"></i>
                            <span>Invoice</span></a>
                    </li>
                    <li class="{{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i>
                            <span>Pengaturan API</span></a>
                    </li>

                    <!-- Navigasi untuk User Regular -->
                @else
                    <li class="menu-header">Navigation</li>
                    <li>
                        <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home"></i><span>Beranda</span></a>
                    </li>

                    <!-- Product Catalog -->
                    <li class="{{ Request::is('products/catalog') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('products.catalog') }}"><i class="fas fa-shopping-basket"></i>
                            <span>Katalog Produk</span></a>
                    </li>

                    <!-- Cart -->
                    <li class="{{ Request::is('cart*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i>
                            <span>Keranjang</span></a>
                    </li>

                    <!-- Orders -->
                    <li class="menu-header">Transaksi</li>
                    <li class="{{ Request::is('orders*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag"></i>
                            <span>Pesanan Saya</span></a>
                    </li>

                    <!-- Shipping section -->
                    <li class="menu-header">Pengiriman</li>
                    <li class="{{ Request::is('shipping/check-ongkir') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shipping.check-ongkir') }}"><i class="fas fa-truck"></i>
                            <span>Cek
                                Ongkir</span></a>
                    </li>
                    <li class="{{ Request::is('shipping/track') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shipping.track') }}"><i class="fas fa-search-location"></i>
                            <span>Lacak Kiriman</span></a>
                    </li>
                @endif
            </ul>
        </aside>
    </div>
@endauth
