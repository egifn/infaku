@extends('layouts.app')

@section('title', 'Master Kontribusi')
@section('page-title', 'Master Kontribusi')
@section('icon-page-title', 'bi-tags')

@push('style')
    <style>
        .master-container {
            padding: 5px;
        }

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

        .table-container {
            overflow-x: auto;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            min-width: 800px;
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            white-space: nowrap;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table th {
            background: #f8f9fa;
            color: #333;
            font-size: 0.9rem;
        }

        .data-table tr {
            font-size: small;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .btn-primary {
            background: #105a44;
            color: white;
        }

        .btn-primary:hover {
            background: #0d8b66;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            color: #4d4d4d;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            background: white;
            color: #757575;
        }

        .form-text {
            font-size: 13px;
            color: #666;
            margin-top: 4px;
        }

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
            max-width: 500px;
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

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .page-info {
            font-size: 13px;
            color: #666;
        }


        .page-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .page-btn:hover:not(:disabled) {
            background: #f8f9fa;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 6px 35px 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 10px 20px;
            color: #666;
        }

        .empty-state h4,
        p {
            font-size: 13px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .code-badge {
            background: #e9ecef;
            color: #495057;
            font-family: 'Courier New', monospace;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .none {
            display: none;
        }

        @media (max-width: 768px) {
            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }
        }

        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 320px;
            z-index: 1060;
            max-width: 400px;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            border-left: 4px solid #17a2b8;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            min-width: 300px;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.hide {
            transform: translateX(100%);
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .toast.success {
            border-left: 4px solid #28a745;
        }

        .toast.success .toast-icon {
            background: #d4edda;
            color: #155724;
        }

        .toast.error {
            border-left: 4px solid #dc3545;
        }

        .toast.error .toast-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .toast.warning {
            border-left: 4px solid #ffc107;
        }

        .toast.warning .toast-icon {
            background: #fff3cd;
            color: #856404;
        }

        .toast.info {
            border-left: 4px solid #17a2b8;
        }

        .toast.info .toast-icon {
            background: #d1ecf1;
            color: #0c5460;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .toast-message {
            font-size: 0.85rem;
            color: #666;
        }

        .toast-close {
            background: none;
            border: none;
            color: #999;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            margin-left: 12px;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #666;
        }
    </style>
@endpush

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <div style="display: flex;gap: 10px;justify-content: space-between;align-items: center;">
                    <h3 class="card-title">Master Kategori Kontribusi</h3>
                    <button class="btn btn-primary" onclick="showCreateModal()">
                        <i class="bi-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-controls">
                    <div>
                        <select id="perPageSelect" class="form-select"
                            style="width:auto;display:inline-block; padding:5px 0px; font-size:13px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input"
                            placeholder="Cari kode, nama kategori...">
                        <i class="bi-search search-icon"></i>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th hidden>Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th hidden>Kelompok</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-tags" style="font-size: 3rem;"></i>
                    <h4>Belum ada data master kontribusi</h4>
                    {{-- <p>Mulai dengan menambahkan kategori kontribusi pertama untuk kelompok Anda.</p> --}}
                    {{-- <button class="btn btn-primary" onclick="showCreateModal()">
                        <i class="bi-plus"></i> Tambah Kategori
                    </button> --}}
                </div>

                <div id="loadingState" class="empty-state">
                    <div style="height: 20px; width: 200px; margin: 0 auto 10px; background: #f0f0f0; border-radius: 4px;">
                    </div>
                    <div style="height: 20px; width: 150px; margin: 0 auto; background: #f0f0f0; border-radius: 4px;"></div>
                </div>

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

    <!-- Create/Edit Modal -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Kategori Kontribusi</h3>
                <button class="modal-close" onclick="hideModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="kontribusiForm">
                    <input type="hidden" id="editId">

                    <div class="form-group">
                        <label class="form-label" for="nama_kontribusi">Nama Kategori *</label>
                        <input type="text" class="form-control" id="nama_kontribusi" name="nama_kontribusi"
                            maxlength="100" required placeholder="Contoh: INFAQ, SODAQOH, ZAKAT">
                        <div class="form-text">Nama lengkap kategori kontribusi</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                            placeholder="Deskripsi atau penjelasan tentang kategori ini..."></textarea>
                    </div>

                    <div class="form-group" id="status">
                        <label class="form-label" for="is_aktif">Status *</label>
                        <select class="form-select" id="is_aktif" name="is_aktif" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <div class="form-text">Kategori tidak aktif tidak akan muncul dalam pilihan</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="hideModal()">Batal</button>
                <button class="btn btn-success" id="submitBtn" onclick="submitForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Kategori Kontribusi</h3>
                <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori <strong id="deleteItemName"></strong>?</p>
                <p class="form-text">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="hideDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="confirmDelete()">Hapus</button>
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
        let searchQuery = '';
        let deleteId = null;
        let perPage = 10;

        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupEventListeners();
            document.getElementById('perPageSelect').addEventListener('change', function() {
                perPage = parseInt(this.value);
                currentPage = 1;
                loadData();
            });
        });

        function setupEventListeners() {
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value;
                    currentPage = 1;
                    loadData();
                }, 500);
            });
        }

        async function loadData() {
            showLoading();

            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.master-kontribusi.index') }}?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(searchQuery)}`
                );
                const data = await response.json();

                if (data.success) {
                    renderTable(data.data);
                    updatePagination(data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showError('Gagal memuat data: ' + error.message);
            }
        }

        function renderTable(data) {
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

            tableBody.innerHTML = data.map((item, index) => `
            <tr>
                <td>${index + 1 + ((currentPage - 1) * perPage)}</td>
                <td hidden>
                    <span class="code-badge">${item.kode_kontribusi}</span>
                </td>
                <td>
                    ${item.nama_kontribusi}
                </td>
                <td hidden>
                    <span class="badge badge-info">${item.nama_kelompok || 'KELOMPOK'}</span>
                </td>
                <td>
                    ${item.is_aktif ? 
                        '<span class="badge badge-success">Aktif</span>' : 
                        '<span class="badge badge-danger">Tidak Aktif</span>'
                    }
                </td>
                <td>${item.keterangan || '-'}</td>
                <td>
                    <button class="btn btn-edit btn-sm" onclick="editItem('${item.master_kontribusi_id}')" title="Edit">
                        <i class="bi-pencil"></i>
                    </button>
                    <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.master_kontribusi_id}', '${item.nama_kontribusi}')" title="Hapus">
                        <i class="bi-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
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

        function showCreateModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Kategori Kontribusi';
            document.getElementById('editId').value = '';
            document.getElementById('kontribusiForm').reset();
            document.getElementById('status').className = "form-group none";

            document.getElementById('formModal').classList.add('show');
        }

        async function editItem(id) {
            try {
                const response = await fetch(`{{ route('admin.kelompok.api.master-kontribusi.show', '') }}/${id}`);
                const data = await response.json();

                if (data.success) {
                    const item = data.data;
                    document.getElementById('modalTitle').textContent = 'Edit Kategori Kontribusi';
                    document.getElementById('editId').value = item.master_kontribusi_id;

                    // Load dapuan options first
                    await loadDapuanOptions();

                    // Fill form fields
                    document.getElementById('nama_kontribusi').value = item.nama_kontribusi;
                    document.getElementById('keterangan').value = item.keterangan || '';
                    document.getElementById('status').className = "form-group";
                    document.getElementById('is_aktif').value = item.is_aktif ? '1' : '0';

                    document.getElementById('formModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading item:', error);
                showError('Gagal memuat data: ' + error.message);
            }
        }

        function hideModal() {
            document.getElementById('formModal').classList.remove('show');
        }

        async function submitForm() {
            const formData = new FormData(document.getElementById('kontribusiForm'));
            const id = document.getElementById('editId').value;

            const data = {
                nama_kontribusi: formData.get('nama_kontribusi'),
                keterangan: formData.get('keterangan'),
                is_aktif: formData.get('is_aktif') === '1'
            };

            try {
                const url = id ? `{{ route('admin.kelompok.api.master-kontribusi.update', '') }}/${id}` :
                    '{{ route('admin.kelompok.api.master-kontribusi.store') }}';
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    hideModal();
                    loadData();
                    showSuccess(id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showError('Gagal menyimpan data: ' + error.message);
            }
        }

        function showDeleteModal(id, name) {
            deleteId = id;
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        async function confirmDelete() {
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.master-kontribusi.destroy', '') }}/${deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                const result = await response.json();

                if (result.success) {
                    hideDeleteModal();
                    loadData();
                    showSuccess('Data berhasil dihapus');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                showError('Gagal menghapus data: ' + error.message);
            }
        }

        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
        }

        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const icons = {
                success: 'bi-check-circle',
                error: 'bi-exclamation-triangle',
                warning: 'bi-exclamation-circle',
                info: 'bi-info-circle'
            };

            toast.className = `toast ${type}`;

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[type] || 'bi-info-circle'}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="bi-x"></i>
                </button>
            `;

            container.appendChild(toast);

            const existingToasts = container.children.length - 1;
            const delay = existingToasts * 300; // 300ms delay between each toast

            setTimeout(() => {
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 250);
                }, 3000); // Auto hide after 3 seconds
            }, delay);
        }

        function showSuccess(message) {
            showToast('success', 'Berhasil', message);
        }

        function showError(message) {
            showToast('error', 'Error', message);
        }

        document.getElementById('formModal').addEventListener('click', function(e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) hideDeleteModal();
        });
    </script>
@endpush
