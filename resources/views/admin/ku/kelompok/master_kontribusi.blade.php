@extends('layouts.app')

@section('title', 'Master Kontribusi')
@section('page-title', 'Master Kontribusi')
@section('icon-page-title', 'bi-tags')

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Master Kategori Kontribusi</h3>
                <div>
                    <button class="btn btn-primary" onclick="KontribusiApp.showCreateModal()">
                        <i class="bi-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="table-controls">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="perPageSelect" class="form-select" style="width:auto; padding:5px 0px;">
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

                <!-- Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th>Kelompok</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-tags"></i>
                    <h4>Tidak ada data master kontribusi</h4>
                </div>

                <div id="loadingState" class="empty-state">
                    <div style="height: 20px; width: 200px; margin: 0 auto 10px; background: #f0f0f0; border-radius: 4px;">
                    </div>
                    <div style="height: 20px; width: 150px; margin: 0 auto; background: #f0f0f0; border-radius: 4px;"></div>
                </div>

                <!-- Pagination -->
                <div class="pagination" id="pagination" style="display: none;">
                    <button class="page-btn" id="prevPage">
                        <i class="bi-chevron-left"></i> Prev
                    </button>
                    <span class="page-info" id="pageInfo">Page 1 of 1</span>
                    <button class="page-btn" id="nextPage">
                        Next <i class="bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------- MODALS ---------- -->

    <!-- Create Modal -->
    <div class="modal" id="createModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Kategori Kontribusi</h3>
                <button class="modal-close" onclick="KontribusiApp.hideCreateModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" name="nama_kontribusi" maxlength="100" required
                            placeholder="Contoh: INFAQ, SODAQOH, ZAKAT">
                        <div class="form-text">Nama lengkap kategori kontribusi</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"
                            placeholder="Deskripsi atau penjelasan tentang kategori ini..."></textarea>
                    </div>

                    <div class="form-group" id="statusCreate" style="display: none;">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="is_aktif" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <div class="form-text">Kategori tidak aktif tidak akan muncul dalam pilihan</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KontribusiApp.hideCreateModal()">Batal</button>
                <button class="btn btn-success" onclick="KontribusiApp.submitCreateForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Edit Kategori Kontribusi</h3>
                <button class="modal-close" onclick="KontribusiApp.hideEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editKontribusiId">

                    <div class="form-group">
                        <label class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" id="editNamaKontribusi" name="nama_kontribusi"
                            maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select class="form-select" id="editIsAktif" name="is_aktif" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KontribusiApp.hideEditModal()">Batal</button>
                <button class="btn btn-success" onclick="KontribusiApp.submitEditForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Kategori Kontribusi</h3>
                <button class="modal-close" onclick="KontribusiApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori <strong id="deleteItemName"></strong>?</p>
                <p class="form-text">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KontribusiApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="KontribusiApp.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ============================================================================
        // VARIABEL GLOBAL & KONFIGURASI
        // ============================================================================
        let currentPage = 1;
        let totalPages = 1;
        let totalRecords = 0;
        let searchQuery = '';
        let perPage = 10;
        let isLoading = false;
        let deleteId = null;

        const API_ROUTES = {
            data: '{{ route('admin.kelompok.api.master-kontribusi.index') }}',
            detail: '{{ route('admin.kelompok.api.master-kontribusi.show', '') }}',
            create: '{{ route('admin.kelompok.api.master-kontribusi.store') }}',
            update: '{{ route('admin.kelompok.api.master-kontribusi.update', '') }}',
            destroy: '{{ route('admin.kelompok.api.master-kontribusi.destroy', '') }}'
        };

        // ============================================================================
        // FUNGSI UTAMA - LOAD DATA & RENDER TABEL
        // ============================================================================

        // Fungsi untuk memuat data kontribusi
        async function loadKontribusiData(page = null) {
            if (isLoading) return;

            if (page !== null && page >= 1) {
                currentPage = page;
            }

            showLoadingState();
            isLoading = true;

            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage
                });

                if (searchQuery) {
                    params.append('search', searchQuery);
                }

                const url = `${API_ROUTES.data}?${params.toString()}`;

                const response = await fetch(url);
                const result = await response.json();

                if (result.success) {
                    renderTable(result.data);
                    updatePagination(result);
                } else {
                    throw new Error(result.message || 'Gagal memuat data');
                }
            } catch (error) {
                console.error('Error loading data:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                } else {
                    alert(error.message);
                }
                // Reset ke halaman 1 jika error
                currentPage = 1;
                showEmptyState();
            } finally {
                hideLoadingState();
                isLoading = false;
            }
        }

        // Fungsi untuk merender tabel
        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const pagination = document.getElementById('pagination');

            if (!data || data.length === 0) {
                showEmptyState();
                return;
            }

            // Hide loading and empty state
            if (loadingState) loadingState.style.display = 'none';
            if (emptyState) emptyState.style.display = 'none';
            if (pagination) pagination.style.display = 'flex';

            // Format data untuk tabel
            const startNumber = ((currentPage - 1) * perPage) + 1;
            const tableRows = data.map((item, index) => {
                const rowNumber = startNumber + index;
                return `
                <tr>
                    <td>${rowNumber}</td>
                    <td>
                        <span class="code-badge">${item.kode_kontribusi || '-'}</span>
                    </td>
                    <td>${escapeHtml(item.nama_kontribusi)}</td>
                    <td>
                        <span class="badge badge-info">${item.nama_kelompok || 'KELOMPOK'}</span>
                    </td>
                    <td>
                        ${item.is_aktif ? 
                            '<span class="badge badge-success">Aktif</span>' : 
                            '<span class="badge badge-danger">Tidak Aktif</span>'
                        }
                    </td>
                    <td>${item.keterangan ? escapeHtml(item.keterangan) : '-'}</td>
                    <td>
                        <button class="btn btn-edit btn-sm" onclick="showEditModal('${item.id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.id}', '${escapeHtml(item.nama_kontribusi)}')" title="Hapus">
                            <i class="bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            }).join('');

            tableBody.innerHTML = tableRows;
        }

        // Tampilkan empty state
        function showEmptyState() {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const pagination = document.getElementById('pagination');

            if (tableBody) tableBody.innerHTML = '';
            if (emptyState) emptyState.style.display = 'block';
            if (loadingState) loadingState.style.display = 'none';
            if (pagination) pagination.style.display = 'none';
        }

        // Fungsi untuk update pagination
        function updatePagination(data) {
            currentPage = parseInt(data.current_page) || 1;
            totalPages = parseInt(data.last_page) || 1;
            totalRecords = parseInt(data.total) || 0;

            const pageInfo = document.getElementById('pageInfo');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');

            // Update info
            if (pageInfo) {
                pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;
            }

            // Update prev button
            if (prevBtn) {
                const isDisabled = currentPage <= 1;
                prevBtn.disabled = isDisabled;
                prevBtn.classList.toggle('disabled', isDisabled);
            }

            // Update next button
            if (nextBtn) {
                const isDisabled = currentPage >= totalPages;
                nextBtn.disabled = isDisabled;
                nextBtn.classList.toggle('disabled', isDisabled);
            }
        }

        // Fungsi untuk ganti halaman
        function goToPage(page) {
            if (page < 1 || page > totalPages || page === currentPage) {
                return;
            }
            loadKontribusiData(page);
        }

        // ============================================================================
        // FUNGSI MODAL - CREATE, EDIT, DELETE
        // ============================================================================

        // Modal Create
        function showCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('statusCreate').style.display = 'none';
            document.getElementById('createModal').classList.add('show');

            // Focus ke input setelah modal muncul
            setTimeout(() => {
                document.querySelector('#createForm input[name="nama_kontribusi"]').focus();
            }, 300);
        }

        function hideCreateModal() {
            document.getElementById('createModal').classList.remove('show');
        }

        // Modal Edit
        async function showEditModal(kontribusiId) {
            try {
                const response = await fetch(`${API_ROUTES.detail}/${kontribusiId}`);
                const result = await response.json();

                if (result.success) {
                    const kontribusi = result.data;

                    // Isi form dengan data kontribusi
                    document.getElementById('editKontribusiId').value = kontribusi.id;
                    document.getElementById('editNamaKontribusi').value = kontribusi.nama_kontribusi;
                    document.getElementById('editKeterangan').value = kontribusi.keterangan || '';
                    document.getElementById('editIsAktif').value = kontribusi.is_aktif ? '1' : '0';

                    // Tampilkan modal
                    document.getElementById('editModal').classList.add('show');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error loading edit data:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        function hideEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // Modal Delete
        function showDeleteModal(kontribusiId, namaKontribusi) {
            deleteId = kontribusiId;
            document.getElementById('deleteItemName').textContent = namaKontribusi;
            document.getElementById('deleteModal').classList.add('show');
        }

        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        // ============================================================================
        // FUNGSI FORM - SUBMIT CREATE, EDIT, DELETE
        // ============================================================================

        // Submit Create Form
        async function submitCreateForm() {
            const form = document.getElementById('createForm');
            const formData = new FormData(form);

            // Konversi ke object
            const data = {
                nama_kontribusi: formData.get('nama_kontribusi'),
                keterangan: formData.get('keterangan'),
                is_aktif: true // Default true untuk create
            };

            // Validasi sederhana
            if (!data.nama_kontribusi) {
                if (window.showToast) {
                    window.showToast('Harap isi nama kategori', 'error');
                }
                return;
            }

            try {
                const response = await fetch(API_ROUTES.create, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    hideCreateModal();
                    currentPage = 1; // Kembali ke halaman 1
                    loadKontribusiData();
                    if (window.showToast) {
                        window.showToast('Data kontribusi berhasil ditambahkan', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal menambahkan data');
                }
            } catch (error) {
                console.error('Error creating kontribusi:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // Submit Edit Form
        async function submitEditForm() {
            const kontribusiId = document.getElementById('editKontribusiId').value;
            const form = document.getElementById('editForm');
            const formData = new FormData(form);

            // Konversi ke object
            const data = {
                nama_kontribusi: formData.get('nama_kontribusi'),
                keterangan: formData.get('keterangan'),
                is_aktif: formData.get('is_aktif') === '1'
            };

            // Validasi sederhana
            if (!data.nama_kontribusi) {
                if (window.showToast) {
                    window.showToast('Harap isi nama kategori', 'error');
                }
                return;
            }

            try {
                const response = await fetch(`${API_ROUTES.update}/${kontribusiId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    hideEditModal();
                    loadKontribusiData(); // Reload data di halaman yang sama
                    if (window.showToast) {
                        window.showToast('Data kontribusi berhasil diupdate', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal mengupdate data');
                }
            } catch (error) {
                console.error('Error updating kontribusi:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // Submit Delete
        async function confirmDelete() {
            if (!deleteId) return;

            try {
                const response = await fetch(`${API_ROUTES.destroy}/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    hideDeleteModal();
                    loadKontribusiData();
                    if (window.showToast) {
                        window.showToast('Data kontribusi berhasil dihapus', 'success');
                    }
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error deleting kontribusi:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // ============================================================================
        // FUNGSI BANTU (HELPER FUNCTIONS)
        // ============================================================================

        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Loading state
        function showLoadingState() {
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');
            if (loadingState) loadingState.style.display = 'block';
            if (emptyState) emptyState.style.display = 'none';
        }

        function hideLoadingState() {
            const loadingState = document.getElementById('loadingState');
            if (loadingState) loadingState.style.display = 'none';
        }

        // ============================================================================
        // EVENT LISTENERS & INITIALIZATION
        // ============================================================================

        // Setup event listeners
        function setupEventListeners() {
            // Search dengan debounce
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value.trim();
                    currentPage = 1; // Reset ke halaman 1
                    loadKontribusiData();
                }, 500);
            });

            // Per page
            document.getElementById('perPageSelect').addEventListener('change', function(e) {
                perPage = parseInt(e.target.value) || 10;
                currentPage = 1; // Reset ke halaman 1
                loadKontribusiData();
            });

            // Pagination buttons
            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    goToPage(currentPage - 1);
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    goToPage(currentPage + 1);
                }
            });

            // Modal backdrop clicks
            document.getElementById('createModal').addEventListener('click', function(e) {
                if (e.target === this) hideCreateModal();
            });

            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) hideEditModal();
            });

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) hideDeleteModal();
            });
        }

        // Initialize aplikasi
        async function initializeApp() {
            setupEventListeners();
            await loadKontribusiData(1); // Mulai dari halaman 1
        }

        // ============================================================================
        // PUBLIC API (KontribusiApp)
        // ============================================================================
        const KontribusiApp = {
            // Data & Table
            reloadData() {
                loadKontribusiData(1);
            },
            goToPage: goToPage,

            // Modals
            showCreateModal: showCreateModal,
            hideCreateModal: hideCreateModal,
            showEditModal: showEditModal,
            hideEditModal: hideEditModal,
            showDeleteModal: showDeleteModal,
            hideDeleteModal: hideDeleteModal,

            // Forms
            submitCreateForm: submitCreateForm,
            submitEditForm: submitEditForm,
            confirmDelete: confirmDelete
        };

        // ============================================================================
        // START APP
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();

            // Expose ke global scope
            window.KontribusiApp = KontribusiApp;
            window.showEditModal = showEditModal;
            window.showDeleteModal = showDeleteModal;

            // Debug
            window.debugPagination = function() {
                console.log('Pagination State:', {
                    currentPage,
                    totalPages,
                    totalRecords,
                    perPage,
                    searchQuery
                });
            };
        });
    </script>
@endpush
