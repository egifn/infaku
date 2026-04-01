@extends('layouts.app')

@section('title', 'Input Pembayaran')
@section('page-title', 'Input Pembayaran')
@section('icon-page-title', 'bi-cash-coin')

@push('styles')
    <style>
        :root {
            --primary-color: #105a44;
            --primary-hover: #0d8b66;
            --primary-light: #e8f5e9;
            --border-color: #e0e0e0;
            --text-primary: #333;
            --text-secondary: #666;
            --text-muted: #888;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        /* Kontribusi List */
        .kontribusi-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .kontribusi-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .kontribusi-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
            transform: translateX(4px);
        }

        .kontribusi-item.active {
            border-color: var(--primary-color);
            background: linear-gradient(to right, var(--primary-light), white);
        }

        .kontribusi-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
            border-radius: 3px 0 0 3px;
        }

        .item-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .item-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .item-content {
            flex: 1;
        }

        .item-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            /* margin-bottom: 0.25rem; */
            flex-wrap: wrap;
        }

        .item-title h4 {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .item-code {
            font-size: 0.7rem;
            color: var(--text-muted);
            background: #f5f5f5;
            padding: 0.2rem 0.5rem;
            border-radius: 2rem;
        }

        .item-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .item-detail {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .item-detail i {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .item-detail .label {
            color: var(--text-muted);
        }

        .item-detail .value {
            font-weight: 600;
            color: var(--text-primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            gap: 0.25rem;
        }

        .status-badge.active {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .status-badge.inactive {
            background: #ffebee;
            color: #d32f2f;
        }

        .item-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-price {
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-color);
            background: var(--primary-light);
            padding: 0.375rem 1rem;
            border-radius: 2rem;
            white-space: nowrap;
        }

        .select-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 2rem;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .select-btn:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .select-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .select-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .select-btn i {
            font-size: 12px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px dashed var(--border-color);
        }

        .empty-state i {
            font-size: 3rem;
            color: #bdbdbd;
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .empty-state p {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* Loading State (match master_kontribusi) */
        .loading-animation {
            height: 20px;
            width: 200px;
            margin: 0 auto 10px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
        }

        /* Loading Skeleton */
        .skeleton-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .skeleton-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        .skeleton-content {
            flex: 1;
        }

        .skeleton-title {
            width: 200px;
            height: 1rem;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .skeleton-detail {
            width: 150px;
            height: 0.75rem;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 0.25rem;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-tabs {
                width: 100%;
                justify-content: center;
            }

            .reset-filter {
                width: 100%;
                justify-content: center;
            }

            .kontribusi-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .item-left {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .item-right {
                width: 100%;
                justify-content: space-between;
            }

            .item-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }

            .item-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fs-5">
                    Transaksi
                </h3>
            </div>
            <div class="card-body py-3">
                <!-- Filter Controls -->
                <div class="table-controls">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="perPageSelect" class="form-select" style="width:auto; padding:5px 0px">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <select id="filterAktif" class="form-select" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari kontribusi...">
                        <i class="bi-search search-icon"></i>
                    </div>
                </div>

                <!-- Stats Cards -->
                {{-- <div class="stats-container" id="statsContainer" style="display: none;">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="stat-info">
                            <h6>Total Kontribusi</h6>
                            <div class="stat-number" id="statTotal">0</div>
                            <div class="stat-detail">Semua kontribusi</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h6>Aktif</h6>
                            <div class="stat-number" id="statActive">0</div>
                            <div class="stat-detail">Kontribusi aktif</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h6>Non-Aktif</h6>
                            <div class="stat-number" id="statInactive">0</div>
                            <div class="stat-detail">Kontribusi non-aktif</div>
                        </div>
                    </div>
                </div> --}}

                <!-- Loading State -->
                <div id="loadingState" class="empty-state">
                    <div class="loading-animation"></div>
                </div>

                <!-- Kontribusi List -->
                <div class="kontribusi-list" id="kontribusiList" style="display: none;"></div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi bi-inbox"></i>
                    <h5>Tidak Ada Data</h5>
                    <p id="emptyMessage">Belum ada kontribusi tersedia.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // ============================================================================
            // KONFIGURASI & STATE
            // ============================================================================
            const CONFIG = {
                api: {
                    masterKontribusi: '{{ route('admin.kelompok.api.input-pembayaran.kontribusi-options') }}',
                    createPage: '{{ route('admin.kelompok.input-pembayaran.create') }}'
                },
                debounceDelay: 500, // Sama dengan halaman lain (500ms)
                defaultPerPage: 10
            };

            // STATE - Semua data aplikasi disimpan di sini
            const State = {
                currentPage: 1,
                totalPages: 1,
                totalRecords: 0,
                searchQuery: '',
                aktifFilter: '',
                perPage: CONFIG.defaultPerPage,
                isLoading: false,
                selectedId: null,

                resetToFirstPage() {
                    this.currentPage = 1;
                },

                updatePagination(data) {
                    this.currentPage = parseInt(data.current_page) || 1;
                    this.totalPages = parseInt(data.last_page) || 1;
                    this.totalRecords = parseInt(data.total) || 0;
                },

                getStatusFilter() {
                    return this.aktifFilter;
                }
            };

            // API Routes
            const API = {
                data: '{{ route('admin.kelompok.api.input-pembayaran.kontribusi-options') }}',
                createPage: '{{ route('admin.kelompok.input-pembayaran.create') }}'
            };

            // ============================================================================
            // HELPER FUNCTIONS
            // ============================================================================
            const Helpers = {
                // Escape HTML - PENTING UNTUK KEAMANAN!
                escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                },

                // Format currency
                formatCurrency(amount) {
                    if (!amount && amount !== 0) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount).replace('Rp', 'Rp');
                },

                // Truncate text
                truncateText(text, maxLength = 50) {
                    if (!text || text.length <= maxLength) return text;
                    return text.substring(0, maxLength) + '...';
                },

                // Get icon based on kode
                getIconClass(kode) {
                    const iconMap = {
                        'INF': 'bi-cash-coin',
                        'ZAK': 'bi-currency-exchange',
                        'SOD': 'bi-heart',
                        'WAK': 'bi-bank',
                        'INV': 'bi-graph-up',
                        'DON': 'bi-gift'
                    };

                    for (const [prefix, icon] of Object.entries(iconMap)) {
                        if (kode.startsWith(prefix)) return icon;
                    }
                    return 'bi-cash-coin';
                },

                // Show toast message
                showToast(message, type = 'info') {
                    if (window.showToast) {
                        window.showToast(message, type);
                    } else {
                        alert(message);
                    }
                },

                // Set button loading state
                setButtonLoading(button, isLoading) {
                    if (!button) return;

                    if (isLoading) {
                        button.disabled = true;
                        button.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    <span>Loading...</span>
                `;
                    } else {
                        button.disabled = false;
                        button.innerHTML = `
                    <i class="bi bi-check-circle"></i>
                    <span>Pilih</span>
                `;
                    }
                },

                // Build query string - SAMA DENGAN HALAMAN SEBELUMNYA
                buildQuery() {
                    const params = new URLSearchParams({
                        page: State.currentPage,
                        per_page: State.perPage
                    });

                    if (State.searchQuery) {
                        params.append('search', State.searchQuery);
                    }

                    const statusFilter = State.getStatusFilter();
                    if (statusFilter !== '') {
                        params.append('is_aktif', statusFilter);
                    }

                    return params.toString();
                },

                // Format nomor urut
                formatRowNumber(index) {
                    return ((State.currentPage - 1) * State.perPage) + index + 1;
                }
            };

            // ============================================================================
            // API CALLS - LANGSUNG KE SERVER (SAMA DENGAN HALAMAN LAIN)
            // ============================================================================
            const Api = {
                async getMasterKontribusi() {
                    if (State.isLoading) return null;

                    State.isLoading = true;
                    UI.showLoading();

                    try {
                        const url = `${API.data}?${Helpers.buildQuery()}`;
                        console.log('Fetching:', url); // Untuk debugging

                        const response = await fetch(url);
                        const result = await response.json();

                        if (!result.success) {
                            throw new Error(result.message || 'Gagal memuat data');
                        }

                        return result;
                    } catch (error) {
                        console.error('Error loading data:', error);
                        Helpers.showToast(error.message, 'error');
                        return null;
                    } finally {
                        State.isLoading = false;
                        // UI.hideLoading akan dipanggil di renderTable
                    }
                }
            };

            // ============================================================================
            // UI RENDER - SAMA DENGAN HALAMAN SEBELUMNYA
            // ============================================================================
            const UI = {
                // Elements
                elements: {
                    loadingState: document.getElementById('loadingState'),
                    // statsContainer: document.getElementById('statsContainer'),
                    kontribusiList: document.getElementById('kontribusiList'),
                    emptyState: document.getElementById('emptyState'),
                    emptyMessage: document.getElementById('emptyMessage'),
                    searchInput: document.getElementById('searchInput'),
                    perPageSelect: document.getElementById('perPageSelect'),
                    filterAktif: document.getElementById('filterAktif'),
                    // statTotal: document.getElementById('statTotal'),
                    // statActive: document.getElementById('statActive'),
                    // statInactive: document.getElementById('statInactive')
                },

                // Show loading state
                showLoading() {
                    this.elements.loadingState.style.display = 'block';
                    // this.elements.statsContainer.style.display = 'none';
                    this.elements.kontribusiList.style.display = 'none';
                    this.elements.emptyState.style.display = 'none';
                },

                // Hide loading state
                hideLoading() {
                    this.elements.loadingState.style.display = 'none';
                },

                // Show content
                showContent() {
                    this.hideLoading();
                    // this.elements.statsContainer.style.display = 'grid';
                },

                // Render table - SAMA DENGAN HALAMAN LAIN
                renderTable(data) {
                    if (!data || data.length === 0) {
                        this.showEmpty();
                        return;
                    }

                    this.hideLoading();
                    // this.elements.statsContainer.style.display = 'grid';
                    this.elements.emptyState.style.display = 'none';
                    this.elements.kontribusiList.style.display = 'flex';

                    // Update stats akan dipanggil terpisah
                    const rows = data.map((item, index) => this.createListItem(item, index)).join('');
                    this.elements.kontribusiList.innerHTML = rows;

                    this.attachItemEvents();
                },

                // Show empty state
                showEmpty() {
                    this.elements.kontribusiList.innerHTML = '';
                    this.elements.kontribusiList.style.display = 'none';
                    // this.elements.statsContainer.style.display = 'grid';
                    this.elements.emptyState.style.display = 'block';
                    this.hideLoading();

                    this.updateEmptyMessage();
                },

                // Update empty message
                updateEmptyMessage() {
                    if (State.searchQuery || State.aktifFilter !== '') {
                        this.elements.emptyMessage.textContent =
                            'Tidak ditemukan kontribusi dengan kriteria tersebut.';
                    } else {
                        this.elements.emptyMessage.textContent = 'Belum ada kontribusi tersedia.';
                    }
                },

                // Update statistics - SAMA DENGAN HALAMAN LAIN
                updateStats(data) {
                    if (!data) return;

                    // Data from API response
                    const total = data.total || 0;
                    const aktif = data.aktif_count || 0;
                    const nonAktif = total - aktif;

                    // Stats badges sudah dihapus pada filter baru, jadi hanya update jika elemen ada
                    if (this.elements.totalCount) this.elements.totalCount.textContent = total;
                    if (this.elements.activeCount) this.elements.activeCount.textContent = aktif;
                    if (this.elements.inactiveCount) this.elements.inactiveCount.textContent = nonAktif;

                    // this.elements.statTotal.textContent = total;
                    // this.elements.statActive.textContent = aktif;
                    // this.elements.statInactive.textContent = nonAktif;
                },

                // Create list item HTML
                createListItem(item, index) {
                    const no = Helpers.formatRowNumber(index);
                    const statusClass = item.is_aktif ? 'active' : 'inactive';
                    const statusText = item.is_aktif ? 'Aktif' : 'Non-Aktif';
                    const nominal = item.nominal_default ? Helpers.formatCurrency(item.nominal_default) : '-';
                    const iconClass = Helpers.getIconClass(item.kode_kontribusi);
                    const isSelected = State.selectedId === item.id ? 'active' : '';
                    console.log(item);


                    return `
                <div class="kontribusi-item ${isSelected}" data-id="${item.id}">
                    <div class="item-left">
                        <div class="item-icon">
                            <i class="bi ${iconClass}"></i>
                        </div>
                        <div class="item-content">
                            <div class="item-title">
                                <h4>${Helpers.escapeHtml(item.nama_kontribusi)}</h4>
                                
                            </div>
                            <div class="item-details">
                                ${item.deskripsi ? `
                                                    <div class="item-detail">
                                                        <i class="bi bi-info-circle"></i>
                                                        <span class="label">Deskripsi:</span>
                                                        <span class="value">${Helpers.truncateText(Helpers.escapeHtml(item.deskripsi))}</span>
                                                    </div>
                                                ` : ''}
                                <div class="item-detail">
                                    <span class="label">Kode:</span>
                                    <span class="item-code">${Helpers.escapeHtml(item.kode_kontribusi)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item-right">
                        <span class="status-badge ${statusClass}">
                                    <i class="bi bi-${item.is_aktif ? 'check-circle' : 'x-circle'}"></i>
                                    ${statusText}
                                </span>
                        <button class="select-btn" onclick="PembayaranApp.selectKontribusi('${item.id}', this)">
                            <i class="bi bi-check-circle"></i>
                            <span>Pilih</span>
                        </button>
                    </div>
                </div>
            `;
                },

                // Attach click events to items
                attachItemEvents() {
                    document.querySelectorAll('.kontribusi-item').forEach(item => {
                        item.addEventListener('click', function(e) {
                            if (!e.target.closest('.select-btn')) {
                                const button = this.querySelector('.select-btn');
                                const id = this.getAttribute('data-id');
                                PembayaranApp.selectKontribusi(id, button);
                            }
                        });
                    });
                },

                // Reset search input
                resetSearchInput() {
                    this.elements.searchInput.value = '';
                }
            };

            // ============================================================================
            // MAIN APP - SAMA DENGAN HALAMAN SEBELUMNYA
            // ============================================================================
            const PembayaranApp = {
                searchTimeout: null,

                // Load data dari server - SAMA DENGAN HALAMAN LAIN
                async loadData(page = null) {
                    if (State.isLoading) return;

                    if (page !== null && page >= 1) {
                        State.currentPage = page;
                    }

                    UI.showLoading();

                    try {
                        const result = await Api.getMasterKontribusi();

                        if (result?.success) {
                            State.updatePagination(result);
                            UI.updateStats(result);
                            UI.renderTable(result.data);
                        } else {
                            UI.showEmpty();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        UI.showEmpty();
                    }
                },

                // Search data - PANGGIL API LAGI (IN YANG BEDA!)
                searchData(term) {
                    State.searchQuery = term;
                    State.resetToFirstPage();
                    this.loadData();
                },

                // Debounced search
                debounceSearch(term) {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.searchData(term);
                    }, CONFIG.debounceDelay);
                },

                // Reset filter - PANGGIL API LAGI
                resetFilter() {
                    State.aktifFilter = '';
                    State.searchQuery = '';
                    State.resetToFirstPage();

                    UI.resetSearchInput();
                    if (UI.elements.filterAktif) UI.elements.filterAktif.value = '';
                    this.loadData();
                },

                // Select kontribusi
                async selectKontribusi(masterId, button) {
                    if (State.isLoading) return;

                    // Set selected state
                    State.selectedId = masterId;

                    // Update UI to show selected item
                    document.querySelectorAll('.kontribusi-item').forEach(item => {
                        if (item.getAttribute('data-id') === masterId) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });

                    // Show loading on button
                    Helpers.setButtonLoading(button, true);

                    try {
                        // Navigate to create page
                        window.location.href = `${API.createPage}?master_kontribusi_id=${masterId}`;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.setButtonLoading(button, false);
                        Helpers.showToast('Gagal melanjutkan ke halaman pembayaran', 'error');
                    }
                },

                // Go to page - untuk pagination
                goToPage(page) {
                    if (page < 1 || page > State.totalPages || page === State.currentPage) {
                        return;
                    }
                    this.loadData(page);
                }
            };

            // ============================================================================
            // EVENT LISTENERS
            // ============================================================================
            function setupEventListeners() {
                const searchInput = document.getElementById('searchInput');
                const perPageSelect = document.getElementById('perPageSelect');
                const filterAktif = document.getElementById('filterAktif');

                // Search dengan debounce - INI YAKIN AKAN MEMANGGIL API
                searchInput.addEventListener('input', (e) => {
                    PembayaranApp.debounceSearch(e.target.value.trim());
                });

                // Prevent form submission on Enter
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });

                if (perPageSelect) {
                    perPageSelect.addEventListener('change', (e) => {
                        State.perPage = parseInt(e.target.value, 10) || CONFIG.defaultPerPage;
                        State.resetToFirstPage();
                        PembayaranApp.loadData();
                    });
                }

                if (filterAktif) {
                    filterAktif.addEventListener('change', (e) => {
                        State.aktifFilter = e.target.value;
                        State.resetToFirstPage();
                        PembayaranApp.loadData();
                    });
                }

                // Pagination buttons
                document.getElementById('prevPage')?.addEventListener('click', function() {
                    if (State.currentPage > 1) {
                        PembayaranApp.goToPage(State.currentPage - 1);
                    }
                });

                document.getElementById('nextPage')?.addEventListener('click', function() {
                    if (State.currentPage < State.totalPages) {
                        PembayaranApp.goToPage(State.currentPage + 1);
                    }
                });
            }

            // ============================================================================
            // INITIALIZE
            // ============================================================================
            async function initializeApp() {
                setupEventListeners();
                await PembayaranApp.loadData(1);
            }

            // ============================================================================
            // START APP
            // ============================================================================
            document.addEventListener('DOMContentLoaded', function() {
                initializeApp();

                // Expose ke global scope
                window.PembayaranApp = PembayaranApp;

                // Untuk debugging
                window.debugState = function() {
                    console.log('State:', {
                        currentPage: State.currentPage,
                        totalPages: State.totalPages,
                        searchQuery: State.searchQuery,
                        aktifFilter: State.aktifFilter,
                        isLoading: State.isLoading
                    });
                };
            });
        })();
    </script>
@endpush
