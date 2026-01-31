@extends('layouts.app')

@section('title', 'Input Pembayaran')
@section('page-title', 'Input Pembayaran')
@section('icon-page-title', 'bi-cash-coin')

@push('styles')
    <style>
        /* Card Styles */
        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        /* Kontribusi Grid */
        .kontribusi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        /* Kontribusi Card */
        .kontribusi-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .kontribusi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #105a44;
        }

        .kontribusi-card.active {
            border-color: #105a44;
            background: #f8fff8;
        }

        .kontribusi-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #105a44 0%, #0d8b66 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .kontribusi-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .kontribusi-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .kontribusi-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 5px 0;
            color: white;
        }

        .kontribusi-card-code {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(0, 0, 0, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .kontribusi-card-body {
            padding: 20px;
            flex: 1;
        }

        .kontribusi-card-details {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kontribusi-card-details li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .kontribusi-card-details li:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            text-align: right;
        }

        .kontribusi-card-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
        }

        .select-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #105a44;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .select-btn:hover {
            background: #0d8b66;
            transform: translateY(-1px);
        }

        .select-btn:active {
            transform: translateY(0);
        }

        .select-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .select-btn.loading .btn-text {
            display: none;
        }

        .select-btn.loading .spinner {
            display: inline-block;
        }

        .select-btn .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @@keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #bdbdbd;
        }

        .empty-state h4 {
            margin: 10px 0 5px 0;
            font-weight: 500;
            font-size: 1.2rem;
        }

        .empty-state p {
            margin: 0;
            font-size: 0.95rem;
            color: #888;
        }

        .empty-state .btn {
            margin-top: 15px;
        }

        /* Responsive */
        @@media (max-width: 768px) {
            .kontribusi-grid {
                grid-template-columns: 1fr;
            }

            .kontribusi-card-header,
            .kontribusi-card-body,
            .kontribusi-card-footer {
                padding: 15px;
            }
        }

        /* Animation */
        @@keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .kontribusi-card {
            animation: fadeInUp 0.3s ease forwards;
            opacity: 0;
        }

    </style>
@endpush

