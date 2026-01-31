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
                <p class="sidebar-title">
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
            </div>
        </div>

        <!-- Navbar Section -->
        <div class="navbar" id="navbar">
            <i class="bi bi-grid navbar-toggle-btn" id="toggleNavbar"></i>
            <div class="navbar-left">
                <div class="navbar-title">
                    @yield('page-title', 'Dashboard')
                    <i class="@yield('icon-page-title', 'bi bi-house')"></i>
                </div>
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
                    <div class="toast success show">
                        <div class="toast-content">
                            <i class="bi bi-check-circle toast-icon"></i>
                            <div class="toast-message">
                                <strong>Sukses!</strong> {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="toast-close"
                            onclick="this.closest('.toast').classList.remove('show')">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-container">
                    <div class="toast error show">
                        <div class="toast-content">
                            <i class="bi bi-exclamation-circle toast-icon"></i>
                            <div class="toast-message">
                                <strong>Error!</strong> {{ session('error') }}
                            </div>
                        </div>
                        <button type="button" class="toast-close"
                            onclick="this.closest('.toast').classList.remove('show')">
                            <i class="bi bi-x"></i>
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
        function performSearch(query) {
            // Implement search functionality here
            console.log('Searching for:', query);
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

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
