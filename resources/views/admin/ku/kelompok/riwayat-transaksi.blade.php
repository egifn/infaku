@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('icon-page-title', 'bi-clock-history')

@push('styles')
    <style>
        .riwayat-container {
            padding: 5px;
        }

        /* Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }

        .filter-control {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            background: white;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-card.primary {
            border-left: 4px solid #105a44;
        }

        .summary-card.success {
            border-left: 4px solid #28a745;
        }

        .summary-card.warning {
            border-left: 4px solid #ffc107;
        }

        .summary-card.info {
            border-left: 4px solid #17a2b8;
        }

        .summary-title {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .summary-subtext {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .data-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            font-size: 12px;
            white-space: nowrap;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
            vertical-align: top;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary {
            background: #d1e7dd;
            color: #0f5132;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-dialog {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Detail View */
        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-title {
            font-size: 14px;
            font-weight: 600;
            color: #105a44;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 0px 23px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        /* Sub Kontribusi Table in Detail */
        .sub-kontribusi-detail {
            border-radius: 6px;
            padding: 0px 22px;
            margin-top: 10px;
        }

        .sub-kontribusi-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 3px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .sub-kontribusi-table th {
            background: #e9ecef;
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 600;
            text-align: left;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        .sub-kontribusi-table td {
            padding: 10px 12px;
            font-size: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .sub-kontribusi-table tr:last-child td {
            border-bottom: none;
        }

        .sub-kontribusi-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Loading and Empty States */
        .loading-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: #7a7a7a;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .page-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-btn:hover:not(:disabled) {
            background: #f8f9fa;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            font-size: 12px;
            color: #666;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
            max-width: 350px;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            overflow: hidden;
            animation: slideInRight 0.3s ease;
            border-left: 4px solid #17a2b8;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.hide {
            animation: slideOutRight 0.3s ease forwards;
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-header {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 10px;
        }

        .toast.success {
            border-left-color: #28a745;
        }

        .toast.success .toast-icon {
            background: #d4edda;
            color: #155724;
        }

        .toast.error {
            border-left-color: #dc3545;
        }

        .toast.error .toast-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .toast.warning {
            border-left-color: #ffc107;
        }

        .toast.warning .toast-icon {
            background: #fff3cd;
            color: #856404;
        }

        .toast.info {
            border-left-color: #17a2b8;
        }

        .toast.info .toast-icon {
            background: #d1ecf1;
            color: #0c5460;
        }

        .toast-title {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: #999;
            font-size: 16px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .toast-close:hover {
            color: #666;
        }

        .toast-body {
            padding: 15px;
            font-size: 13px;
            color: #666;
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .summary-cards {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="riwayat-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Transaksi Pembayaran</h3>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Tanggal Mulai</label>
                            <input type="date" class="filter-control" id="filterStartDate">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Tanggal Selesai</label>
                            <input type="date" class="filter-control" id="filterEndDate">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-control" id="filterStatus">
                                <option value="ALL">Semua Status</option>
                                <option value="PENDING">Pending</option>
                                <option value="VERIFIED">Verified</option>
                                <option value="REJECTED">Rejected</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Metode Bayar</label>
                            <select class="filter-control" id="filterMetodeBayar">
                                <option value="ALL">Semua Metode</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="TRANSFER">TRANSFER</option>
                                <option value="QRIS">QRIS</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Pencarian</label>
                            <input type="text" class="filter-control" id="filterSearch"
                                placeholder="Cari kode transaksi, nama jamaah, NIK...">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button class="btn btn-secondary" onclick="resetFilters()">
                            <i class="bi-arrow-clockwise"></i> Reset Filter
                        </button>
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="bi-filter"></i> Terapkan Filter
                        </button>
                        <button class="btn btn-export" onclick="exportData()">
                            <i class="bi-download"></i> Export CSV
                        </button>
                        <button class="btn btn-print" onclick="printData()">
                            <i class="bi-printer"></i> Cetak
                        </button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards" id="summaryCards" style="display: none;">
                    <div class="summary-card primary">
                        <div class="summary-title">Total Transaksi</div>
                        <div class="summary-value" id="totalTransaksi">0</div>
                        <div class="summary-subtext">Jumlah transaksi</div>
                    </div>
                    <div class="summary-card success">
                        <div class="summary-title">Total Pendapatan</div>
                        <div class="summary-value" id="totalPendapatan">Rp 0</div>
                        <div class="summary-subtext">Total semua transaksi</div>
                    </div>
                    <div class="summary-card info">
                        <div class="summary-title">Terverifikasi</div>
                        <div class="summary-value" id="totalVerified">Rp 0</div>
                        <div class="summary-subtext">Transaksi verified</div>
                    </div>
                    <div class="summary-card warning">
                        <div class="summary-title">Pending</div>
                        <div class="summary-value" id="totalPending">Rp 0</div>
                        <div class="summary-subtext">Menunggu verifikasi</div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-container">
                    <table class="data-table" id="transaksiTable">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jamaah</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading State -->
                <div class="loading-state" id="loadingState">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data transaksi...</p>
                </div>

                <!-- Empty State -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <i class="bi-clock-history"></i>
                    <h4>Belum ada transaksi</h4>
                    {{-- <p>Tidak ada data transaksi yang ditemukan.</p> --}}

                </div>

                <!-- Pagination -->
                <div class="pagination" id="pagination" style="display: none;">
                    <button class="page-btn" id="prevPage" onclick="changePage(currentPage - 1)">
                        <i class="bi-chevron-left"></i> Prev
                    </button>
                    <span class="page-info" id="pageInfo">Page 1 of 1</span>
                    <button class="page-btn" id="nextPage" onclick="changePage(currentPage + 1)">
                        Next <i class="bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal" id="detailModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Detail Transaksi</h3>
                <button class="modal-close" onclick="hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Detail content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="hideDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="statusModalTitle">Update Status Transaksi</h3>
                <button class="modal-close" onclick="hideStatusModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="statusTransaksiId">
                <div class="form-group">
                    <label class="form-label required">Status</label>
                    <select class="form-control" id="statusSelect">
                        <option value="VERIFIED">VERIFIED</option>
                        <option value="REJECTED">REJECTED</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" id="statusCatatan" rows="3" placeholder="Masukkan catatan jika diperlukan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="hideStatusModal()">Batal</button>
                <button class="btn btn-success" onclick="updateStatus()">Update Status</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let currentFilters = {
            search: '',
            start_date: '',
            end_date: '',
            status: 'ALL',
            metode_bayar: 'ALL'
        };

        // Helper functions
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function formatDateLocal(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatDateFull(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Toast notification
        function showToast(type, title, message) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const icons = {
                success: '<i class="bi-check-circle"></i>',
                error: '<i class="bi-x-circle"></i>',
                warning: '<i class="bi-exclamation-triangle"></i>',
                info: '<i class="bi-info-circle"></i>'
            };

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.id = toastId;
            toast.innerHTML = `
                <div class="toast-header">
                    <div class="toast-icon">
                        ${icons[type] || icons.info}
                    </div>
                    <div class="toast-title">${title}</div>
                    <button type="button" class="toast-close" onclick="removeToast('${toastId}')">
                        <i class="bi-x"></i>
                    </button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;

            toastContainer.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toastId);
            }, 5000);
        }

        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Main functions
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupEventListeners();
            setDefaultDates();
        });

        function setupEventListeners() {
            // Search on enter
            document.getElementById('filterSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });

            // Date change events
            ['filterStartDate', 'filterEndDate', 'filterStatus', 'filterMetodeBayar'].forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    applyFilters();
                });
            });
        }

        function setDefaultDates() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            document.getElementById('filterStartDate').value = formatDate(firstDay);
            document.getElementById('filterEndDate').value = formatDate(today);

            currentFilters.start_date = formatDate(firstDay);
            currentFilters.end_date = formatDate(today);
        }

        function applyFilters() {
            currentFilters = {
                search: document.getElementById('filterSearch').value,
                start_date: document.getElementById('filterStartDate').value,
                end_date: document.getElementById('filterEndDate').value,
                status: document.getElementById('filterStatus').value,
                metode_bayar: document.getElementById('filterMetodeBayar').value
            };

            currentPage = 1;
            loadData();
        }

        function resetFilters() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterStatus').value = 'ALL';
            document.getElementById('filterMetodeBayar').value = 'ALL';
            setDefaultDates();
            applyFilters();
            showToast('info', 'Reset Filter', 'Filter telah direset ke pengaturan default');
        }

        async function loadData() {
            showLoading();

            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    ...currentFilters
                });

                const response = await fetch(`{{ route('admin.kelompok.riwayat-transaksi.api.index') }}?${params}`);
                const data = await response.json();

                if (data.success) {
                    renderTable(data.data);
                    updateSummary(data.summary);
                    updatePagination(data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showToast('error', 'Error', 'Gagal memuat data transaksi: ' + error.message);
            }
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const pagination = document.getElementById('pagination');
            const summaryCards = document.getElementById('summaryCards');

            if (data.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                loadingState.style.display = 'none';
                pagination.style.display = 'none';
                summaryCards.style.display = 'none';
                return;
            }

            emptyState.style.display = 'none';
            loadingState.style.display = 'none';
            pagination.style.display = 'flex';
            summaryCards.style.display = 'grid';

            let tableRows = '';
            data.forEach((item, index) => {
                const rowNumber = index + 1 + ((currentPage - 1) * 10);
                const dataJson = item.data_json_parsed || {};

                // Status badge
                let statusBadge;
                switch (item.status) {
                    case 'VERIFIED':
                        statusBadge =
                            '<span class="badge badge-success"><i class="bi-check-circle"></i> VERIFIED</span>';
                        break;
                    case 'PENDING':
                        statusBadge = '<span class="badge badge-warning"><i class="bi-clock"></i> PENDING</span>';
                        break;
                    case 'REJECTED':
                        statusBadge =
                            '<span class="badge badge-danger"><i class="bi-x-circle"></i> REJECTED</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge badge-info">' + item.status + '</span>';
                }

                // Action buttons
                let actionButtons = '<div class="action-buttons">';
                actionButtons += '<button class="btn btn-view btn-sm" onclick="showDetail(\'' + item.transaksi_id +
                    '\')" title="Detail">';
                actionButtons += '<i class="bi-eye"></i>';
                actionButtons += '</button>';

                if (item.status === 'PENDING') {
                    actionButtons += '<button class="btn btn-verify btn-sm" onclick="showStatusModal(\'' + item
                        .transaksi_id + '\', \'VERIFIED\')" title="Verifikasi">';
                    actionButtons += '<i class="bi-check"></i>';
                    actionButtons += '</button>';
                    actionButtons += '<button class="btn btn-reject btn-sm" onclick="showStatusModal(\'' + item
                        .transaksi_id + '\', \'REJECTED\')" title="Tolak">';
                    actionButtons += '<i class="bi-x"></i>';
                    actionButtons += '</button>';
                }

                actionButtons += '</div>';

                tableRows += `
                    <tr>
                        <td>${rowNumber}</td>
                        <td>
                            <code>${escapeHtml(item.kode_transaksi)}</code><br>
                            <small class="text-muted">${escapeHtml(item.transaksi_id)}</small>
                        </td>
                        <td>${formatDateLocal(item.tgl_transaksi)}</td>
                        <td>
                            <strong>${escapeHtml(item.nama_jamaah)}</strong><br>
                            <small class="text-muted">${escapeHtml(item.nik || 'No NIK')}</small>
                        </td>
                        <td>${escapeHtml(dataJson.nama_kontribusi || item.kategori_id)}</td>
                        <td>
                            <strong class="text-success">Rp ${formatNumber(item.jumlah)}</strong>
                        </td>
                        <td>
                            <span class="badge badge-primary">${escapeHtml(item.metode_bayar)}</span>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
            });

            tableBody.innerHTML = tableRows;
        }

        function updateSummary(summary) {
            document.getElementById('totalTransaksi').textContent = summary.total_transaksi;
            document.getElementById('totalPendapatan').textContent = 'Rp ' + formatNumber(summary.total_pendapatan);
            document.getElementById('totalVerified').textContent = 'Rp ' + formatNumber(summary.total_verified);
            document.getElementById('totalPending').textContent = 'Rp ' + formatNumber(summary.total_pending);
        }

        function updatePagination(data) {
            currentPage = data.current_page;
            totalPages = data.last_page;

            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadData();
            }
        }

        async function showDetail(transaksiId) {
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.riwayat-transaksi.api.show', '') }}/${transaksiId}`);
                const data = await response.json();

                if (data.success) {
                    renderDetail(data.data);
                    document.getElementById('detailModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                showToast('error', 'Error', 'Gagal memuat detail transaksi');
            }
        }

        function renderDetail(item) {
            const dataJson = item.data_json_parsed || {};
            const subKontribusi = dataJson.sub_kontribusi || [];

            let detailHtml = `
            <div class="detail-section">
                <div class="detail-title">
                    <i class="bi-info-circle"></i> Informasi Transaksi
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Kode Transaksi</div>
                        <div class="detail-value"><code>${escapeHtml(item.kode_transaksi)}</code></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">ID Transaksi</div>
                        <div class="detail-value">${escapeHtml(item.transaksi_id)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Transaksi</div>
                        <div class="detail-value">${formatDateFull(item.tgl_transaksi)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Waktu Input</div>
                        <div class="detail-value">${formatDateTime(item.created_at)}</div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-title">
                    <i class="bi-person"></i> Data Jamaah
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Nama Jamaah</div>
                        <div class="detail-value">${escapeHtml(item.nama_jamaah)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">NIK</div>
                        <div class="detail-value">${escapeHtml(item.nik || '-')}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Telepon</div>
                        <div class="detail-value">${escapeHtml(item.telepon || '-')}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Alamat</div>
                        <div class="detail-value">${escapeHtml(item.alamat || '-')}</div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-title">
                    <i class="bi-cash-coin"></i> Data Pembayaran
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">${escapeHtml(dataJson.nama_kontribusi || item.kategori_id)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Kode Kategori</div>
                        <div class="detail-value">${escapeHtml(dataJson.kode_kontribusi || '-')}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Jumlah</div>
                        <div class="detail-value"><strong class="text-success">Rp ${formatNumber(item.jumlah)}</strong></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Metode Bayar</div>
                        <div class="detail-value">${escapeHtml(item.metode_bayar)}</div>
                    </div>
                </div>
            </div>
        `;

            // Sub Kontribusi Detail
            if (subKontribusi.length > 0) {
                let subKontribusiRows = '';
                let totalCalculated = 0;

                subKontribusi.forEach((sub) => {
                    let subTotal = 0;
                    if (sub.jenis === 'percentage') {
                        subTotal = (parseFloat(sub.value) * parseFloat(sub.input_value)) / 100;
                    } else {
                        subTotal = parseFloat(sub.input_value);
                    }
                    totalCalculated += subTotal;

                    const jenisClass = sub.jenis === 'percentage' ? 'badge-info' : 'badge-success';
                    const jenisLabel = sub.jenis === 'percentage' ? 'Persentase' : 'Nominal';
                    const valueDisplay = sub.jenis === 'percentage' ? sub.value + '%' : 'Rp ' + formatNumber(sub
                        .value);
                    const inputDisplay = sub.jenis === 'percentage' ? sub.input_value + '%' : 'Rp ' + formatNumber(
                        sub.input_value);

                    subKontribusiRows += `
                        <tr>
                            <td>${escapeHtml(sub.nama_kontribusi)}</td>
                            <td>
                                <span class="badge ${jenisClass}">${jenisLabel}</span>
                            </td>
                            <td>${valueDisplay}</td>
                            <td>${inputDisplay}</td>
                            <td class="text-right"><strong>Rp ${formatNumber(subTotal.toFixed(2))}</strong></td>
                        </tr>
                    `;
                });

                detailHtml += `
                    <div class="detail-section">
                        <div class="detail-title">
                            <i class="bi-list-check"></i> Rincian Sub Kontribusi
                        </div>
                        <div class="sub-kontribusi-detail">
                            <table class="sub-kontribusi-table">
                                <thead>
                                    <tr>
                                        <th>Nama Kontribusi</th>
                                        <th>Jenis</th>
                                        <th>Value</th>
                                        <th>Input</th>
                                        <th class="text-right">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${subKontribusiRows}
                                    <tr style="background: #f8f9fa;">
                                        <td colspan="4" class="text-right"><strong>GRAND TOTAL</strong></td>
                                        <td class="text-right"><strong class="text-success">Rp ${formatNumber(totalCalculated.toFixed(2))}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Status & Info
            let statusBadge;
            switch (item.status) {
                case 'VERIFIED':
                    statusBadge = '<span class="badge badge-success"><i class="bi-check-circle"></i> VERIFIED</span>';
                    break;
                case 'PENDING':
                    statusBadge = '<span class="badge badge-warning"><i class="bi-clock"></i> PENDING</span>';
                    break;
                case 'REJECTED':
                    statusBadge = '<span class="badge badge-danger"><i class="bi-x-circle"></i> REJECTED</span>';
                    break;
                default:
                    statusBadge = '<span class="badge badge-info">' + item.status + '</span>';
            }

            detailHtml += `
            <div class="detail-section">
                <div class="detail-title">
                    <i class="bi-clipboard-check"></i> Status & Informasi
                </div>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">${statusBadge}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Dibuat Oleh</div>
                        <div class="detail-value">${escapeHtml(item.created_by_name || '-')}</div>
                    </div>
            `;

            if (item.verified_by_name) {
                detailHtml += `
                    <div class="detail-item">
                        <div class="detail-label">Diverifikasi Oleh</div>
                        <div class="detail-value">${escapeHtml(item.verified_by_name)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Waktu Verifikasi</div>
                        <div class="detail-value">${formatDateTime(item.verified_at)}</div>
                    </div>
                `;
            }

            detailHtml += `
                </div>
            </div>
        `;

            // Keterangan
            if (item.keterangan) {
                detailHtml += `
                <div class="detail-section">
                    <div class="detail-title">
                        <i class="bi-chat-left-text"></i> Keterangan
                    </div>
                    <div class="detail-grid">
                        <div class="detail-label">Catatan Transaksi</div>
                        <div class="detail-value">${escapeHtml(item.keterangan)}</div>
                    </div>
                </div>
            `;
            }

            // Bukti Bayar
            if (item.bukti_bayar) {
                detailHtml += `
                <div class="detail-section">
                    <div class="detail-title">
                        <i class="bi-image"></i> Bukti Pembayaran
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">File Bukti</div>
                        <div class="detail-value">
                            <img src="/storage/${escapeHtml(item.bukti_bayar)}" 
                                 alt="Bukti Bayar" 
                                 style="max-width: 100%; max-height: 300px; border-radius: 4px; border: 1px solid #ddd;">
                        </div>
                    </div>
                </div>
            `;
            }

            document.getElementById('detailModalBody').innerHTML = detailHtml;
        }

        function hideDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function showStatusModal(transaksiId, status) {
            document.getElementById('statusTransaksiId').value = transaksiId;
            document.getElementById('statusSelect').value = status;
            document.getElementById('statusModalTitle').textContent = status === 'VERIFIED' ? 'Verifikasi Transaksi' :
                'Tolak Transaksi';
            document.getElementById('statusCatatan').value = '';
            document.getElementById('statusModal').classList.add('show');
        }

        function hideStatusModal() {
            document.getElementById('statusModal').classList.remove('show');
        }

        async function updateStatus() {
            const transaksiId = document.getElementById('statusTransaksiId').value;
            const status = document.getElementById('statusSelect').value;
            const catatan = document.getElementById('statusCatatan').value;

            if (!transaksiId) {
                showToast('error', 'Error', 'ID Transaksi tidak valid');
                return;
            }

            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.riwayat-transaksi.api.update-status', '') }}/${transaksiId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status,
                            catatan: catatan
                        })
                    });

                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Berhasil', 'Status transaksi berhasil diupdate');
                    hideStatusModal();
                    loadData();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error updating status:', error);
                showToast('error', 'Error', 'Gagal mengupdate status transaksi: ' + error.message);
            }
        }

        function exportData() {
            const params = new URLSearchParams(currentFilters);
            window.open(`{{ route('admin.kelompok.riwayat-transaksi.export') }}?${params}`, '_blank');
            showToast('info', 'Export', 'Data sedang diexport ke format CSV');
        }

        function printData() {
            const params = new URLSearchParams(currentFilters);
            window.open(`{{ route('admin.kelompok.riwayat-transaksi.print') }}?${params}`, '_blank');
            showToast('info', 'Cetak', 'Membuka halaman cetak');
        }

        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
            document.getElementById('summaryCards').style.display = 'none';
            document.getElementById('tableBody').innerHTML = '';
        }

        // Close modals when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) hideDetailModal();
        });

        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) hideStatusModal();
        });

        // Add keyboard shortcut for escape to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDetailModal();
                hideStatusModal();
            }
        });
    </script>
@endpush
