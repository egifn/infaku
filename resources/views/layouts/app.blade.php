<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Infaqu') }} - @yield('title')</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="https://egifn.github.io/got-style/icon.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Internal CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('styles')

</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">

            <div class="search">
                <div class="header-brand">
                    <div class="header-logo">
                        <span class="header-logo-frame">I</span>
                    </div>
                </div>
                <form action="#" method="GET" class="search-form" style="display: flex">
                    @csrf
                    <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}"
                        class="search-input" id="global-search">
                    <button type="submit" class="search-btn btn-gradient-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                <div class="header-right">
                    <p style="font-size : 11px">{{ session('user.username') }}</p>
                    <div class="profile">
                        <div class="profile-img-wrapper">
                            <div class="dropdown hover-dropdown">
                                <img src="{{ asset('img/default-avatar.png') }}" width="20"
                                    height="20">
                                <div class="dropdown-content">
                                    <div style="padding: 8px 16px; font-weight: 600; color: #333;">
                                        {{ session('user.username') ?? '-' }}
                                    </div>
                                    <a href="#">
                                        <i class="bi bi-person"></i>Profil
                                    </a>
                                    <a href="#">
                                        <i class="bi bi-gear"></i>Setting
                                    </a>
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                style="display: none;">
                @csrf
            </form>
        </header>

        <!-- Sidebar Section -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-logo">I</span>
                <p style="color: #5b5656;margin-top: -5px;margin-left: 5px;font-size: 20px;">
                    <b>Infaqu</b>
                </p>
                <div class="sidebar-toggle">
                    <i class="bi bi-grid sidebar-toggle-btn" id="toggleSidebar"></i>
                </div>
            </div>

            <div class="sidebar-menu">
                <!-- Dashboard -->
                <a href="{{ route('admin.kelompok.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.dashboard') ? 'active' : '' }}"
                    data-name="Dashboards" title="Dashboards">
                    <i class="bi bi-bar-chart"></i>
                    <span class="menu-text">Dashboards</span>
                </a>

                <!-- Header Menu: MASTER JAMAAH -->
                <div class="menu-header">
                    <span class="full-text">MASTER JAMAAH</span>
                    <span class="short-text" style="display:none;">MJ</span>
                </div>

                <a href="{{ route('admin.kelompok.data-jamaah.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.data-jamaah.*') ? 'active' : '' }}"
                    data-name="Data Jamaah" title="Data Jamaah">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Data Jamaah</span>
                </a>

                <a href="{{ route('admin.kelompok.data-keluarga.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.data-keluarga.*') ? 'active' : '' }}"
                    data-name="Data Keluarga" title="Data Keluarga">
                    <i class="bi bi-person-vcard"></i>
                    <span class="menu-text">Data Keluarga</span>
                </a>

                <!-- Header Menu: MASTER KONTRIBUSI -->
                <div class="menu-header">
                    <span class="full-text">MASTER KONTRIBUSI</span>
                    <span class="short-text" style="display:none;">MK</span>
                </div>

                <a href="{{ route('admin.kelompok.master-kontribusi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.master-kontribusi.*') ? 'active' : '' }}"
                    data-name="Data Kontribusi" title="Data Kontribusi">
                    <i class="bi bi-cash-stack"></i>
                    <span class="menu-text">Data Kontribusi</span>
                </a>

                <a href="{{ route('admin.kelompok.sub-kontribusi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.sub-kontribusi.*') ? 'active' : '' }}"
                    data-name="Data Sub Kontribusi" title="Data Sub Kontribusi">
                    <i class="bi bi-list-nested"></i>
                    <span class="menu-text">Data Sub Kontribusi</span>
                </a>

                <!-- Header Menu: TRANSAKSI -->
                <div class="menu-header">
                    <span class="full-text">TRANSAKSI</span>
                    <span class="short-text" style="display:none;">TR</span>
                </div>

                <a href="{{ route('admin.kelompok.input-pembayaran.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.input-pembayaran.*') ? 'active' : '' }}"
                    data-name="Transaksi" title="Transaksi">
                    <i class="bi bi-wallet2"></i>
                    <span class="menu-text">Input Pembayaran</span>
                </a>

                <a href="{{ route('admin.kelompok.riwayat-transaksi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.riwayat-transaksi.*') ? 'active' : '' }}"
                    data-name="Riwayat Transaksi" title="Riwayat Transaksi">
                    <i class="bi bi-clock-history"></i>
                    <span class="menu-text">Riwayat Transaksi</span>
                </a>

                <!-- Header Menu: LAPORAN -->
                <div class="menu-header">
                    <span class="full-text">LAPORAN</span>
                    <span class="short-text" style="display:none;">LP</span>
                </div>

                <a href="{{ route('admin.kelompok.laporan.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.laporan.*') ? 'active' : '' }}"
                    data-name="Laporan" title="Laporan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="menu-text">Laporan</span>
                </a>

                <!-- Additional Menu Items with Submenus (Example) -->
                @if(isset($hasSubmenu) && $hasSubmenu)
                    <div class="menu-item has-submenu" data-submenu="submenu-extra">
                        <i class="bi bi-gear"></i>
                        <span class="menu-text">Pengaturan</span>
                        <span class="dropdown-toggle">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </div>

                    <div class="submenu" id="submenu-extra">
                        <a href="#" class="menu-item">
                            <i class="bi bi-person-circle"></i>
                            <span class="menu-text">Pengguna</span>
                        </a>
                        <a href="#" class="menu-item">
                            <i class="bi bi-shield-check"></i>
                            <span class="menu-text">Hak Akses</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Navbar Section -->
        <div class="navbar" id="navbar">
            <div class="navbar-brand">
                <i class="bi bi-grid navbar-toggle-btn" id="toggleNavbar"></i>
            </div>
            <div class="navbar-title">
                @yield('page-title', 'Dashboard')
                <i class="@yield('icon-page-title', 'bi bi-house')"></i>
            </div>

            <div class="navbar-actions">
                @hasSection('navbar-actions')
                    @yield('navbar-actions')
                @else
                @endif
            </div>
        </div>

        <!-- Main Content Section -->
        <main class="main-content" id="mainContent">
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <nav class="breadcrumb">
                    @yield('breadcrumb')
                </nav>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="toast-container">
                    <div class="toast success show d-flex align-items-center justify-content-between gap-2 px-3 py-2 shadow rounded mb-2"
                        id="successToast" style="min-width:280px;max-width:400px;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="toast-icon text-success fs-4"><i class="bi bi-check-circle"></i></span>
                            <div class="toast-content">
                                <div class="toast-title fw-bold mb-1">Sukses</div>
                                <div class="toast-message small">{{ session('success') }}</div>
                            </div>
                        </div>
                        <button type="button"
                            class="btn btn-sm btn-light border-0 p-1 ms-2 toast-close d-flex align-items-center justify-content-center"
                            aria-label="Close" onclick="this.closest('.toast').classList.remove('show')">
                            <i class="bi bi-x fs-5"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-container">
                    <div class="toast error show d-flex align-items-center justify-content-between gap-2 px-3 py-2 shadow rounded mb-2"
                        id="errorToast" style="min-width:280px;max-width:400px;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="toast-icon text-danger fs-4"><i class="bi bi-exclamation-circle"></i></span>
                            <div class="toast-content">
                                <div class="toast-title fw-bold mb-1">Error</div>
                                <div class="toast-message small">{{ session('error') }}</div>
                            </div>
                        </div>
                        <button type="button"
                            class="btn btn-sm btn-light border-0 p-1 ms-2 toast-close d-flex align-items-center justify-content-center"
                            aria-label="Close" onclick="this.closest('.toast').classList.remove('show')">
                            <i class="bi bi-x fs-5"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <div class="master-container">
                @yield('content')
            </div>

            <!-- Modal Container -->
            <div id="modalContainer"></div>
        </main>
    </div>

    <!-- JavaScript Files -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/modals.js') }}"></script>
    <script src="{{ asset('js/toasts.js') }}"></script>

    <!-- Inline JavaScript -->
    <script>
        // CSRF Token setup for AJAX requests
        document.addEventListener('DOMContentLoaded', function () {

            // Set CSRF token for all fetch requests (tanpa jQuery)
            window.getCsrfToken = function () {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            };

            // Helper fetch wrapper with CSRF
            window.csrfFetch = function (url, options = {}) {
                options.headers = options.headers || {};
                if (!options.headers['X-CSRF-TOKEN']) {
                    options.headers['X-CSRF-TOKEN'] = getCsrfToken();
                }
                return fetch(url, options);
            };

            // Global search functionality
            const searchInput = document.getElementById('global-search');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function (e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 3) {
                            performSearch(this.value);
                        }
                    }, 500);
                });
            }

            // Initialize sidebar state from localStorage
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.querySelector('.main-content').classList.add('expanded');
                document.querySelector('.navbar').classList.add('expanded');
                updateMenuHeaderText();
            }
        });

        // Global search function


        // Show search results in dropdown
        function showSearchResults(results) {
            // Implementation for showing search results
            console.log('Search results:', results);
        }

        // Update menu header text based on sidebar state
        function updateMenuHeaderText() {
            const sidebar = document.querySelector('.sidebar');
            const menuHeaders = document.querySelectorAll('.menu-header');

            menuHeaders.forEach(header => {
                const fullText = header.querySelector('.full-text');
                const shortText = header.querySelector('.short-text');

                if (sidebar.classList.contains('collapsed')) {
                    if (fullText) fullText.style.display = 'none';
                    if (shortText) shortText.style.display = 'inline';
                } else {
                    if (fullText) fullText.style.display = 'inline';
                    if (shortText) shortText.style.display = 'none';
                }
            });
        }

        // Export data function
        function exportData(type, url) {
            const exportUrl = `${url}?export=${type}&${new URLSearchParams(window.location.search).toString()}`;
            window.open(exportUrl, '_blank');
        }

        // Show loading indicator
        function showLoading() {
            const loader = document.createElement('div');
            loader.className = 'loading-overlay';
            loader.innerHTML = `
                <div class="loading-spinner">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Loading...</span>
                </div>
            `;
            document.body.appendChild(loader);
        }

        // Hide loading indicator
        function hideLoading() {
            const loader = document.querySelector('.loading-overlay');
            if (loader) {
                loader.remove();
            }
        }

        // Confirm dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

    </script>

    @stack('scripts')
</body>

</html>
