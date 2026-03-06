<!DOCTYPE html>
<html lang="id">

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
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

    @stack('styles')
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="header-brand">
                <div class="header-logo-frame">I</div>
            </div>

            <div class="search">
                <form action="#" method="GET" class="search-form">
                    @csrf
                    <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}"
                        class="search-input" id="global-search">
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="header-right">
                <span class="user-name">{{ session('user.username') }}</span>
                <div class="profile">
                    <div class="profile-img-wrapper dropdown hover-dropdown">
                        <img src="{{ asset('img/default-avatar.png') }}" alt="">
                        <div class="dropdown-content">
                            <div class="dropdown-header">
                                {{ session('user.username') ?? '-' }}
                            </div>
                            <a href="#">
                                <i class="bi bi-person"></i>Profil
                            </a>
                            <a href="#">
                                <i class="bi bi-gear"></i>Setting
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                    title="Dashboard">
                    <i class="bi bi-bar-chart"></i>
                    <span class="menu-text">Dashboard</span>
                </a>

                <!-- Header Menu: MASTER JAMAAH -->
                <div class="menu-header">
                    <span class="full-text">MASTER JAMAAH</span>
                    <span class="short-text">MJ</span>
                </div>

                <a href="{{ route('admin.kelompok.data-jamaah.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.data-jamaah.*') ? 'active' : '' }}"
                    title="Data Jamaah">
                    <i class="bi bi-person-vcard"></i>
                    <span class="menu-text">Data Jamaah</span>
                </a>

                <a href="{{ route('admin.kelompok.data-keluarga.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.data-keluarga.*') ? 'active' : '' }}"
                    title="Data Keluarga">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Data Keluarga</span>
                </a>

                <!-- Header Menu: MASTER KONTRIBUSI -->
                <div class="menu-header">
                    <span class="full-text">MASTER KONTRIBUSI</span>
                    <span class="short-text">MK</span>
                </div>

                <a href="{{ route('admin.kelompok.master-kontribusi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.master-kontribusi.*') ? 'active' : '' }}"
                    title="Data Kontribusi">
                    <i class="bi bi-cash-stack"></i>
                    <span class="menu-text">Data Kontribusi</span>
                </a>

                <a href="{{ route('admin.kelompok.sub-kontribusi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.sub-kontribusi.*') ? 'active' : '' }}"
                    title="Data Sub Kontribusi">
                    <i class="bi bi-list-nested"></i>
                    <span class="menu-text">Data Sub Kontribusi</span>
                </a>

                <!-- Header Menu: TRANSAKSI -->
                <div class="menu-header">
                    <span class="full-text">TRANSAKSI</span>
                    <span class="short-text">TR</span>
                </div>

                <a href="{{ route('admin.kelompok.input-pembayaran.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.input-pembayaran.*') ? 'active' : '' }}"
                    title="Input Pembayaran">
                    <i class="bi bi-wallet2"></i>
                    <span class="menu-text">Input Pembayaran</span>
                </a>

                <a href="{{ route('admin.kelompok.riwayat-transaksi.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.riwayat-transaksi.*') ? 'active' : '' }}"
                    title="Riwayat Transaksi">
                    <i class="bi bi-clock-history"></i>
                    <span class="menu-text">Riwayat Transaksi</span>
                </a>

                <!-- Header Menu: LAPORAN -->
                <div class="menu-header">
                    <span class="full-text">LAPORAN</span>
                    <span class="short-text">LP</span>
                </div>

                <a href="{{ route('admin.kelompok.laporan.index') }}"
                    class="menu-item {{ request()->routeIs('admin.kelompok.laporan.*') ? 'active' : '' }}"
                    title="Laporan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="menu-text">Laporan</span>
                </a>
            </div>
        </div>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Navbar Section -->
        <div class="navbar" id="navbar">
            <i class="bi bi-grid navbar-toggle-btn" id="toggleNavbar"></i>
            <div class="navbar-title">
                @yield('page-title', 'Dashboard')
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
            <div class="toast-container" id="toastContainer">
                @if (session('success'))
                    <div class="toast success show" data-toast>
                        <div class="toast-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="toast-content">
                            <div class="toast-title">Sukses</div>
                            <div class="toast-message">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="toast-close" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="toast error show" data-toast>
                        <div class="toast-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <div class="toast-content">
                            <div class="toast-title">Error</div>
                            <div class="toast-message">{{ session('error') }}</div>
                        </div>
                        <button type="button" class="toast-close" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <div class="master-container">
                @yield('content')
            </div>

            <!-- Modal Container -->
            <div id="modalContainer"></div>
        </main>
    </div>

    <!-- Main JavaScript - Single File -->
    <script>
        (function() {
            'use strict';

            // ==================== CONFIGURATION ====================
            const CONFIG = {
                toastDuration: 5000,
                sidebarCollapsedKey: 'sidebarCollapsed',
                mobileBreakpoint: 576
            };

            // ==================== DOM READY INIT ====================
            document.addEventListener('DOMContentLoaded', function() {
                initializeSidebar();
                initializeDropdowns();
                initializeToasts();
                initializeUtilities();

                // Force sidebar collapsed on mobile
                handleMobileSidebar();
            });

            function initializeSidebar() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('toggleSidebar');
                const navToggleBtn = document.getElementById('toggleNavbar');
                const mainContent = document.getElementById('mainContent');
                const navbar = document.getElementById('navbar');
                const sidebarHeader = document.querySelector('.sidebar-header');
                const sidebarToggle = document.querySelector('.sidebar-toggle');
                const navbarToggle = document.querySelector('.navbar-toggle-btn');

                if (!sidebar) return;

                // Toggle function
                function toggleSidebar() {
                    const sidebar = document.getElementById('sidebar');
                    const isMobile = window.innerWidth <= CONFIG.mobileBreakpoint;

                    if (isMobile) {
                        // ========== MOBILE MODE ==========
                        // Toggle class khusus mobile
                        sidebar.classList.toggle('sidebar-open');
                        sidebar.classList.toggle('collapsed');

                        // Lock/unlock body scroll
                        if (sidebar.classList.contains('sidebar-open')) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }

                        // Update overlay (opsional, bisa melalui CSS)
                        const overlay = document.getElementById('sidebarOverlay');
                        if (overlay) {
                            if (sidebar.classList.contains('sidebar-open')) {
                                overlay.style.display = 'block';
                            } else {
                                overlay.style.display = 'none';
                            }
                        }
                    } else {
                        // ========== DESKTOP MODE ==========
                        sidebar.classList.toggle('collapsed');
                        if (sidebarHeader) sidebarHeader.classList.toggle('collapsed');
                        if (sidebarToggle) sidebarToggle.classList.toggle('collapsed');
                        if (navbarToggle) {
                            navbarToggle.classList.toggle('collapsed');
                            navbarToggle.classList.toggle('expanded');
                        }
                        if (mainContent) mainContent.classList.toggle('expanded');
                        if (navbar) navbar.classList.toggle('expanded');

                        // Save state hanya untuk desktop
                        if (window.innerWidth > CONFIG.mobileBreakpoint) {
                            const isCollapsed = sidebar.classList.contains('collapsed');
                            localStorage.setItem(CONFIG.sidebarCollapsedKey, isCollapsed);
                        }

                        // Update menu headers
                        updateMenuHeaders();

                        // Close submenus if collapsed
                        if (sidebar.classList.contains('collapsed')) closeAllSubmenus();
                    }
                }

                // Load state from localStorage (ONLY FOR DESKTOP)
                function loadSidebarState() {
                    const isMobile = window.innerWidth <= CONFIG.mobileBreakpoint;

                    if (isMobile) {
                        // MOBILE: SELALU COLLAPSED
                        sidebar.classList.add('collapsed');
                        if (sidebarHeader) sidebarHeader.classList.add('collapsed');
                        if (sidebarToggle) sidebarToggle.classList.add('collapsed');
                        if (navbarToggle) {
                            navbarToggle.classList.add('collapsed');
                            navbarToggle.classList.add('expanded');
                        }
                        if (mainContent) mainContent.classList.add('expanded');
                        if (navbar) navbar.classList.add('expanded');
                    } else {
                        // DESKTOP: PAKAI LOCALSTORAGE
                        const isCollapsed = localStorage.getItem(CONFIG.sidebarCollapsedKey) === 'true';
                        if (isCollapsed) {
                            sidebar.classList.add('collapsed');
                            if (sidebarHeader) sidebarHeader.classList.add('collapsed');
                            if (sidebarToggle) sidebarToggle.classList.add('collapsed');
                            if (navbarToggle) {
                                navbarToggle.classList.add('collapsed');
                                navbarToggle.classList.add('expanded');
                            }
                            if (mainContent) mainContent.classList.add('expanded');
                            if (navbar) navbar.classList.add('expanded');
                        }
                    }
                }

                // INITIAL LOAD
                loadSidebarState();

                // Event listeners
                if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
                if (navToggleBtn) navToggleBtn.addEventListener('click', toggleSidebar);

                // Submenu handling
                initializeSubmenus(sidebar);

                // Menu headers
                updateMenuHeaders();

                // Handle resize
                window.addEventListener('resize', debounce(function() {
                    const isMobile = window.innerWidth <= CONFIG.mobileBreakpoint;

                    if (isMobile) {
                        // PAKSA COLLAPSED DI MOBILE
                        sidebar.classList.add('collapsed');
                        if (sidebarHeader) sidebarHeader.classList.add('collapsed');
                        if (sidebarToggle) sidebarToggle.classList.add('collapsed');
                        if (navbarToggle) {
                            navbarToggle.classList.add('collapsed');
                            navbarToggle.classList.add('expanded');
                        }
                        if (mainContent) mainContent.classList.add('expanded');
                        if (navbar) navbar.classList.add('expanded');
                        closeAllSubmenus();
                    } else {
                        // DI DESKTOP, KEMBALIKAN KE STATE SEBELUMNYA (TAPI JANGAN OTOMATIS)
                        // Biarkan user yang toggle sendiri
                    }
                    updateMenuHeaders();
                }, 150));
            }

            function initializeSubmenus(sidebar) {
                const submenuItems = document.querySelectorAll('[data-submenu]');

                submenuItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (sidebar.classList.contains('collapsed')) {
                            closeAllSubmenus();
                            return;
                        }

                        const submenuId = this.dataset.submenu;
                        const submenu = document.getElementById(submenuId);

                        if (!submenu) return;

                        const isOpen = submenu.classList.contains('open');
                        closeAllSubmenus();

                        if (!isOpen) {
                            submenu.classList.add('open');
                            this.classList.add('open');
                        }
                    });
                });

                // Observer for sidebar collapse
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class' &&
                            mutation.target.classList.contains('collapsed')) {
                            closeAllSubmenus();
                        }
                    });
                });

                observer.observe(sidebar, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }

            function closeAllSubmenus() {
                document.querySelectorAll('.submenu').forEach(s => s.classList.remove('open'));
                document.querySelectorAll('[data-submenu]').forEach(b => b.classList.remove('open'));
            }

            function updateMenuHeaders() {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar) return;

                const isCollapsed = sidebar.classList.contains('collapsed');

                document.querySelectorAll('.menu-header').forEach(header => {
                    const fullText = header.querySelector('.full-text');
                    const shortText = header.querySelector('.short-text');

                    if (fullText && shortText) {
                        fullText.style.display = isCollapsed ? 'none' : 'inline';
                        shortText.style.display = isCollapsed ? 'inline' : 'none';
                    }
                });
            }

            function handleMobileSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const toggleBtn = document.getElementById('toggleNavbar');

                if (!sidebar) return;

                const isMobile = window.innerWidth <= CONFIG.mobileBreakpoint;

                if (isMobile) {
                    // Pastikan sidebar tersembunyi di awal
                    sidebar.classList.remove('sidebar-open');
                    sidebar.classList.add('collapsed');

                    // Event listener untuk tombol toggle
                    if (toggleBtn) {
                        toggleBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            sidebar.classList.toggle('sidebar-open');
                            sidebar.classList.toggle('collapsed');

                            // Prevent body scroll when sidebar is open
                            if (sidebar.classList.contains('sidebar-open')) {
                                document.body.style.overflow = 'hidden';
                            } else {
                                document.body.style.overflow = '';
                            }
                        });
                    }

                    // Event listener untuk overlay
                    if (overlay) {
                        overlay.addEventListener('click', function() {
                            sidebar.classList.remove('sidebar-open');
                            sidebar.classList.add('collapsed');
                            document.body.style.overflow = '';
                        });
                    }
                } else {
                    // Desktop: hapus event listener dan reset
                    document.body.style.overflow = '';
                    sidebar.classList.remove('sidebar-open');

                    // Kembalikan ke state localStorage
                    const isCollapsed = localStorage.getItem(CONFIG.sidebarCollapsedKey) === 'true';
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                }
            }

            function handleResize() {
                handleMobileSidebar();
                updateMenuHeaders();
            }

            // ==================== DROPDOWN COMPONENT ====================
            function initializeDropdowns() {
                // Dropdown buttons
                document.querySelectorAll('.dropbtn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = this.nextElementSibling;
                        if (dropdown?.classList.contains('dropdown-content')) {
                            dropdown.classList.toggle('show');
                        }
                    });
                });

                // Close dropdowns on outside click
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        document.querySelectorAll('.dropdown-content.show').forEach(d => {
                            d.classList.remove('show');
                        });
                    }
                });

                // Close on escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        document.querySelectorAll('.dropdown-content.show').forEach(d => {
                            d.classList.remove('show');
                        });
                    }
                });
            }

            // ==================== TOAST COMPONENT ====================
            function initializeToasts() {
                // Auto-hide toasts
                setTimeout(() => {
                    document.querySelectorAll('.toast.show').forEach(toast => {
                        hideToast(toast);
                    });
                }, CONFIG.toastDuration);

                // Close buttons
                document.querySelectorAll('.toast-close').forEach(btn => {
                    btn.addEventListener('click', function() {
                        hideToast(this.closest('.toast'));
                    });
                });
            }

            function hideToast(toast) {
                if (!toast) return;
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }

            // Toast Manager Class
            class ToastManager {
                constructor() {
                    this.container = document.getElementById('toastContainer') || this.createContainer();
                }

                createContainer() {
                    const container = document.createElement('div');
                    container.id = 'toastContainer';
                    container.className = 'toast-container';
                    document.body.appendChild(container);
                    return container;
                }

                show(message, type = 'info', title = null) {
                    const toast = document.createElement('div');
                    toast.className = `toast ${type} show`;

                    toast.innerHTML = `
                        <div class="toast-icon">
                            <i class="bi ${this.getIcon(type)}"></i>
                        </div>
                        <div class="toast-content">
                            <div class="toast-title">${title || this.getTitle(type)}</div>
                            <div class="toast-message">${message}</div>
                        </div>
                        <button type="button" class="toast-close">
                            <i class="bi bi-x"></i>
                        </button>
                    `;

                    this.container.appendChild(toast);

                    // Close button
                    toast.querySelector('.toast-close').addEventListener('click', () => {
                        hideToast(toast);
                    });

                    // Auto hide
                    setTimeout(() => hideToast(toast), CONFIG.toastDuration);

                    return toast;
                }

                success(message, title = 'Sukses') {
                    return this.show(message, 'success', title);
                }

                error(message, title = 'Error') {
                    return this.show(message, 'error', title);
                }

                getIcon(type) {
                    const icons = {
                        success: 'bi-check-circle',
                        error: 'bi-exclamation-circle',
                        warning: 'bi-exclamation-triangle',
                        info: 'bi-info-circle'
                    };
                    return icons[type] || 'bi-info-circle';
                }

                getTitle(type) {
                    const titles = {
                        success: 'Sukses',
                        error: 'Error',
                        warning: 'Peringatan',
                        info: 'Informasi'
                    };
                    return titles[type] || 'Informasi';
                }
            }

            // ==================== MODAL COMPONENT ====================
            class ModalManager {
                constructor() {
                    this.container = document.getElementById('modalContainer');
                    this.activeModal = null;
                    this.init();
                }

                init() {
                    document.addEventListener('click', (e) => {
                        const trigger = e.target.closest('[data-modal]');
                        if (trigger) {
                            e.preventDefault();
                            this.show(trigger.dataset.modal);
                        }

                        // Close modal
                        if (e.target.classList.contains('modal-close') ||
                            e.target.closest('.modal-close') ||
                            (e.target.classList.contains('modal') && e.target !== this.container)) {
                            this.hide();
                        }
                    });

                    // Close on escape
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && this.activeModal) {
                            this.hide();
                        }
                    });
                }

                show(modalId) {
                    this.hide();

                    const modal = document.createElement('div');
                    modal.className = 'modal show';
                    modal.innerHTML = `
                        <div class="modal-dialog">
                            <div class="modal-header">
                                <h3 class="modal-title">Loading...</h3>
                                <button class="modal-close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">Memuat konten...</div>
                            </div>
                        </div>
                    `;

                    this.container.appendChild(modal);
                    this.activeModal = modal;

                    // Load content
                    this.loadContent(modalId, modal);
                }

                loadContent(modalId, modal) {
                    const url = this.getModalUrl(modalId);

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const modalBody = modal.querySelector('.modal-body');
                            if (modalBody) modalBody.innerHTML = html;
                        })
                        .catch(() => {
                            const modalBody = modal.querySelector('.modal-body');
                            if (modalBody) {
                                modalBody.innerHTML =
                                    '<div class="alert alert-danger">Gagal memuat konten</div>';
                            }
                        });
                }

                getModalUrl(modalId) {
                    const urls = {
                        'create-jamaah': '/admin/kelompok/data-jamaah/create',
                        'edit-jamaah': '/admin/kelompok/data-jamaah/{id}/edit',
                        'create-transaksi': '/admin/kelompok/transaksi/create'
                    };
                    return urls[modalId] || `/${modalId}`;
                }

                hide() {
                    if (this.activeModal) {
                        this.activeModal.classList.remove('show');
                        setTimeout(() => {
                            if (this.activeModal?.parentNode) {
                                this.activeModal.remove();
                                this.activeModal = null;
                            }
                        }, 300);
                    }
                }
            }

            // ==================== UTILITIES ====================
            function initializeUtilities() {
                // Tooltips
                document.querySelectorAll('[title]').forEach(el => {
                    if (!el.closest('.menu-item')) { // Skip menu items (they have their own tooltip)
                        el.addEventListener('mouseenter', showTooltip);
                        el.addEventListener('mouseleave', removeTooltip);
                    }
                });

                // Delete confirmation
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                            e.preventDefault();
                        }
                    });
                });
            }

            function showTooltip(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = this.title;
                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = `${rect.left + rect.width / 2}px`;
                tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;

                this._tooltip = tooltip;
            }

            function removeTooltip() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    delete this._tooltip;
                }
            }

            // Utility functions
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // ==================== EXPOSE GLOBALLY ====================
            window.toastManager = new ToastManager();
            window.modalManager = new ModalManager();

            // Global helper functions
            window.formatCurrency = function(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            };

            window.formatDate = function(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            };

            window.showToast = function(message, type = 'info') {
                return window.toastManager.show(message, type);
            };

            window.showSuccess = function(message) {
                return window.toastManager.success(message);
            };

            window.showError = function(message) {
                return window.toastManager.error(message);
            };

            window.performSearch = function(query) {
                console.log('Searching for:', query);
            };
        })();
    </script>

    @stack('scripts')
</body>

</html>