@section('content')
<div class="master-container">
    <div class="card">
        <div class="card-header">
            <div style="display: flex; gap: 10px; justify-content: space-between; align-items: center;">
                <h3 class="card-title">Pilih Master Kontribusi</h3>
            </div>
        </div>
        <div class="card-body">
            <!-- Loading State -->
            <div id="loadingState" class="empty-state">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h4>Memuat data kontribusi...</h4>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="bi-inbox"></i>
                <h4>Tidak ada data kontribusi</h4>
                <p>Belum ada kontribusi yang tersedia</p>
            </div>

            <!-- Error State -->
            <div id="errorState" class="empty-state" style="display: none;">
                <i class="bi-exclamation-triangle"></i>
                <h4>Gagal memuat data</h4>
                <p id="errorMessage">Terjadi kesalahan saat memuat data</p>
                <button class="btn btn-primary" onclick="PembayaranApp.loadMasterKontribusi()">
                    <i class="bi-arrow-clockwise"></i> Coba Lagi
                </button>
            </div>

            <!-- Kontribusi Grid -->
            <div class="kontribusi-grid" id="kontribusiGrid" style="display: none;">
                <!-- Data akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // ============================================================================
        // VARIABEL GLOBAL & KONFIGURASI
        // ============================================================================
        let isLoading = false;
        let currentSelection = null;

        const API_ROUTES = {
            masterKontribusi: '{{ route("admin.kelompok.api.input-pembayaran.kontribusi-options") }}',
            createPage: '{{ route("admin.kelompok.input-pembayaran.create") }}'
        };

        // ============================================================================
        // FUNGSI UTAMA - LOAD DATA
        // ============================================================================

        // Load master kontribusi
        async function loadMasterKontribusi() {
            if (isLoading) return;

            showLoadingState();
            isLoading = true;

            try {
                const response = await fetch(API_ROUTES.masterKontribusi);
                const result = await response.json();

                if (result.success) {
                    renderMasterKontribusi(result.data);
                } else {
                    throw new Error(result.message || 'Gagal memuat data');
                }
            } catch (error) {
                console.error('Error loading master kontribusi:', error);
                showErrorState(error.message);
            } finally {
                hideLoadingState();
                isLoading = false;
            }
        }

        // Render master kontribusi cards
        function renderMasterKontribusi(data) {
            const kontribusiGrid = document.getElementById('kontribusiGrid');
            const emptyState = document.getElementById('emptyState');

            if (!data || data.length === 0) {
                showEmptyState();
                return;
            }

            emptyState.style.display = 'none';
            kontribusiGrid.style.display = 'grid';

            const cardsHtml = data.map((item, index) => {
                // Tentukan ikon berdasarkan kode atau nama kontribusi
                let iconClass = getIconClass(item.kode_kontribusi);

                // Buat detail items
                const detailItems = [];

                if (item.deskripsi) {
                    detailItems.push({
                        label: 'Deskripsi',
                        value: truncateText(item.deskripsi, 50)
                    });
                }

                if (item.nominal_default) {
                    detailItems.push({
                        label: 'Nominal Default',
                        value: formatCurrency(item.nominal_default)
                    });
                }

                if (item.is_active !== undefined) {
                    detailItems.push({
                        label: 'Status',
                        value: item.is_active ? 'Aktif' : 'Tidak Aktif'
                    });
                }

                return `
                <div class="kontribusi-card" data-id="${item.master_kontribusi_id}" 
                     style="animation-delay: ${index * 0.05}s">
                    <div class="kontribusi-card-header">
                        <div class="kontribusi-card-icon">
                            <i class="bi ${iconClass}"></i>
                        </div>
                        <h4 class="kontribusi-card-title">${escapeHtml(item.nama_kontribusi)}</h4>
                        <div class="kontribusi-card-code">${escapeHtml(item.kode_kontribusi)}</div>
                    </div>
                    
                    ${detailItems.length > 0 ? `
                    <div class="kontribusi-card-body">
                        <ul class="kontribusi-card-details">
                            ${detailItems.map(detail => `
                                <li>
                                    <span class="detail-label">${detail.label}</span>
                                    <span class="detail-value">${detail.value}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                    ` : ''}
                    
                    <div class="kontribusi-card-footer">
                        <button class="select-btn" onclick="selectKontribusi('${item.master_kontribusi_id}', this)">
                            <span class="btn-text">Pilih Kontribusi</span>
                            <span class="spinner"></span>
                        </button>
                    </div>
                </div>
            `;
            }).join('');

            kontribusiGrid.innerHTML = cardsHtml;

            // Add click event to entire card
            document.querySelectorAll('.kontribusi-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    if (!e.target.closest('.select-btn')) {
                        const button = this.querySelector('.select-btn');
                        const id = this.getAttribute('data-id');
                        selectKontribusi(id, button);
                    }
                });
            });
        }

        // Pilih kontribusi
        async function selectKontribusi(masterId, button) {
            if (isLoading) return;

            // Set loading state pada button
            if (button) {
                button.classList.add('loading');
            }

            try {
                // Navigasi ke halaman input pembayaran
                window.location.href = `${API_ROUTES.createPage}?master_kontribusi_id=${masterId}`;
            } catch (error) {
                console.error('Error selecting kontribusi:', error);
                showToast('Error', error.message, 'error');

                // Reset button state
                if (button) {
                    button.classList.remove('loading');
                }
            }
        }

        // ============================================================================
        // FUNGSI BANTU (HELPER FUNCTIONS)
        // ============================================================================

        // Get icon class based on kontribusi code
        function getIconClass(kode) {
            const iconMap = {
                'INF': 'bi-cash-coin',
                'ZAK': 'bi-currency-exchange',
                'SOD': 'bi-heart',
                'WAK': 'bi-bank',
                'INV': 'bi-graph-up',
                'DON': 'bi-gift'
            };

            // Check prefix
            for (const [prefix, icon] of Object.entries(iconMap)) {
                if (kode.startsWith(prefix)) {
                    return icon;
                }
            }

            // Default icon
            return 'bi-cash-coin';
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Truncate text
        function truncateText(text, maxLength) {
            if (!text || text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }

        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show loading state
        function showLoadingState() {
            document.getElementById('loadingState').style.display = 'flex';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('errorState').style.display = 'none';
            document.getElementById('kontribusiGrid').style.display = 'none';
        }

        function hideLoadingState() {
            document.getElementById('loadingState').style.display = 'none';
        }

        // Show empty state
        function showEmptyState() {
            document.getElementById('emptyState').style.display = 'flex';
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'none';
            document.getElementById('kontribusiGrid').style.display = 'none';
        }

        // Show error state
        function showErrorState(message = 'Terjadi kesalahan saat memuat data') {
            document.getElementById('errorState').style.display = 'flex';
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('kontribusiGrid').style.display = 'none';
        }

        // Toast notification
        function showToast(title, message, type = 'info') {
            if (window.showToast) {
                window.showToast(message, type);
            } else {
                alert(`${title}: ${message}`);
            }
        }

        // ============================================================================
        // PUBLIC API (PembayaranApp)
        // ============================================================================
        const PembayaranApp = {
            loadMasterKontribusi: loadMasterKontribusi,
            selectKontribusi: selectKontribusi
        };

        // ============================================================================
        // START APP
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function () {
            loadMasterKontribusi();

            // Expose ke global scope
            window.PembayaranApp = PembayaranApp;
        });

    </script>
@endpush
