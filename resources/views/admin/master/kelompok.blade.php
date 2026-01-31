<!-- resources/views/admin/master/wilayah-kelompok.blade.php -->
@extends('layouts.app')

@section('title', 'Master Wilayah - Kelompok')
@section('page-title', 'Master Wilayah Kelompok')
@section('icon-page-title', 'eg-home')

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

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* smooth scrolling on mobile */
        }

        /* wrapper that keeps the table horizontally scrollable on small screens */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        /* ensure table can expand beyond container so scrolling is possible */
        .data-table {
            min-width: 980px;
            /* adjust if needed */
            width: 100%;
            border-collapse: collapse;
        }

        /* prevent text from wrapping inside table cells to keep columns consistent */
        .data-table th,
        .data-table td {
            white-space: nowrap;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .data-table tr {
            font-size: small;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Action Buttons */
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

        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .form-text {
            font-size: 0.8rem;
            color: #666;
            margin-top: 4px;
        }

        /* Modal Styles */
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
            justify-content: between;
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

        .modal-close:hover {
            color: #333;
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

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding: 15px 0;
        }

        .page-info {
            font-size: 0.9rem;
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

        .page-btn.active {
            background: #105a44;
            color: white;
            border-color: #105a44;
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        <blade keyframes|%20loading%20%7B%0D>0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
        }

        /* Search and Filter */
        .table-controls {
            display: flex;
            justify-content: between;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Badges */
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

    </style>
@endpush

@section('content')
<div class="master-container">
    <!-- Header Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Kelompok</h3>
        </div>
        <div class="card-body">
            {{-- <p>Kelola data kelompok di wilayah Anda. Tambah, edit, atau hapus data kelompok sesuai kebutuhan.</p> --}}

            <div class="table-controls">
                <div class="search-box">
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari nama kelompok...">
                    <i class="eg-search search-icon"></i>
                </div>
                <button class="btn btn-primary" onclick="showCreateModal()" style="padding: 9px 12px;">
                    <i class="eg-plus"></i> Tambah Kelompok
                </button>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th hidden>Kode Kelompok</th>
                                <th>Nama Kelompok</th>
                                <th>Nama Masjid</th>
                                <th>Alamat Masjid</th>
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

            <!-- Empty State -->
            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="eg-info"></i>
                <h4>Belum ada data kelompok</h4>
                <p>Mulai dengan menambahkan data kelompok pertama Anda.</p>
                <button class="btn btn-primary" onclick="showCreateModal()">
                    <i class="eg-plus"></i> Tambah Kelompok
                </button>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="empty-state">
                <div class="skeleton" style="height: 20px; width: 200px; margin: 0 auto 10px;"></div>
                <div class="skeleton" style="height: 20px; width: 150px; margin: 0 auto;"></div>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination" style="display: none;">
                <button class="page-btn" id="prevPage" onclick="changePage(currentPage - 1)">
                    <i class="eg-arrow-left"></i> Prev
                </button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="page-btn" id="nextPage" onclick="changePage(currentPage + 1)">
                    Next <i class="eg-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal" id="formModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Tambah Kelompok</h3>
            <button class="modal-close" onclick="hideModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="wilayahForm">
                <input type="hidden" id="editId">

                <div class="form-group">
                    <label class="form-label" for="nama_kelompok">Nama Kelompok *</label>
                    <input type="text" class="form-control" id="nama_kelompok" name="nama_kelompok" required>
                    <div class="form-text">Nama kelompok harus unik</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_masjid">Nama Masjid</label>
                    <input type="text" class="form-control" id="nama_masjid" name="nama_masjid">
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat_masjid">Alamat Masjid</label>
                    <textarea class="form-control" id="alamat_masjid" name="alamat_masjid" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
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
            <h3 class="modal-title">Hapus Kelompok</h3>
            <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus kelompok <strong id="deleteItemName"></strong>?</p>
            <p class="form-text">Data yang dihapus tidak dapat dikembalikan.</p>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="hideDeleteModal()">Batal</button>
            <button class="btn btn-delete" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        let deleteId = null;

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function () {
            loadData();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search input with debounce
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value;
                    currentPage = 1;
                    loadData();
                }, 500);
            });
        }

        // Load data from API
        async function loadData() {
            showLoading();

            try {
                const response = await fetch(
                    `{{ route('api.wilayah-kelompok.index') }}?page=${currentPage}&search=${encodeURIComponent(searchQuery)}`
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

        // Render table data
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
                <td>${index + 1 + ((currentPage - 1) * 10)}</td>
                <td hidden><code>${item.kelompok_id}</code></td>
                <td>${item.nama_kelompok}</td>
                <td>${item.nama_masjid || '-'}</td>
                <td>${item.alamat_masjid ? item.alamat_masjid.substring(0, 50) + (item.alamat_masjid.length > 50 ? '...' : '') : '-'}</td>
                <td>${item.keterangan || '-'}</td>
                <td>
                    <button class="btn btn-edit btn-sm" onclick="editItem('${item.kelompok_id}')" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.kelompok_id}', '${item.nama_kelompok}')" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>

            </tr>
        `).join('');
        }

        // Update pagination
        function updatePagination(data) {
            currentPage = data.current_page;
            totalPages = data.last_page;

            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        // Change page
        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadData();
            }
        }

        // Show create modal
        function showCreateModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Kelompok';
            document.getElementById('editId').value = '';
            document.getElementById('wilayahForm').reset();
            document.getElementById('formModal').classList.add('show');
        }

        // Show edit modal
        async function editItem(id) {
            try {
                const response = await fetch(
                    `{{ route('api.wilayah-kelompok.show', '') }}/${id}`
                    );
                const data = await response.json();

                if (data.success) {
                    const item = data.data;
                    document.getElementById('modalTitle').textContent = 'Edit Kelompok';
                    document.getElementById('editId').value = item.kelompok_id;
                    document.getElementById('nama_kelompok').value = item.nama_kelompok;
                    document.getElementById('nama_masjid').value = item.nama_masjid || '';
                    document.getElementById('alamat_masjid').value = item.alamat_masjid || '';
                    document.getElementById('keterangan').value = item.keterangan || '';
                    document.getElementById('formModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading item:', error);
                showError('Gagal memuat data: ' + error.message);
            }
        }

        // Hide modal
        function hideModal() {
            document.getElementById('formModal').classList.remove('show');
        }

        // Submit form
        async function submitForm() {
            const formData = new FormData(document.getElementById('wilayahForm'));
            const id = document.getElementById('editId').value;

            const data = {
                nama_kelompok: formData.get('nama_kelompok'),
                nama_masjid: formData.get('nama_masjid'),
                alamat_masjid: formData.get('alamat_masjid'),
                keterangan: formData.get('keterangan')
            };

            try {
                const url = id ?
                    `{{ route('api.wilayah-kelompok.update', '') }}/${id}` :
                    '{{ route('api.wilayah-kelompok.store') }}';
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

        // Show delete modal
        function showDeleteModal(id, name) {
            deleteId = id;
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        // Hide delete modal
        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        // Confirm delete
        async function confirmDelete() {
            try {
                const response = await fetch(
                    `{{ route('api.wilayah-kelompok.destroy', '') }}/${deleteId}`, {
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

        // Utility functions
        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
        }

        function showSuccess(message) {
            alert('Sukses: ' + message); // Bisa diganti dengan toast notification
        }

        function showError(message) {
            alert('Error: ' + message); // Bisa diganti dengan toast notification
        }

        // Close modal when clicking outside
        document.getElementById('formModal').addEventListener('click', function (e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) hideDeleteModal();
        });

    </script>
@endpush
