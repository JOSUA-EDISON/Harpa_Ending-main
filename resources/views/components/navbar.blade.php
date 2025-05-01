<nav class="navbar">
    <div class="nav-container">
        <a href="#home" class="logo">
            <span class="logo-text">Gallery Bejo</span>
        </a>

        <div class="nav-links">
            <a href="#home" class="nav-link">
                <i class='bx bx-home-alt'></i>
                <span>Beranda</span>
            </a>
            <a href="#promosi-unggulan" class="nav-link">
                <i class='bx bx-store-alt'></i>
                <span>Produk</span>
            </a>
            <a href="#contact" class="nav-link">
                <i class='bx bx-envelope'></i>
                <span>Kontak</span>
            </a>
        </div>

        <div class="auth-buttons">
            <div class="shop-links">
                @auth
                <a href="{{ route('products.catalog') }}" class="shop-link catalog">
                    <i class='bx bx-store'></i>
                    <span>Katalog</span>
                </a>
                <a href="{{ route('cart.index') }}" class="shop-link cart">
                    <i class='bx bx-cart'></i>
                    <span>Keranjang</span>
                </a>
                @else
                <a href="{{ route('login') }}" class="shop-link catalog">
                    <i class='bx bx-log-in'></i>
                    <span>Login untuk Belanja</span>
                </a>
                @endauth
            </div>

            @guest
                <a href="{{ route('login') }}" class="auth-link login">
                    <i class='bx bx-log-in'></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="auth-link register">
                    <i class='bx bx-user-plus'></i>
                    <span>Register</span>
                </a>
            @else
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="auth-link login">
                    <i class='bx bx-user'></i>
                    <span>Dashboard</span>
                </a>
                @else
                <a href="{{ route('profile.show') }}" class="auth-link login">
                    <i class='bx bx-user-circle'></i>
                    <span>Profile</span>
                </a>
                @endif
                <a href="{{ route('orders.index') }}" class="auth-link orders">
                    <i class='bx bx-shopping-bag'></i>
                    <span>Pesanan</span>
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="auth-link logout">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>

        <button class="mobile-menu-btn">
            <i class='bx bx-menu'></i>
        </button>
    </div>
</nav>

<style>
    .shop-links {
        display: flex;
        margin-right: 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 3px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
    }

    .shop-link {
        display: flex;
        align-items: center;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        transition: all 0.3s ease;
        border-radius: 50px;
        margin: 0 2px;
    }

    .shop-link i {
        font-size: 18px;
        margin-right: 6px;
        transition: transform 0.3s ease;
    }

    .shop-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .shop-link:hover i {
        transform: scale(1.2);
    }

    .shop-link.catalog {
        background: linear-gradient(135deg, #38b6ff, #5271ff);
    }

    .shop-link.cart {
        background: linear-gradient(135deg, #ff6b6b, #ff9e4a);
    }

    /* New styles for nav-links */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        transition: all 0.3s ease;
        border-radius: 50px;
        background: linear-gradient(135deg, #ffffff, #ffffff);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .nav-link i {
        font-size: 18px;
        margin-right: 6px;
        transition: transform 0.3s ease;
    }

    .nav-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .nav-link:hover i {
        transform: scale(1.2);
    }

    .nav-link.active {
        background: linear-gradient(135deg, #6e3adc, #3b82f6);
    }

    .navbar .nav-link {
        color: #000000;
        padding-left: 15px !important;
        padding-right: 15px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        height: 100%;
    }

    @media (max-width: 768px) {
        .shop-links {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .auth-buttons {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-links {
            flex-direction: column;
            width: 100%;
            gap: 5px;
        }
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            const navLinksItems = document.querySelectorAll('.nav-link');
            const navbar = document.querySelector('.navbar');

            // Toggle mobile menu
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('show');
                this.classList.toggle('active');
            });

            // Smooth scroll with minimal offset
            navLinksItems.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');

                    // Skip smooth scroll for links that are not anchor links
                    if (!targetId.startsWith('#')) {
                        return;
                    }

                    e.preventDefault();
                    const targetSection = document.querySelector(targetId);

                    if (targetSection) {
                        // Remove active class from all links
                        navLinksItems.forEach(item => item.classList.remove('active'));
                        // Add active class to clicked link
                        this.classList.add('active');

                        // Get the target section's position
                        const targetPosition = targetSection.offsetTop;

                        // Small delay for mobile menu to close before scrolling
                        setTimeout(() => {
                            // Smooth scroll to target with no offset
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }, 100);

                        // Close mobile menu if open
                        navLinks.classList.remove('show');
                        mobileMenuBtn.classList.remove('active');
                    }
                });
            });

            // Update active state on scroll with better detection for each section
            function updateActiveLink() {
                const sections = document.querySelectorAll('#home, #promosi-unggulan, #contact');
                const scrollPosition = window.scrollY;
                const navbarHeight = 80; // Match with CSS scroll-padding-top

                // Find the section closest to the top of the viewport
                let currentSection = null;
                let minDistance = Infinity;

                sections.forEach(section => {
                    // Distance from the top of the section to current scroll position
                    const distance = Math.abs(section.offsetTop - scrollPosition - navbarHeight);

                    // If we're within the section or closer than any previous section
                    if (distance < minDistance) {
                        minDistance = distance;
                        currentSection = section;
                    }
                });

                if (currentSection) {
                    const targetId = '#' + currentSection.id;
                    navLinksItems.forEach(link => {
                        if (link.getAttribute('href') === targetId) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                }
            }

            // Throttled scroll event listener
            let isScrolling = false;
            window.addEventListener('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        updateActiveLink();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            });

            // Initial check for active section
            updateActiveLink();
        });
    </script>
@endpush
