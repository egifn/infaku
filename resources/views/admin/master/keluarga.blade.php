<!-- resources/views/admin/master/wilayah-kelompok.blade.php -->
@extends('layouts.app')

@section('title', 'Master Wilayah - Kelompok')
@section('page-title', 'Master Wilayah Kelompok')
@section('icon-page-title', 'eg-home')

@section('content')
    <div class="master-container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Kelompok</h3>
            </div>
            <div class="card-body">
                <p>Kelola data kelompok di wilayah Anda. Tambah, edit, atau hapus data kelompok sesuai kebutuhan.</p>

                <div class="table-controls">
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama kelompok...">
                        <i class="eg-search search-icon"></i>
                    </div>
                    <button class="btn btn-primary" onclick="showCreateModal()">
                        <i class="eg-plus"></i> Tambah Kelompok
                    </button>
                </div>

                <!-- Table Container -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Kode Kelompok</th>
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
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search input with debounce
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
                <td><code>${item.kelompok_id}</code></td>
                <td><strong>${item.nama_kelompok}</strong></td>
                <td>${item.nama_masjid || '-'}</td>
                <td>${item.alamat_masjid ? item.alamat_masjid.substring(0, 50) + (item.alamat_masjid.length > 50 ? '...' : '') : '-'}</td>
                <td>${item.keterangan || '-'}</td>
                <td>
                    <button class="btn btn-edit btn-sm" onclick="editItem('${item.kelompok_id}')" title="Edit">
                        <i class="eg-edit"></i>
                    </button>
                    <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.kelompok_id}', '${item.nama_kelompok}')" title="Hapus">
                        <i class="eg-trash"></i>
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
                const response = await fetch(`{{ route('api.wilayah-kelompok.show', '') }}/${id}`);
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
                const url = id ? `{{ route('api.wilayah-kelompok.update', '') }}/${id}` :
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
                const response = await fetch(`{{ route('api.wilayah-kelompok.destroy', '') }}/${deleteId}`, {
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
        document.getElementById('formModal').addEventListener('click', function(e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) hideDeleteModal();
        });
    </script>
@endpush
