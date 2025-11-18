<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'Master Wilayah')
@section('page-title', 'Master Wilayah')
@section('icon-page-title', 'eg-document')

@push('style')
    <style>
        .dashboard-container {
            background: #ffffff;
            border: 1px solid #dcdcdc;
            overflow: hidden;
            margin-bottom: 20px;
        }

        /* ---------- HEADER ---------- */
        .tab-container {
            background: #ffffff;
            border: 1px solid #dcdcdc;
            margin-bottom: 20px;
        }

        .tab-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dcdcdc;
            padding: 0;
        }

        .tab-nav {
            display: flex;
            flex-wrap: wrap;
        }

        .tab-button {
            padding: 12px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s;
        }

        .tab-button.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
            background: #ffffff;
        }

        .tab-content {
            display: none;
            padding: 20px;
        }

        .tab-content.active {
            display: block;
        }
        
        .table-header {
            background: #dcdcdc;
            border-bottom: 1px solid #dcdcdc;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 10px;
            font-size: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
        }

        .btn:hover {
            background: #f1f5f9;
        }

        .btn-primary {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        /* ---------- FILTER ---------- */
        .filter-container {
            padding: 10px 16px;
            background: #ffffff;
            border-bottom: 1px solid #f2f2f2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .filter-group label {
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            display: block;
            margin-bottom: 4px;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }

        /* ---------- TABLE ---------- */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            font-size: 12px;
        }

        .data-table th,
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f2f2f2;
            white-space: nowrap;
        }

        .data-table th {
            background: #dcdcdc;
            color: #334155;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        .data-table tr:hover td {
            background: #f2f2f2;
        }

        /* ---------- FOOTER ---------- */
        .footer-info {
            padding: 8px 10px;
            background: #bababa;
            border-top: 1px solid #f2f2f2;
            font-size: 12px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ---------- MODAL ---------- */
        .modal-content {
            border-radius: 8px;
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px) {
            .tab-nav {
                flex-direction: column;
            }
            
            .tab-button {
                border-bottom: 1px solid #dcdcdc;
                border-left: 3px solid transparent;
            }
            
            .tab-button.active {
                border-left-color: #0d6efd;
            }

            .table-header,
            .filter-container,
            .footer-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div style="margin: 5px 0px">
        <h2>Master Wilayah</h2>
    </div>

    <div class="dashboard-container">
        <!-- Tab Navigation -->
        <div class="tab-header">
            <div class="tab-nav">
                <button class="tab-button active" data-tab="kota">Master Kota</button>
                <button class="tab-button" data-tab="daerah">Master Daerah</button>
                <button class="tab-button" data-tab="desa">Master Desa</button>
                <button class="tab-button" data-tab="kelompok">Master Kelompok</button>
            </div>
        </div>

        <!-- Kota Tab -->
        <div id="tab-kota" class="tab-content active">
            <div class="table-header">
                <h1>Data Master Kota</h1>
                <div class="table-actions">
                    <button class="btn btn-primary" id="btn_show_modal_create_kota"><i class="fas fa-plus"></i> Tambah Kota</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                    <button class="btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <div class="filter-container">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="search_kota" class="filter-input" placeholder="Cari nama kota...">
                </div>
                <div class="filter-group">
                    <label>Items per page</label>
                    <select id="limit_kota" class="filter-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kota</th>
                            <th>Nama Kota</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kota_table_body"></tbody>
                </table>
            </div>

            <div class="footer-info">
                <span>Showing <span id="kota_pagination_info">0-0 of 0</span> entries</span>
                <ul class="pagination mb-0 justify-content-center" id="kota_pagination"></ul>
            </div>
        </div>

        <!-- Daerah Tab -->
        <div id="tab-daerah" class="tab-content">
            <div class="table-header">
                <h1>Data Master Daerah</h1>
                <div class="table-actions">
                    <button class="btn btn-primary" id="btn_show_modal_create_daerah"><i class="fas fa-plus"></i> Tambah Daerah</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                    <button class="btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <div class="filter-container">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="search_daerah" class="filter-input" placeholder="Cari nama daerah...">
                </div>
                <div class="filter-group">
                    <label>Kota</label>
                    <select id="filter_kota_daerah" class="filter-select">
                        <option value="">Semua Kota</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Items per page</label>
                    <select id="limit_daerah" class="filter-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Daerah</th>
                            <th>Nama Daerah</th>
                            <th>Kota</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="daerah_table_body"></tbody>
                </table>
            </div>

            <div class="footer-info">
                <span>Showing <span id="daerah_pagination_info">0-0 of 0</span> entries</span>
                <ul class="pagination mb-0 justify-content-center" id="daerah_pagination"></ul>
            </div>
        </div>

        <!-- Desa Tab -->
        <div id="tab-desa" class="tab-content">
            <div class="table-header">
                <h1>Data Master Desa</h1>
                <div class="table-actions">
                    <button class="btn btn-primary" id="btn_show_modal_create_desa"><i class="fas fa-plus"></i> Tambah Desa</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                    <button class="btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <div class="filter-container">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="search_desa" class="filter-input" placeholder="Cari nama desa...">
                </div>
                <div class="filter-group">
                    <label>Daerah</label>
                    <select id="filter_daerah_desa" class="filter-select">
                        <option value="">Semua Daerah</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Items per page</label>
                    <select id="limit_desa" class="filter-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Desa</th>
                            <th>Nama Desa</th>
                            <th>Daerah</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="desa_table_body"></tbody>
                </table>
            </div>

            <div class="footer-info">
                <span>Showing <span id="desa_pagination_info">0-0 of 0</span> entries</span>
                <ul class="pagination mb-0 justify-content-center" id="desa_pagination"></ul>
            </div>
        </div>

        <!-- Kelompok Tab -->
        <div id="tab-kelompok" class="tab-content">
            <div class="table-header">
                <h1>Data Master Kelompok</h1>
                <div class="table-actions">
                    <button class="btn btn-primary" id="btn_show_modal_create_kelompok"><i class="fas fa-plus"></i> Tambah Kelompok</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                    <button class="btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <div class="filter-container">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="search_kelompok" class="filter-input" placeholder="Cari nama kelompok...">
                </div>
                <div class="filter-group">
                    <label>Desa</label>
                    <select id="filter_desa_kelompok" class="filter-select">
                        <option value="">Semua Desa</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Items per page</label>
                    <select id="limit_kelompok" class="filter-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelompok</th>
                            <th>Nama Kelompok</th>
                            <th>Desa</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kelompok_table_body"></tbody>
                </table>
            </div>

            <div class="footer-info">
                <span>Showing <span id="kelompok_pagination_info">0-0 of 0</span> entries</span>
                <ul class="pagination mb-0 justify-content-center" id="kelompok_pagination"></ul>
            </div>
        </div>
    </div>

@push('modal')
        <!-- Modal untuk Kota -->
    <div class="modal fade" id="modal_kota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_kota_title">Tambah Kota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kota_id" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kota" maxlength="60">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_kota" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kota">Simpan</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kota_loading" style="display:none" disabled>
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Daerah -->
    <div class="modal fade" id="modal_daerah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_daerah_title">Tambah Daerah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Daerah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="daerah_id" maxlength="7">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <select class="form-control" id="kota_id_daerah">
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Daerah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_daerah" maxlength="60">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_daerah" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_daerah">Simpan</button>
                    <button type="button" class="btn btn-primary" id="btn_save_daerah_loading" style="display:none" disabled>
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Desa -->
    <div class="modal fade" id="modal_desa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_desa_title">Tambah Desa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Desa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="desa_id" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Daerah <span class="text-danger">*</span></label>
                        <select class="form-control" id="daerah_id_desa">
                            <option value="">Pilih Daerah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Desa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_desa" maxlength="60">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_desa" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_desa">Simpan</button>
                    <button type="button" class="btn btn-primary" id="btn_save_desa_loading" style="display:none" disabled>
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Kelompok -->
    <div class="modal fade" id="modal_kelompok" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_kelompok_title">Tambah Kelompok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Kelompok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kelompok_id" maxlength="11">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Desa <span class="text-danger">*</span></label>
                        <select class="form-control" id="desa_id_kelompok">
                            <option value="">Pilih Desa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kelompok" maxlength="60">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_kelompok" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kelompok">Simpan</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kelompok_loading" style="display:none" disabled>
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush
@endsection

@push('scripts')
<script>
    // Variabel global
    let currentKotaPage = 1;
    let currentDaerahPage = 1;
    let currentDesaPage = 1;
    let currentKelompokPage = 1;

    // Tab Functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(`tab-${this.dataset.tab}`).classList.add('active');
            
            loadTabData(this.dataset.tab);
        });
    });

    function loadTabData(tabType) {
        switch(tabType) {
            case 'kota':
                fetchKotaData();
                break;
            case 'daerah':
                fetchKotaOptions();
                fetchDaerahData();
                break;
            case 'desa':
                fetchDaerahOptions();
                fetchDesaData();
                break;
            case 'kelompok':
                fetchDesaOptions();
                fetchKelompokData();
                break;
        }
    }

    // ==================== KOTA CRUD ====================
    async function fetchKotaData(search = '', page = 1) {
        try {
            const response = await axios.get(`{{ route('wilayah.kota.data') }}`, {
                params: { search, page, limit: document.getElementById('limit_kota').value }
            });
            
            const data = response.data.data;
            const pagination = response.data.pagination;
            const tableBody = document.getElementById('kota_table_body');
            
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
            } else {
                data.forEach((kota, index) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${kota.kota_id}</td>
                            <td>${kota.nama_kota}</td>
                            <td>${kota.keterangan || '-'}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-kota" data-id="${kota.kota_id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-kota" data-id="${kota.kota_id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            
            updatePagination('kota', pagination);
        } catch (error) {
            console.error('Error fetching kota data:', error);
        }
    }

    // Event Listeners untuk Kota
    document.getElementById('btn_show_modal_create_kota').addEventListener('click', function() {
        document.getElementById('modal_kota_title').textContent = 'Tambah Kota';
        document.getElementById('kota_id').value = '';
        document.getElementById('nama_kota').value = '';
        document.getElementById('keterangan_kota').value = '';
        new bootstrap.Modal(document.getElementById('modal_kota')).show();
    });

    document.getElementById('btn_save_kota').addEventListener('click', async function() {
        const btnSave = document.getElementById('btn_save_kota');
        const btnLoading = document.getElementById('btn_save_kota_loading');
        
        btnSave.style.display = 'none';
        btnLoading.style.display = 'inline-block';
        
        try {
            const response = await axios.post(`{{ route('wilayah.kota.insert') }}`, {
                kota_id: document.getElementById('kota_id').value,
                nama_kota: document.getElementById('nama_kota').value,
                keterangan: document.getElementById('keterangan_kota').value
            });
            
            if (response.data.status) {
                alertResponseSuccess(response.data.message);
                fetchKotaData();
                bootstrap.Modal.getInstance(document.getElementById('modal_kota')).hide();
            } else {
                alertResponseError(response.data);
            }
        } catch (error) {
            console.error('Error saving kota:', error);
        } finally {
            btnSave.style.display = 'inline-block';
            btnLoading.style.display = 'none';
        }
    });

    // ==================== DAERAH CRUD ====================
    async function fetchDaerahData(search = '', kotaId = '', page = 1) {
        try {
            const response = await axios.get(`{{ route('wilayah.daerah.data') }}`, {
                params: { search, kota_id: kotaId, page, limit: document.getElementById('limit_daerah').value }
            });
            
            const data = response.data.data;
            const pagination = response.data.pagination;
            const tableBody = document.getElementById('daerah_table_body');
            
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
            } else {
                data.forEach((daerah, index) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${daerah.daerah_id}</td>
                            <td>${daerah.nama_daerah}</td>
                            <td>${daerah.nama_kota}</td>
                            <td>${daerah.keterangan || '-'}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-daerah" data-id="${daerah.daerah_id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-daerah" data-id="${daerah.daerah_id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            
            updatePagination('daerah', pagination);
        } catch (error) {
            console.error('Error fetching daerah data:', error);
        }
    }

    async function fetchKotaOptions() {
        try {
            const response = await axios.get(`{{ route('wilayah.kota.options') }}`);
            const select = document.getElementById('kota_id_daerah');
            const filterSelect = document.getElementById('filter_kota_daerah');
            
            select.innerHTML = '<option value="">Pilih Kota</option>';
            filterSelect.innerHTML = '<option value="">Semua Kota</option>';
            
            response.data.data.forEach(kota => {
                select.innerHTML += `<option value="${kota.kota_id}">${kota.nama_kota}</option>`;
                filterSelect.innerHTML += `<option value="${kota.kota_id}">${kota.nama_kota}</option>`;
            });
        } catch (error) {
            console.error('Error fetching kota options:', error);
        }
    }

    // Similar functions for Desa and Kelompok...

    // ==================== DELETE FUNCTION ====================
    document.addEventListener('click', async function(event) {
        if (event.target.classList.contains('delete-kota')) {
            event.preventDefault();
            let id = event.target.dataset.id;

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await axios.post(`{{ route('wilayah.kota.delete') }}`, { id });
                        if (response.data.status) {
                            alertResponseSuccess(response.data.message);
                            fetchKotaData();
                        } else {
                            alertResponseError(response.data);
                        }
                    } catch (error) {
                        console.error("Error deleting data:", error);
                        alertResponseError("Terjadi kesalahan saat menghapus data.");
                    }
                }
            });
        }
        
        // Similar delete handlers for daerah, desa, kelompok...
    });

    // ==================== PAGINATION ====================
    function updatePagination(type, pagination) {
        const infoElement = document.getElementById(`${type}_pagination_info`);
        const paginationElement = document.getElementById(`${type}_pagination`);
        
        if (pagination) {
            infoElement.textContent = `Showing ${pagination.from} to ${pagination.to} of ${pagination.total} entries`;
            
            let paginationHTML = '';
            
            // Previous button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                paginationHTML += `
                    <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Next button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a>
                </li>
            `;

            paginationElement.innerHTML = paginationHTML;

            // Add event listeners to pagination links
            paginationElement.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = parseInt(e.target.dataset.page);
                    if (!isNaN(page)) {
                        switch(type) {
                            case 'kota':
                                fetchKotaData(document.getElementById('search_kota').value, page);
                                break;
                            case 'daerah':
                                fetchDaerahData(
                                    document.getElementById('search_daerah').value,
                                    document.getElementById('filter_kota_daerah').value,
                                    page
                                );
                                break;
                            // Similar for desa and kelompok...
                        }
                    }
                });
            });
        }
    }

    // ==================== SEARCH AND FILTER HANDLERS ====================
    document.getElementById('search_kota').addEventListener('keyup', (e) => {
        fetchKotaData(e.target.value, 1);
    });

    document.getElementById('limit_kota').addEventListener('change', (e) => {
        fetchKotaData(document.getElementById('search_kota').value, 1);
    });

    document.getElementById('filter_kota_daerah').addEventListener('change', (e) => {
        fetchDaerahData(document.getElementById('search_daerah').value, e.target.value, 1);
    });

    // Similar handlers for other entities...

    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        fetchKotaData();
    });
</script>
@endpush