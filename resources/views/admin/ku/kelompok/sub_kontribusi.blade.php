@extends('layouts.app')

@section('title', 'Sub Kontribusi')
@section('page-title', 'Sub Kontribusi')
@section('icon-page-title', 'bi-list-ul')

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sub Kontribusi</h3>
                <button class="btn btn-primary" id="addBtn" onclick="SubKontribusiApp.showFormModal()"
                    style="display: none;">
                    <i class="bi-plus"></i> Tambah Sub Kontribusi
                </button>
            </div>
            <div class="card-body">
                <!-- Table Controls -->
                <div class="table-controls" id="tableControls">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="perPageSelect" class="form-select" style="width:auto; padding:5px 0px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <select id="masterFilter" class="form-select" style="width: 150px;">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, telepon...">
                        <i class="bi-search search-icon"></i>
                    </div>
                </div>

                <!-- Initial State -->
                <div id="initialState" class="empty-state">
                    <i class="bi-filter-circle"></i>
                    <h4>Pilih Kategori Kontribusi Terlebih Dahulu</h4>
                </div>

                <!-- Table -->
                <div class="table-container" id="tableSection" style="display: none;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Sub Kontribusi</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
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
                    <i class="bi-list-ul"></i>
                    <p>Belum ada data sub kontribusi</p>
                </div>

                <div id="loadingState" class="empty-state" style="display: none;">
                    <div style="height: 20px; width: 200px; margin: 0 auto 10px; background: #f0f0f0; border-radius: 4px;">
                    </div>
                    <div style="height: 20px; width: 150px; margin: 0 auto; background: #f0f0f0; border-radius: 4px;"></div>
                </div>

                <!-- Pagination -->
                <div class="pagination" id="pagination" style="display: none;">
                    <button class="page-btn" id="prevPage">
                        <i class="bi-chevron-left"></i> Prev
                    </button>
                    <span class="page-info" id="pageInfo">Halaman 1 dari 1</span>
                    <button class="page-btn" id="nextPage">
                        Next <i class="bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------- MODALS ---------- -->
    <!-- Form Modal -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Sub Kontribusi</h3>
                <button class="modal-close" onclick="SubKontribusiApp.hideFormModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="kontribusiForm">
                    <input type="hidden" id="editId">
                    <input type="hidden" id="currentMasterId">

                    <div class="form-group">
                        <label class="form-label">Kategori Kontribusi *</label>
                        <select class="form-select" id="master_kontribusi_id" name="master_kontribusi_id" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Sub Kontribusi *</label>
                        <input type="text" class="form-control" id="nama_kontribusi" name="nama_kontribusi" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Nilai *</label>
                            <select class="form-select" id="jenis" name="jenis" required
                                onchange="SubKontribusiApp.toggleValuePrefix()">
                                <option value="">Pilih Jenis</option>
                                <option value="nominal">Nominal (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nilai *</label>
                            <div class="value-input-group">
                                <input type="number" class="form-control" id="value" name="value" step="1"
                                    min="0" style="width:94%;" required>
                                <span class="value-prefix" id="valuePrefix">Rp</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="SubKontribusiApp.hideFormModal()">Batal</button>
                <button class="btn btn-success" onclick="SubKontribusiApp.submitForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Sub Kontribusi</h3>
                <button class="modal-close" onclick="SubKontribusiApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus sub kontribusi <strong id="deleteItemName"></strong>?</p>
                <p class="form-text">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="SubKontribusiApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="SubKontribusiApp.confirmDelete()">Hapus</button>
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
        let searchQuery = '';
        let deleteId = null;
        let currentMasterId = null;
        let masterOptions = [];
        let perPage = 10;
        let isLoading = false;

        const API_ROUTES = {
            index: '{{ route('admin.kelompok.api.sub-kontribusi.index') }}',
            show: '{{ route('admin.kelompok.api.sub-kontribusi.show', '') }}',
            store: '{{ route('admin.kelompok.api.sub-kontribusi.store') }}',
            update: '{{ route('admin.kelompok.api.sub-kontribusi.update', '') }}',
            destroy: '{{ route('admin.kelompok.api.sub-kontribusi.destroy', '') }}',
            masterOptions: '{{ route('admin.kelompok.api.sub-kontribusi.master-options') }}'
        };

        // ============================================================================
        // FUNGSI UTAMA - LOAD DATA & RENDER TABEL
        // ============================================================================

        // Load data sub kontribusi
        async function loadData(page = null) {
            if (isLoading || !currentMasterId) return;

            if (page !== null && page >= 1) {
                currentPage = page;
            }

            showLoadingState();
            isLoading = true;

            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage,
                    master_id: currentMasterId
                });

                // Hanya tambahkan search jika ada query dan kategori dipilih
                if (searchQuery && currentMasterId) {
                    params.append('search', searchQuery);
                }

                const url = `${API_ROUTES.index}?${params.toString()}`;
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
                showToast('Error', error.message, 'error');
                showEmptyState();
            } finally {
                hideLoadingState();
                isLoading = false;
            }
        }

        // Render tabel
        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const pagination = document.getElementById('pagination');

            if (!data || data.length === 0) {
                showEmptyState();
                return;
            }

            // Pastikan table section selalu muncul jika ada data
            showTableSection();
            if (loadingState) loadingState.style.display = 'none';
            if (emptyState) emptyState.style.display = 'none';
            if (pagination) pagination.style.display = 'flex';

            const startNumber = ((currentPage - 1) * perPage) + 1;
            const tableRows = data.map((item, index) => {
                const rowNumber = startNumber + index;
                return `
                <tr>
                    <td>${rowNumber}</td>
                    <td><strong>${escapeHtml(item.nama_kontribusi)}</strong></td>
                    <td>
                        ${item.jenis === 'percentage' ? 
                            '<span class="badge badge-primary">Persentase</span>' : 
                            '<span class="badge badge-success">Nominal</span>'
                        }
                    </td>
                    <td>
                        <div class="value-display ${item.jenis === 'percentage' ? 'percentage-value' : 'nominal-value'}">
                            ${item.jenis === 'percentage' ? 
                                `${parseFloat(item.value).toFixed(1)}%` : 
                                `Rp ${formatNumber(parseFloat(item.value))}`
                            }
                        </div>
                    </td>
                    <td>
                        ${item.is_active ? 
                            '<span class="badge badge-success">Aktif</span>' : 
                            '<span class="badge badge-danger">Tidak Aktif</span>'
                        }
                    </td>
                    <td>${escapeHtml(item.keterangan || '-')}</td>
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
            const tableSection = document.getElementById('tableSection');

            if (tableBody) tableBody.innerHTML = '';
            if (emptyState) emptyState.style.display = 'block';
            if (loadingState) loadingState.style.display = 'none';
            if (pagination) pagination.style.display = 'none';
            if (tableSection) tableSection.style.display = 'none';
        }

        // Tampilkan table section
        function showTableSection() {
            const tableSection = document.getElementById('tableSection');
            const emptyState = document.getElementById('emptyState');
            const initialState = document.getElementById('initialState');

            if (tableSection) tableSection.style.display = 'block';
            if (emptyState) emptyState.style.display = 'none';
            if (initialState) initialState.style.display = 'none';
        }

        // Update pagination
        function updatePagination(data) {
            currentPage = parseInt(data.current_page) || 1;
            totalPages = parseInt(data.last_page) || 1;

            const pageInfo = document.getElementById('pageInfo');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');

            if (pageInfo) {
                pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;
            }

            if (prevBtn) {
                const isDisabled = currentPage <= 1;
                prevBtn.disabled = isDisabled;
                prevBtn.classList.toggle('disabled', isDisabled);
            }

            if (nextBtn) {
                const isDisabled = currentPage >= totalPages;
                nextBtn.disabled = isDisabled;
                nextBtn.classList.toggle('disabled', isDisabled);
            }
        }

        // Ganti halaman
        function goToPage(page) {
            if (page < 1 || page > totalPages || page === currentPage) {
                return;
            }
            loadData(page);
        }

        // ============================================================================
        // FUNGSI MODAL - FORM & DELETE
        // ============================================================================

        // Modal Form
        function showFormModal() {
            if (!currentMasterId) {
                showToast('Peringatan', 'Silakan pilih kategori kontribusi terlebih dahulu', 'warning');
                return;
            }

            document.getElementById('modalTitle').textContent = 'Tambah Sub Kontribusi';
            document.getElementById('editId').value = '';
            document.getElementById('kontribusiForm').reset();
            document.getElementById('master_kontribusi_id').value = currentMasterId;
            toggleValuePrefix();
            document.getElementById('formModal').classList.add('show');
        }

        function hideFormModal() {
            document.getElementById('formModal').classList.remove('show');
        }

        async function showEditModal(id) {
            try {
                const response = await fetch(`${API_ROUTES.show}/${id}`);
                const result = await response.json();

                if (result.success) {
                    const item = result.data;
                    document.getElementById('modalTitle').textContent = 'Edit Sub Kontribusi';
                    document.getElementById('editId').value = item.sub_kat_id;
                    document.getElementById('currentMasterId').value = item.id;

                    document.getElementById('nama_kontribusi').value = item.nama_kontribusi;
                    document.getElementById('keterangan').value = item.keterangan || '';
                    document.getElementById('is_active').value = item.is_active ? '1' : '0';
                    document.getElementById('master_kontribusi_id').value = item.id;
                    document.getElementById('jenis').value = item.jenis;
                    document.getElementById('value').value = item.value;
                    toggleValuePrefix();

                    document.getElementById('formModal').classList.add('show');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error loading item:', error);
                showToast('Error', error.message, 'error');
            }
        }

        // Modal Delete
        function showDeleteModal(id, name) {
            deleteId = id;
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        // ============================================================================
        // FUNGSI FORM - SUBMIT
        // ============================================================================

        // Submit form
        async function submitForm() {
            const formData = new FormData(document.getElementById('kontribusiForm'));
            const id = document.getElementById('editId').value;
            const masterId = document.getElementById('currentMasterId').value;

            const data = {
                master_kontribusi_id: masterId || formData.get('master_kontribusi_id'),
                nama_kontribusi: formData.get('nama_kontribusi'),
                value: parseFloat(formData.get('value')),
                jenis: formData.get('jenis'),
                keterangan: formData.get('keterangan'),
                is_active: formData.get('is_active') === '1'
            };

            // Validasi
            if (!data.nama_kontribusi || !data.jenis || !data.value || !data.master_kontribusi_id) {
                showToast('Peringatan', 'Harap isi semua field yang wajib diisi', 'warning');
                return;
            }

            if (data.value <= 0) {
                showToast('Peringatan', 'Nilai harus lebih dari 0', 'warning');
                return;
            }

            try {
                const url = id ? `${API_ROUTES.update}/${id}` : API_ROUTES.store;
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
                    hideFormModal();
                    loadData();
                    showToast('Berhasil', id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan', 'success');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showToast('Error', error.message, 'error');
            }
        }

        // Confirm delete
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
                    loadData();
                    showToast('Berhasil', 'Data berhasil dihapus', 'success');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                showToast('Error', error.message, 'error');
            }
        }

        // ============================================================================
        // FUNGSI BANTU (HELPER FUNCTIONS)
        // ============================================================================

        // Load master options
        async function loadMasterOptions() {
            try {
                const response = await fetch(API_ROUTES.masterOptions);
                const result = await response.json();

                if (result.success) {
                    masterOptions = result.data || [];
                    updateMasterDropdowns();
                }
            } catch (error) {
                console.error('Error loading master options:', error);
                masterOptions = [];
            }
        }

        // Update master dropdowns
        function updateMasterDropdowns() {
            const filterDropdown = document.getElementById('masterFilter');
            const formDropdown = document.getElementById('master_kontribusi_id');

            if (!filterDropdown || !formDropdown) return;

            let filterOptions = '<option value="">Pilih Kategori</option>';
            let formOptions = '<option value="">Pilih Kategori</option>';

            masterOptions.forEach(master => {
                const displayText = `${master.nama_kontribusi} (${master.kode_kontribusi})`;
                // Gunakan master.id jika ada, fallback ke master.master_kontribusi_id
                const val = typeof master.id !== 'undefined' ? master.id : master.master_kontribusi_id;
                filterOptions +=
                    `<option value="${val}">${escapeHtml(displayText)}</option>`;
                formOptions +=
                    `<option value="${val}">${escapeHtml(displayText)}</option>`;
            });

            filterDropdown.innerHTML = filterOptions;
            formDropdown.innerHTML = formOptions;
        }

        // Toggle value prefix
        function toggleValuePrefix() {
            const jenis = document.getElementById('jenis').value;
            const prefix = document.getElementById('valuePrefix');
            const valueInput = document.getElementById('value');

            if (jenis === 'percentage') {
                prefix.textContent = '%';
                valueInput.step = '0.0001';
            } else {
                prefix.textContent = 'Rp';
                valueInput.step = '1';
            }
        }

        // Format number
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

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
            const initialState = document.getElementById('initialState');
            if (loadingState) loadingState.style.display = 'block';
            if (emptyState) emptyState.style.display = 'none';
            if (initialState) initialState.style.display = 'none';
        }

        function hideLoadingState() {
            const loadingState = document.getElementById('loadingState');
            if (loadingState) loadingState.style.display = 'none';
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
        // EVENT LISTENERS & INITIALIZATION
        // ============================================================================

        // Setup event listeners
        function setupEventListeners() {
            // Filter master dengan debounce
            let filterTimeout;
            document.getElementById('masterFilter').addEventListener('change', function(e) {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(() => {
                    const masterId = e.target.value;
                    currentMasterId = masterId;

                    if (masterId) {
                        document.getElementById('initialState').style.display = 'none';
                        document.getElementById('addBtn').style.display = 'flex';
                        showTableSection();
                        currentPage = 1;
                        loadData();
                    } else {
                        document.getElementById('initialState').style.display = 'block';
                        document.getElementById('addBtn').style.display = 'none';
                        document.getElementById('tableSection').style.display = 'none';
                        document.getElementById('emptyState').style.display = 'none';
                        document.getElementById('loadingState').style.display = 'none';
                    }
                }, 300);
            });

            // Search dengan debounce, hanya aktif jika kategori dipilih
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (!currentMasterId) {
                        // Jika kategori belum dipilih, jangan lakukan pencarian
                        searchQuery = '';
                        return;
                    }
                    searchQuery = e.target.value.trim();
                    // Reset ke default jika input kosong
                    if (!searchQuery) searchQuery = '';
                    currentPage = 1;
                    loadData();
                }, 500);
            });

            // Per page
            document.getElementById('perPageSelect').addEventListener('change', function(e) {
                perPage = parseInt(e.target.value) || 10;
                currentPage = 1;
                loadData();
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
            document.getElementById('formModal').addEventListener('click', function(e) {
                if (e.target === this) hideFormModal();
            });

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) hideDeleteModal();
            });
        }

        // Initialize aplikasi
        async function initializeApp() {
            await loadMasterOptions();
            setupEventListeners();
        }

        // ============================================================================
        // PUBLIC API (SubKontribusiApp)
        // ============================================================================
        const SubKontribusiApp = {
            reloadData() {
                loadData(1);
            },
            goToPage: goToPage,
            showFormModal: showFormModal,
            hideFormModal: hideFormModal,
            showEditModal: showEditModal,
            hideDeleteModal: hideDeleteModal,
            submitForm: submitForm,
            confirmDelete: confirmDelete,
            toggleValuePrefix: toggleValuePrefix
        };

        // ============================================================================
        // START APP
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();

            // Expose ke global scope
            window.SubKontribusiApp = SubKontribusiApp;
            window.showEditModal = showEditModal;
            window.showDeleteModal = showDeleteModal;
        });
    </script>
@endpush
