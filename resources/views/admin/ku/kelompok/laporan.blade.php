@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')
@section('icon-page-title', 'bi-file-earmark-text')

@push('styles')
    <style>
        .laporan-container {
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

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
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
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        /* Preview Section */
        .preview-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            display: none;
        }

        .preview-section.show {
            display: block;
        }

        .preview-title {
            font-size: 14px;
            font-weight: 600;
            color: #105a44;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .preview-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            border-left: 4px solid #105a44;
        }

        .preview-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .preview-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        /* Category Table */
        .category-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .category-table th {
            background: #e9ecef;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 600;
            text-align: left;
            color: #333;
        }

        .category-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #e0e0e0;
        }

        .category-table tr:hover {
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
            margin-bottom: 15px;
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
            max-width: 900px;
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

        /* Detail Section in Modal */
        .detail-section {
            margin-bottom: 25px;
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
            margin-bottom: 15px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        /* Sub Kontribusi Table */
        .sub-kontribusi-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .sub-kontribusi-table th {
            background: #e9ecef;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 600;
            text-align: left;
            color: #333;
        }

        .sub-kontribusi-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #e0e0e0;
        }

        .sub-kontribusi-table tr:hover {
            background: #f8f9fa;
        }

        /* Grid System for Forms */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -8px;
            margin-right: -8px;
        }

        .form-col {
            flex: 1 1 0;
            min-width: 220px;
            max-width: 100%;
            padding-left: 8px;
            padding-right: 8px;
            box-sizing: border-box;
        }

        .form-col-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .form-col-3 {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }

        .form-col-4 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        @media (max-width: 900px) {

            .form-col-2,
            .form-col-3,
            .form-col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
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
    <div class="laporan-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Keuangan</h3>
            </div>
            <div class="card-body">
                <!-- Button untuk buat laporan baru -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="margin: 0; font-size: 14px; color: #666;">Daftar Laporan</h4>
                    <button class="btn btn-primary" onclick="showCreateForm()">
                        <i class="bi-plus-circle"></i> Buat Laporan Baru
                    </button>
                </div>

                <!-- Form Buat Laporan Baru -->
                <div class="form-section" id="createForm">
                    <div class="form-title">
                        <i class="bi-file-earmark-plus"></i> Buat Laporan Baru
                    </div>
                    <form onsubmit="event.preventDefault(); previewLaporan();">
                        <div class="form-row">
                            <div class="form-col form-col-2">
                                <div class="form-group">
                                    <label class="form-label required">Judul Laporan</label>
                                    <input type="text" class="form-control" id="judul_laporan"
                                        placeholder="Contoh: Laporan Bulanan Januari 2024">
                                </div>
                            </div>
                            <div class="form-col form-col-2">
                                <div class="form-group">
                                    <label class="form-label required">Tipe Laporan</label>
                                    <select class="form-control" id="tipe_laporan">
                                        <option value="BULANAN">Bulanan</option>
                                        <option value="MINGGUAN">Mingguan</option>
                                        <option value="HARIAN">Harian</option>
                                        <option value="TAHUNAN">Tahunan</option>
                                        <option value="KHUSUS">Khusus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col form-col-2">
                                <div class="form-group">
                                    <label class="form-label required">Tanggal Awal</label>
                                    <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="form-col form-col-2">
                                <div class="form-group">
                                    <label class="form-label required">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-t') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col form-col-2">
                                <div class="form-group">
                                    <label class="form-label">Filter Master Kontribusi</label>
                                    <select class="form-control" id="filter_master_kontribusi">
                                        <option value="">Semua Kontribusi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="hideCreateForm()">
                                <i class="bi-x"></i> Batal
                            </button>
                            <button type="button" class="btn btn-info" onclick="previewLaporan()">
                                <i class="bi-eye"></i> Preview
                            </button>
                            <button type="button" class="btn btn-success" onclick="createLaporan()">
                                <i class="bi-check"></i> Buat Laporan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preview Section -->
                <div class="preview-section" id="previewSection">
                    <div class="form-title">
                        <i class="bi-eye"></i> Preview Laporan
                    </div>

                    <div class="preview-grid" id="previewGrid">
                        <!-- Preview data akan dimuat di sini -->
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-secondary" onclick="hidePreview()">
                            <i class="bi-x"></i> Tutup Preview
                        </button>
                        <button class="btn btn-success" onclick="createLaporan()">
                            <i class="bi-check"></i> Konfirmasi & Buat Laporan
                        </button>
                    </div>
                </div>

                <!-- Filter Existing Laporan -->
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Pencarian</label>
                            <input type="text" class="filter-control" id="filterSearch"
                                placeholder="Cari kode/judul laporan...">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-control" id="filterStatus">
                                <option value="ALL">Semua Status</option>
                                <option value="PUBLISHED">Published</option>
                                <option value="DRAFT">Draft</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Bulan</label>
                            <select class="filter-control" id="filterMonth">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Tahun</label>
                            <select class="filter-control" id="filterYear">
                                <option value="">Semua Tahun</option>
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button class="btn btn-secondary" onclick="resetLaporanFilters()">
                            <i class="bi-arrow-clockwise"></i> Reset Filter
                        </button>
                        <button class="btn btn-primary" onclick="loadLaporanData()">
                            <i class="bi-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Table Laporan -->
                <div class="table-container">
                    <table class="data-table" id="laporanTable">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Kode Laporan</th>
                                <th>Judul</th>
                                <th>Periode</th>
                                <th>Tipe</th>
                                <th>Total Pemasukan</th>
                                <th>Jml Transaksi</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading State -->
                <div class="loading-state" id="loadingState">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data laporan...</p>
                </div>

                <!-- Empty State -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <i class="bi-file-earmark-text"></i>
                    <h4>Belum ada laporan</h4>
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
                <h3 class="modal-title">Detail Laporan</h3>
                <button class="modal-close" onclick="hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Detail content akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="hideDetailModal()">Tutup</button>
                <button class="btn btn-primary" onclick="printLaporan()" id="printBtn">
                    <i class="bi-printer"></i> Cetak
                </button>
                <button class="btn btn-success" onclick="exportLaporan()" id="exportBtn">
                    <i class="bi-download"></i> Export
                </button>
                <button class="btn btn-warning" onclick="settleLaporan()" id="settleBtn">
                    <i class="bi-lock"></i> Settle
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let currentLaporanId = null;
        let currentFilters = {
            search: '',
            status: 'ALL',
            month: '',
            year: ''
        };

        document.addEventListener('DOMContentLoaded', function() {
            loadLaporanData();
            setupEventListeners();
            loadMasterKontribusiOptions();
        });

        function setupEventListeners() {
            // Search on enter
            document.getElementById('filterSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    loadLaporanData();
                }
            });

            // Filter change events
            ['filterStatus', 'filterMonth', 'filterYear'].forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    loadLaporanData();
                });
            });
        }

        function loadMasterKontribusiOptions() {
            fetch("{{ route('admin.kelompok.api.sub-kontribusi.master-options') }}")
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('filter_master_kontribusi');
                    if (!select) return;
                    if (data.success && data.data) {
                        data.data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.master_kontribusi_id;
                            opt.textContent = item.nama_kontribusi;
                            select.appendChild(opt);
                        });
                    }
                });
        }

        function showCreateForm() {
            document.getElementById('createForm').classList.add('show');
            hidePreview();
        }

        function hideCreateForm() {
            document.getElementById('createForm').classList.remove('show');
        }

        function hidePreview() {
            document.getElementById('previewSection').classList.remove('show');
        }

        async function previewLaporan() {
            const judul = document.getElementById('judul_laporan').value;
            const tipe = document.getElementById('tipe_laporan').value;
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;

            if (!judul || !tglAwal || !tglAkhir) {
                alert('Harap lengkapi semua field yang diperlukan');
                return;
            }

            try {
                const params = new URLSearchParams({
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir,
                    tipe_laporan: tipe
                });

                const response = await fetch(`{{ route('admin.kelompok.laporan.api.preview') }}?${params}`);
                const data = await response.json();

                if (data.success) {
                    renderPreview(data.data);
                    document.getElementById('previewSection').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error previewing laporan:', error);
                alert('Gagal melakukan preview: ' + error.message);
            }
        }

        function renderPreview(data) {
            const previewGrid = document.getElementById('previewGrid');

            let html = `
                <div class="preview-item">
                    <div class="preview-label">Periode</div>
                    <div class="preview-value">${formatDate(data.summary.tgl_awal)} - ${formatDate(data.summary.tgl_akhir)}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Total Pemasukan</div>
                    <div class="preview-value">Rp ${formatNumber(data.summary.total_pemasukan)}</div>
                </div>
                <div class="preview-item">
                    <div class="preview-label">Total Transaksi</div>
                    <div class="preview-value">${data.summary.total_transaksi} transaksi</div>
                </div>
            `;

            // Add per kategori preview
            if (data.per_kategori && Object.keys(data.per_kategori).length > 0) {
                html += `<div style="grid-column: 1 / -1; margin-top: 15px;">
                    <div class="preview-label">Per Kategori:</div>
                    <table class="category-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>`;

                for (const [kategori, detail] of Object.entries(data.per_kategori)) {
                    html += `
                        <tr>
                            <td>${escapeHtml(kategori)}</td>
                            <td>${detail.count} transaksi</td>
                            <td>Rp ${formatNumber(detail.total)}</td>
                        </tr>
                    `;
                }

                html += `</tbody></table></div>`;
            }

            previewGrid.innerHTML = html;
        }

        async function createLaporan() {
            const judul = document.getElementById('judul_laporan').value;
            const tipe = document.getElementById('tipe_laporan').value;
            const tglAwal = document.getElementById('tgl_awal').value;
            const tglAkhir = document.getElementById('tgl_akhir').value;

            if (!judul || !tglAwal || !tglAkhir) {
                alert('Harap lengkapi semua field yang diperlukan');
                return;
            }

            try {
                const response = await fetch('{{ route('admin.kelompok.laporan.api.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        judul_laporan: judul,
                        tgl_awal: tglAwal,
                        tgl_akhir: tglAkhir,
                        tipe_laporan: tipe
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Laporan berhasil dibuat!');
                    hideCreateForm();
                    hidePreview();
                    loadLaporanData();
                    // Show detail modal for the newly created report
                    showDetail(data.data.laporan_id);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error creating laporan:', error);
                alert('Gagal membuat laporan: ' + error.message);
            }
        }

        async function loadLaporanData() {
            showLoading();

            currentFilters = {
                search: document.getElementById('filterSearch').value,
                status: document.getElementById('filterStatus').value,
                month: document.getElementById('filterMonth').value,
                year: document.getElementById('filterYear').value
            };

            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    ...currentFilters
                });

                const response = await fetch(`{{ route('admin.kelompok.laporan.api.index') }}?${params}`);
                const data = await response.json();

                if (data.success) {
                    renderLaporanTable(data.data);
                    updatePagination(data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading laporan data:', error);
                alert('Gagal memuat data laporan');
            }
        }

        function renderLaporanTable(data) {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const pagination = document.getElementById('pagination');

            if (data.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                loadingState.style.display = 'none';
                pagination.style.display = 'none';
                return;
            }

            emptyState.style.display = 'none';
            loadingState.style.display = 'none';
            pagination.style.display = 'flex';

            tableBody.innerHTML = data.map((item, index) => {
                const rowNumber = index + 1 + ((currentPage - 1) * 10);

                // Status badge
                let statusBadge;
                switch (item.status_laporan) {
                    case 'PUBLISHED':
                        statusBadge = '<span class="badge badge-success">PUBLISHED</span>';
                        break;
                    case 'DRAFT':
                        statusBadge = '<span class="badge badge-warning">DRAFT</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge badge-info">' + item.status_laporan + '</span>';
                }

                // Action buttons
                const actionButtons = `
                    <div class="action-buttons">
                        <button class="btn btn-info btn-sm" onclick="showDetail(${item.laporan_id})" title="Detail">
                            <i class="bi-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="editLaporan(${item.laporan_id})" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        ${item.status_laporan !== 'PUBLISHED' ? `
                                                                                                                            <button class="btn btn-danger btn-sm" onclick="deleteLaporan(${item.laporan_id}, '${escapeHtml(item.judul_laporan)}')" title="Hapus">
                                                                                                                                <i class="bi-trash"></i>
                                                                                                                            </button>
                                                                                                                        ` : ''}
                    </div>
                `;

                return `
                    <tr style="white-space: nowrap;">
                        <td>${rowNumber}</td>
                        <td>
                            <code>${escapeHtml(item.kode_laporan)}</code>
                        </td>
                        <td>
                            <strong>${escapeHtml(item.judul_laporan)}</strong>
                        </td>
                        <td>
                            ${formatDate(item.tgl_awal)} s/d ${formatDate(item.tgl_akhir)}
                        </td>
                        <td>${item.tipe_laporan}</td>
                        <td>
                            <strong class="text-success">Rp ${formatNumber(item.total_pemasukan)}</strong>
                        </td>
                        <td>${item.total_transaksi}</td>
                        <td>${statusBadge}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
            }).join('');
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
                loadLaporanData();
            }
        }

        function resetLaporanFilters() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterStatus').value = 'ALL';
            document.getElementById('filterMonth').value = '';
            document.getElementById('filterYear').value = '';
            loadLaporanData();
        }

        async function showDetail(laporanId) {
            currentLaporanId = laporanId;

            try {
                const response = await fetch(`{{ route('admin.kelompok.laporan.api.detail', '') }}/${laporanId}`);
                const data = await response.json();

                if (data.success) {
                    renderDetail(data.data);
                    document.getElementById('detailModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading laporan detail:', error);
                alert('Gagal memuat detail laporan');
            }
        }

        function renderDetail(data) {
            const laporan = data.laporan;
            const perKategori = data.per_kategori;
            const perSubKontribusi = data.per_sub_kontribusi;

            let detailHtml = `
                <div class="detail-section">
                    <div class="detail-title">
                        <i class="bi-info-circle"></i> Informasi Laporan
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Kode Laporan</div>
                            <div class="detail-value"><code>${escapeHtml(laporan.kode_laporan)}</code></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Judul</div>
                            <div class="detail-value">${escapeHtml(laporan.judul_laporan)}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Periode</div>
                            <div class="detail-value">${formatDate(laporan.tgl_awal)} - ${formatDate(laporan.tgl_akhir)}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tipe Laporan</div>
                            <div class="detail-value">${laporan.tipe_laporan}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Pemasukan</div>
                            <div class="detail-value"><strong class="text-success">Rp ${formatNumber(laporan.total_pemasukan)}</strong></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Transaksi</div>
                            <div class="detail-value">${laporan.total_transaksi} transaksi</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                ${laporan.status_laporan === 'PUBLISHED' ? 
                                    '<span class="badge badge-success">PUBLISHED</span>' : 
                                    '<span class="badge badge-warning">DRAFT</span>'}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Dibuat Pada</div>
                            <div class="detail-value">${formatDateTime(laporan.created_at)}</div>
                        </div>
                    </div>
                </div>
            `;

            // Per Kategori
            if (perKategori.length > 0) {
                detailHtml += `
                    <div class="detail-section">
                        <div class="detail-title">
                            <i class="bi-list-check"></i> Per Kategori Kontribusi
                        </div>
                        <table class="sub-kontribusi-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jml Transaksi</th>
                                    <th>Total</th>
                                    <th>Sub Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                perKategori.forEach(kategori => {
                    let subItems = '';
                    if (kategori.subs && kategori.subs.length > 0) {
                        subItems = kategori.subs.map(sub => `
                            <div style="margin-bottom: 5px;">
                                <strong>${escapeHtml(sub.sub_kontribusi)}</strong><br>
                                <small>${sub.count} transaksi • Rp ${formatNumber(sub.total)}</small>
                            </div>
                        `).join('');
                    }

                    detailHtml += `
                        <tr>
                            <td><strong>${escapeHtml(kategori.kategori)}</strong></td>
                            <td>${kategori.count}</td>
                            <td><strong>Rp ${formatNumber(kategori.total)}</strong></td>
                            <td>${subItems || '-'}</td>
                        </tr>
                    `;
                });

                detailHtml += `</tbody></table></div>`;
            }

            // Per Sub Kontribusi
            if (perSubKontribusi.length > 0) {
                detailHtml += `
                    <div class="detail-section">
                        <div class="detail-title">
                            <i class="bi-diagram-3"></i> Per Sub Kontribusi
                        </div>
                        <table class="sub-kontribusi-table">
                            <thead>
                                <tr>
                                    <th>Sub Kontribusi</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Jml Transaksi</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                perSubKontribusi.forEach(sub => {
                    const jenisBadge = sub.jenis === 'percentage' ?
                        '<span class="badge badge-info">Persentase</span>' :
                        '<span class="badge badge-success">Nominal</span>';

                    detailHtml += `
                        <tr>
                            <td><strong>${escapeHtml(sub.nama)}</strong></td>
                            <td>${escapeHtml(sub.kategori)}</td>
                            <td>${jenisBadge}</td>
                            <td>${sub.count}</td>
                            <td><strong>Rp ${formatNumber(sub.total)}</strong></td>
                        </tr>
                    `;
                });

                detailHtml += `</tbody></table></div>`;
            }

            document.getElementById('detailModalBody').innerHTML = detailHtml;

            // Update button visibility based on status
            const settleBtn = document.getElementById('settleBtn');
            if (laporan.status_laporan === 'PUBLISHED') {
                settleBtn.style.display = 'none';
            } else {
                settleBtn.style.display = 'inline-flex';
            }
        }

        function hideDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function editLaporan(laporanId) {
            alert('Fitur edit laporan akan segera tersedia');
            // Implement edit functionality here
        }

        function deleteLaporan(laporanId, judul) {
            if (confirm(`Apakah Anda yakin ingin menghapus laporan "${judul}"?`)) {
                fetch(`{{ route('admin.kelompok.laporan.api.destroy', '') }}/${laporanId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Laporan berhasil dihapus');
                            loadLaporanData();
                            hideDetailModal();
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting laporan:', error);
                        alert('Gagal menghapus laporan: ' + error.message);
                    });
            }
        }

        function printLaporan() {
            if (currentLaporanId) {
                window.open(`{{ route('admin.kelompok.laporan.print', '') }}/${currentLaporanId}`, '_blank');
            }
        }

        function exportLaporan() {
            if (currentLaporanId) {
                window.open(`{{ route('admin.kelompok.laporan.api.export', '') }}/${currentLaporanId}`, '_blank');
            }
        }

        function settleLaporan() {
            if (!currentLaporanId) return;

            if (confirm(
                    'Apakah Anda yakin ingin menyelesaikan (settle) laporan ini? Data tidak dapat diubah setelah disettle.'
                )) {
                fetch(`{{ route('admin.kelompok.laporan.api.settle', '') }}/${currentLaporanId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Laporan berhasil disettle');
                            loadLaporanData();
                            hideDetailModal();
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error settling laporan:', error);
                        alert('Gagal settle laporan: ' + error.message);
                    });
            }
        }

        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
            document.getElementById('tableBody').innerHTML = '';
        }

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

        function formatDate(dateString) {
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

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) hideDetailModal();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDetailModal();
            }
        });
    </script>
@endpush
