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
                        <select id="masterFilter" class="form-select" style="width: 250px;">
                            <option value="">Pilih Kategori Kontribusi</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input"
                            placeholder="Cari nama sub kontribusi...">
                        <i class="bi-search search-icon"></i>
                    </div>
                </div>

                <!-- Initial State -->
                <div id="initialState" class="empty-state">
                    <i class="bi-filter-circle"></i>
                    <h4>Pilih Kategori Kontribusi Terlebih Dahulu</h4>
                    <p class="text-muted">Gunakan dropdown di atas untuk memilih kategori</p>
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
                                    <th>Kesanggupan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-list-ul"></i>
                    <h4>Belum ada data sub kontribusi</h4>
                    <p class="text-muted">Klik tombol Tambah Sub Kontribusi untuk menambahkan data</p>
                </div>

                <div id="loadingState" class="empty-state" style="display: none;">
                    <div class="loading-animation"></div>
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

    <!-- ========== FORM MODAL ========== -->
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
                        <input type="text" class="form-control" id="nama_kontribusi" name="nama_kontribusi"
                            maxlength="100" required placeholder="Contoh: Infaq Jumat, Zakat Fitrah">
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
                                    min="0" required placeholder="0">
                                <span class="value-prefix" id="valuePrefix">Rp</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                            placeholder="Deskripsi tambahan..."></textarea>
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

    <!-- ========== DELETE MODAL ========== -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Sub Kontribusi</h3>
                <button class="modal-close" onclick="SubKontribusiApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus sub kontribusi <strong id="deleteItemName"></strong>?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="SubKontribusiApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-danger" onclick="SubKontribusiApp.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        /* ===== STYLES ===== */
        .loading-animation {
            height: 20px;
            width: 200px;
            margin: 0 auto 10px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Value Input Group */
        .value-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .value-prefix {
            position: absolute;
            right: 10px;
            color: #666;
            font-weight: 500;
            background: #f8f9fa;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Badge Styles */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge-primary {
            background: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }

        /* Value Display */
        .value-display {
            font-family: monospace;
            font-weight: 500;
        }

        .nominal-value {
            color: #0d6efd;
        }

        .percentage-value {
            color: #198754;
        }

        .text-muted {
            color: #6c757d;
            font-size: 14px;
            margin-top: 8px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 0.875em;
        }

        /* Empty State */
        .empty-state i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 16px;
        }

        .empty-state h4 {
            color: #495057;
            margin-bottom: 8px;
        }

        /* Dropdown Filter */
        #masterFilter {
            min-width: 250px;
        }
    </style>

    <script>
        (function() {
            // ============================================================================
            // KONFIGURASI & STATE
            // ============================================================================
            const CONFIG = {
                debounceDelay: 500,
                defaultPerPage: 10,
                minSearchChars: 2,
                maxNamaLength: 100
            };

            // STATE - Semua data aplikasi disimpan di sini
            const State = {
                currentPage: 1,
                totalPages: 1,
                searchQuery: '',
                perPage: CONFIG.defaultPerPage,
                isLoading: false,
                deleteId: null,
                currentMasterId: null,
                masterOptions: [],

                resetToFirstPage() {
                    this.currentPage = 1;
                },

                updatePagination(data) {
                    this.currentPage = parseInt(data.current_page) || 1;
                    this.totalPages = parseInt(data.last_page) || 1;
                },

                setMasterId(id) {
                    this.currentMasterId = id;
                }
            };

            // API Routes
            const API = {
                index: '{{ route('admin.kelompok.api.sub-kontribusi.getdata') }}',
                show: (id) => '{{ route('admin.kelompok.api.sub-kontribusi.show', '') }}/' + id,
                store: '{{ route('admin.kelompok.api.sub-kontribusi.store') }}',
                update: (id) => '{{ route('admin.kelompok.api.sub-kontribusi.update', '') }}/' + id,
                destroy: (id) => '{{ route('admin.kelompok.api.sub-kontribusi.destroy', '') }}/' + id,
                masterOptions: '{{ route('admin.kelompok.api.sub-kontribusi.master-options') }}'
            };

            // ============================================================================
            // HELPER FUNCTIONS
            // ============================================================================
            const Helpers = {
                // ESCAPE HTML - PENTING UNTUK KEAMANAN!
                escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                },

                // Format number ke format Indonesia
                formatNumber(num) {
                    if (num === undefined || num === null) return '0';
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                // Format nomor urut
                formatRowNumber(index) {
                    return ((State.currentPage - 1) * State.perPage) + index + 1;
                },

                // Format nilai berdasarkan jenis
                formatValue(value, jenis) {
                    if (value === undefined || value === null) return '-';

                    if (jenis === 'percentage') {
                        return `${parseFloat(value).toFixed(1)}%`;
                    } else {
                        return `Rp ${this.formatNumber(parseFloat(value))}`;
                    }
                },

                // Validasi form sub kontribusi
                validateForm(data) {
                    if (!data.master_kontribusi_id) {
                        this.showToast('Kategori kontribusi harus dipilih', 'error');
                        return false;
                    }

                    if (!data.nama_kontribusi || data.nama_kontribusi.trim() === '') {
                        this.showToast('Nama sub kontribusi harus diisi', 'error');
                        return false;
                    }

                    if (data.nama_kontribusi.length > CONFIG.maxNamaLength) {
                        this.showToast(`Nama sub kontribusi maksimal ${CONFIG.maxNamaLength} karakter`, 'error');
                        return false;
                    }

                    if (!data.jenis) {
                        this.showToast('Jenis nilai harus dipilih', 'error');
                        return false;
                    }

                    if (data.value === undefined || data.value === null || isNaN(data.value) || data.value < 0) {
                        this.showToast('Nilai harus diisi dengan angka positif', 'error');
                        return false;
                    }

                    return true;
                },

                // Toast notification
                showToast(message, type = 'info') {
                    if (window.showToast) {
                        window.showToast(message, type);
                    } else {
                        alert(message);
                    }
                },

                // Loading state
                setLoading(isLoading) {
                    State.isLoading = isLoading;
                    const loadingEl = document.getElementById('loadingState');
                    const emptyEl = document.getElementById('emptyState');
                    const initialState = document.getElementById('initialState');

                    if (loadingEl) loadingEl.style.display = isLoading ? 'block' : 'none';
                    if (emptyEl && isLoading) emptyEl.style.display = 'none';
                    if (initialState && !isLoading && !State.currentMasterId) {
                        initialState.style.display = 'block';
                    }
                },

                // Build query string
                buildQuery() {
                    const params = new URLSearchParams({
                        page: State.currentPage,
                        per_page: State.perPage,
                        master_id: State.currentMasterId
                    });

                    if (State.searchQuery && State.currentMasterId) {
                        params.append('search', State.searchQuery);
                    }

                    return params.toString();
                },

                // Toggle UI berdasarkan master ID
                toggleMasterState(masterId) {
                    const initialState = document.getElementById('initialState');
                    const addBtn = document.getElementById('addBtn');
                    const tableSection = document.getElementById('tableSection');
                    const emptyState = document.getElementById('emptyState');
                    const loadingState = document.getElementById('loadingState');

                    if (masterId) {
                        initialState.style.display = 'none';
                        addBtn.style.display = 'flex';
                        tableSection.style.display = 'block';
                        emptyState.style.display = 'none';
                        loadingState.style.display = 'none';
                    } else {
                        initialState.style.display = 'block';
                        addBtn.style.display = 'none';
                        tableSection.style.display = 'none';
                        emptyState.style.display = 'none';
                        loadingState.style.display = 'none';
                    }
                }
            };

            // ============================================================================
            // API CALLS
            // ============================================================================
            const Api = {
                async getSubKontribusi() {
                    if (State.isLoading || !State.currentMasterId) return null;

                    Helpers.setLoading(true);

                    try {
                        const url = `${API.index}?${Helpers.buildQuery()}`;
                        const res = await fetch(url);
                        const result = await res.json();

                        if (!result.success) {
                            throw new Error(result.message || 'Gagal memuat data');
                        }
                        return result;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.showToast(error.message, 'error');
                        return null;
                    } finally {
                        Helpers.setLoading(false);
                    }
                },

                async getDetail(id) {
                    try {
                        const res = await fetch(API.show(id));
                        const result = await res.json();
                        return result.success ? result.data : null;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.showToast('Gagal memuat detail', 'error');
                        return null;
                    }
                },

                async getMasterOptions() {
                    try {
                        const res = await fetch(API.masterOptions);
                        const result = await res.json();
                        return result.success ? result.data : [];
                    } catch (error) {
                        console.error('Error loading master options:', error);
                        return [];
                    }
                },

                async create(data) {
                    const res = await fetch(API.store, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });
                    return await res.json();
                },

                async update(id, data) {
                    const res = await fetch(API.update(id), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });
                    return await res.json();
                },

                async delete(id) {
                    const res = await fetch(API.destroy(id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    return await res.json();
                }
            };

            // ============================================================================
            // UI RENDER
            // ============================================================================
            const UI = {
                tableBody: document.getElementById('tableBody'),
                emptyState: document.getElementById('emptyState'),
                pagination: document.getElementById('pagination'),
                pageInfo: document.getElementById('pageInfo'),
                masterFilter: document.getElementById('masterFilter'),
                formMasterDropdown: document.getElementById('master_kontribusi_id'),

                renderTable(data) {
                    if (!data || data.length === 0) {
                        this.showEmpty();
                        return;
                    }

                    Helpers.setLoading(false);
                    this.emptyState.style.display = 'none';
                    this.pagination.style.display = 'flex';

                    const rows = data.map((item, index) => {
                        const no = Helpers.formatRowNumber(index);
                        const jenisBadge = item.jenis === 'percentage' ?
                            '<span class="badge badge-primary">Persentase</span>' :
                            '<span class="badge badge-success">Nominal</span>';

                        const statusBadge = item.is_active ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-danger">Tidak Aktif</span>';

                        const valueFormatted = Helpers.formatValue(item.value, item.jenis);
                        const level = item.level ? item.level.charAt(0).toUpperCase() + item.level.slice(
                            1) : '-';

                        return `<tr>
                    <td>${no}</td>
                    <td><strong>${Helpers.escapeHtml(item.nama_kontribusi)}</strong></td>
                    <td>${jenisBadge}</td>
                    <td>
                        <div class="value-display ${item.jenis === 'percentage' ? 'percentage-value' : 'nominal-value'}">
                            ${valueFormatted}
                        </div>
                    </td>
                    <td>${level}</td>
                    <td>${statusBadge}</td>
                    <td>${item.keterangan ? Helpers.escapeHtml(item.keterangan) : '-'}</td>
                    <td>
                        <button class="btn btn-edit btn-sm" onclick="SubKontribusiApp.showEditModal('${item.id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="SubKontribusiApp.showDeleteModal('${item.id}', '${Helpers.escapeHtml(item.nama_kontribusi)}')" title="Hapus">
                            <i class="bi-trash"></i>
                        </button>
                    </td>
                </tr>`;
                    }).join('');

                    this.tableBody.innerHTML = rows;
                    this.updatePagination();
                },

                showEmpty() {
                    this.tableBody.innerHTML = '';
                    this.emptyState.style.display = 'block';
                    this.pagination.style.display = 'none';
                    Helpers.setLoading(false);
                },

                updatePagination() {
                    if (this.pageInfo) {
                        this.pageInfo.textContent = `Halaman ${State.currentPage} dari ${State.totalPages}`;
                    }

                    const prev = document.getElementById('prevPage');
                    const next = document.getElementById('nextPage');

                    if (prev) {
                        prev.disabled = State.currentPage <= 1;
                        prev.classList.toggle('disabled', State.currentPage <= 1);
                    }

                    if (next) {
                        next.disabled = State.currentPage >= State.totalPages;
                        next.classList.toggle('disabled', State.currentPage >= State.totalPages);
                    }
                },

                updateMasterDropdowns(options) {
                    State.masterOptions = options;

                    // Update filter dropdown
                    let filterOptions = '<option value="">Pilih Kategori Kontribusi</option>';
                    let formOptions = '<option value="">Pilih Kategori</option>';

                    options.forEach(master => {
                        const displayText = `${master.nama_kontribusi} (${master.kode_kontribusi})`;
                        const val = master.id || master.master_kontribusi_id;

                        filterOptions +=
                            `<option value="${val}">${Helpers.escapeHtml(displayText)}</option>`;
                        formOptions += `<option value="${val}">${Helpers.escapeHtml(displayText)}</option>`;
                    });

                    if (this.masterFilter) this.masterFilter.innerHTML = filterOptions;
                    if (this.formMasterDropdown) this.formMasterDropdown.innerHTML = formOptions;
                },

                setSelectedMaster(id) {
                    if (this.masterFilter) this.masterFilter.value = id;
                    if (this.formMasterDropdown) this.formMasterDropdown.value = id;
                },

                toggleValuePrefix() {
                    const jenis = document.getElementById('jenis')?.value;
                    const prefix = document.getElementById('valuePrefix');
                    const valueInput = document.getElementById('value');

                    if (!prefix || !valueInput) return;

                    if (jenis === 'percentage') {
                        prefix.textContent = '%';
                        valueInput.step = '0.01';
                        valueInput.placeholder = '0.00';
                    } else {
                        prefix.textContent = 'Rp';
                        valueInput.step = '1';
                        valueInput.placeholder = '0';
                    }
                }
            };

            // ============================================================================
            // MODAL CONTROLS
            // ============================================================================
            const Modal = {
                show(id) {
                    document.getElementById(id)?.classList.add('show');
                },

                hide(id) {
                    document.getElementById(id)?.classList.remove('show');
                },

                showForm() {
                    if (!State.currentMasterId) {
                        Helpers.showToast('Pilih kategori kontribusi terlebih dahulu', 'warning');
                        return;
                    }

                    document.getElementById('modalTitle').textContent = 'Tambah Sub Kontribusi';
                    document.getElementById('editId').value = '';
                    document.getElementById('kontribusiForm').reset();
                    document.getElementById('master_kontribusi_id').value = State.currentMasterId;

                    UI.toggleValuePrefix();
                    this.show('formModal');

                    // Focus ke input nama
                    setTimeout(() => {
                        document.getElementById('nama_kontribusi')?.focus();
                    }, 300);
                },

                async showEdit(id) {
                    const data = await Api.getDetail(id);
                    if (!data) return;

                    document.getElementById('modalTitle').textContent = 'Edit Sub Kontribusi';
                    document.getElementById('editId').value = data.sub_kat_id;
                    document.getElementById('currentMasterId').value = data.id;

                    document.getElementById('nama_kontribusi').value = data.nama_kontribusi;
                    document.getElementById('keterangan').value = data.keterangan || '';
                    document.getElementById('is_active').value = data.is_active ? '1' : '0';
                    document.getElementById('master_kontribusi_id').value = data.id;
                    document.getElementById('jenis').value = data.jenis;
                    document.getElementById('value').value = data.value;

                    UI.toggleValuePrefix();
                    this.show('formModal');
                },

                showDelete(id, name) {
                    State.deleteId = id;
                    document.getElementById('deleteItemName').textContent = name;
                    this.show('deleteModal');
                }
            };

            // ============================================================================
            // FORM HANDLERS
            // ============================================================================
            const Form = {
                getFormData() {
                    const form = document.getElementById('kontribusiForm');
                    const formData = new FormData(form);
                    const id = document.getElementById('editId').value;

                    const valueRaw = formData.get('value');
                    const valueParsed = valueRaw === null || valueRaw === '' ? NaN : parseFloat(valueRaw);

                    return {
                        id: id || null,
                        master_kontribusi_id: State.currentMasterId || formData.get('master_kontribusi_id'),
                        nama_kontribusi: (formData.get('nama_kontribusi') || '').trim(),
                        value: valueParsed,
                        jenis: formData.get('jenis'),
                        keterangan: (formData.get('keterangan') || '').trim(),
                        is_active: formData.get('is_active') === '1'
                    };
                },

                async submit() {
                    const data = this.getFormData();

                    if (!Helpers.validateForm(data)) return;

                    try {
                        const result = data.id ?
                            await Api.update(data.id, data) :
                            await Api.create(data);

                        if (result.success) {
                            Modal.hide('formModal');
                            await loadData();
                            Helpers.showToast(
                                data.id ? 'Sub kontribusi berhasil diupdate' :
                                'Sub kontribusi berhasil ditambahkan',
                                'success'
                            );
                        } else {
                            throw new Error(result.message || 'Gagal menyimpan data');
                        }
                    } catch (error) {
                        Helpers.showToast(error.message, 'error');
                    }
                },

                async confirmDelete() {
                    if (!State.deleteId) return;

                    try {
                        const result = await Api.delete(State.deleteId);

                        if (result.success) {
                            Modal.hide('deleteModal');
                            State.deleteId = null;
                            await loadData();
                            Helpers.showToast('Sub kontribusi berhasil dihapus', 'success');
                        } else {
                            throw new Error(result.message || 'Gagal menghapus data');
                        }
                    } catch (error) {
                        Helpers.showToast(error.message, 'error');
                    }
                }
            };

            // ============================================================================
            // MAIN FUNCTION
            // ============================================================================
            async function loadData(page = null) {
                if (page !== null) State.currentPage = page;

                const result = await Api.getSubKontribusi();
                if (result?.success) {
                    State.updatePagination(result);
                    UI.renderTable(result.data);
                } else {
                    UI.showEmpty();
                }
            }

            // ============================================================================
            // EVENT LISTENERS
            // ============================================================================
            function setupListeners() {
                // Master filter change
                let filterTimeout;
                document.getElementById('masterFilter').addEventListener('change', function(e) {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(() => {
                        const masterId = e.target.value;
                        State.setMasterId(masterId);
                        Helpers.toggleMasterState(masterId);

                        if (masterId) {
                            State.resetToFirstPage();
                            loadData();
                        }
                    }, 300);
                });

                // Search debounce
                let searchTimeout;
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (!State.currentMasterId) {
                            Helpers.showToast('Pilih kategori kontribusi terlebih dahulu', 'warning');
                            e.target.value = ''; // Reset input
                            return;
                        }
                        State.searchQuery = e.target.value.trim();
                        State.resetToFirstPage();
                        loadData();
                    }, CONFIG.debounceDelay);
                });

                // Per page
                document.getElementById('perPageSelect').addEventListener('change', function(e) {
                    State.perPage = parseInt(e.target.value) || CONFIG.defaultPerPage;
                    State.resetToFirstPage();
                    if (State.currentMasterId) {
                        loadData();
                    }
                });

                // Pagination
                document.getElementById('prevPage').addEventListener('click', () => {
                    if (State.currentPage > 1) loadData(State.currentPage - 1);
                });

                document.getElementById('nextPage').addEventListener('click', () => {
                    if (State.currentPage < State.totalPages) loadData(State.currentPage + 1);
                });

                // Modal backdrop clicks
                ['formModal', 'deleteModal'].forEach(id => {
                    document.getElementById(id)?.addEventListener('click', function(e) {
                        if (e.target === this) Modal.hide(id);
                    });
                });

                // Prevent form submission on Enter
                document.getElementById('kontribusiForm')?.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });
            }

            // ============================================================================
            // INITIALIZE
            // ============================================================================
            async function init() {
                // Load master options
                const options = await Api.getMasterOptions();
                UI.updateMasterDropdowns(options);

                setupListeners();

                // Expose ke global
                window.SubKontribusiApp = {
                    // Modals
                    showFormModal: () => Modal.showForm(),
                    hideFormModal: () => Modal.hide('formModal'),
                    showEditModal: (id) => Modal.showEdit(id),
                    showDeleteModal: (id, name) => Modal.showDelete(id, name),
                    hideDeleteModal: () => Modal.hide('deleteModal'),

                    // Forms
                    submitForm: () => Form.submit(),
                    confirmDelete: () => Form.confirmDelete(),

                    // Helpers
                    toggleValuePrefix: () => UI.toggleValuePrefix(),

                    // Data
                    reloadData: () => loadData(1)
                };

                // Untuk inline onclick
                window.showEditModal = (id) => Modal.showEdit(id);
                window.showDeleteModal = (id, name) => Modal.showDelete(id, name);
            }

            // START!
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endpush
