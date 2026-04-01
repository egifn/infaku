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
                                    <th>Jenis</th>
                                    <th>Periode</th>
                                    <th>Kelompok</th>
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
                    <i class="bi-tags"></i>
                    <h4>Tidak ada data master kontribusi</h4>
                </div>

                <div id="loadingState" class="empty-state">
                    <div class="loading-animation"></div>
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

    <!-- ========== CREATE MODAL ========== -->
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

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis *</label>
                            <select class="form-select" name="jenis" id="createJenis" required>
                                <option value="BILLING" selected>Setoran</option>
                                <option value="SAVING">Tabungan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Periode *</label>
                            <select class="form-select" name="periode" id="createPeriode" required>
                                <option value="MONTHLY" selected>Bulanan</option>
                                <option value="WEEKLY">Mingguan</option>
                            </select>
                        </div>
                    </div>

                    <div id="createPeriodeRange" class="form-row" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Tgl Mulai</label>
                            <input type="date" class="form-control" name="tgl_mulai" id="createTglMulai">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tgl Selesai</label>
                            <input type="date" class="form-control" name="tgl_selesai" id="createTglSelesai">
                        </div>
                    </div>

                    <div id="createTargets" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Target Kontribusi</label>
                            <div class="form-text">Isi daftar target tabungan (contoh: Sapi 4.500.000)</div>
                        </div>
                        <div id="createTargetList"></div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="KontribusiApp.addTargetRow('create')">
                            <i class="bi-plus"></i> Tambah Target
                        </button>
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

    <!-- ========== EDIT MODAL ========== -->
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

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis *</label>
                            <select class="form-select" name="jenis" id="editJenis" required>
                                <option value="BILLING">Setoran</option>
                                <option value="SAVING">Tabungan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Periode *</label>
                            <select class="form-select" name="periode" id="editPeriode" required>
                                <option value="MONTHLY">Bulanan</option>
                                <option value="WEEKLY">Mingguan</option>
                            </select>
                        </div>
                    </div>

                    <div id="editPeriodeRange" class="form-row" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Tgl Mulai</label>
                            <input type="date" class="form-control" name="tgl_mulai" id="editTglMulai">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tgl Selesai</label>
                            <input type="date" class="form-control" name="tgl_selesai" id="editTglSelesai">
                        </div>
                    </div>

                    <div id="editTargets" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Target Kontribusi</label>
                            <div class="form-text">Isi daftar target tabungan (contoh: Sapi 4.500.000)</div>
                        </div>
                        <div id="editTargetList"></div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="KontribusiApp.addTargetRow('edit')">
                            <i class="bi-plus"></i> Tambah Target
                        </button>
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

    <!-- ========== DELETE CONFIRMATION MODAL ========== -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Kategori Kontribusi</h3>
                <button class="modal-close" onclick="KontribusiApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori <strong id="deleteItemName"></strong>?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KontribusiApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-danger" onclick="KontribusiApp.confirmDelete()">Hapus</button>
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

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .code-badge {
            font-family: monospace;
            background: #e9ecef;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 12px;
            border: 1px solid #ced4da;
        }

        .form-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 0.875em;
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
                maxNamaLength: 100
            };

            // STATE - Semua data aplikasi disimpan di sini
            const State = {
                currentPage: 1,
                totalPages: 1,
                totalRecords: 0,
                searchQuery: '',
                perPage: CONFIG.defaultPerPage,
                isLoading: false,
                deleteId: null,

                resetToFirstPage() {
                    this.currentPage = 1;
                },

                updatePagination(data) {
                    this.currentPage = parseInt(data.current_page) || 1;
                    this.totalPages = parseInt(data.last_page) || 1;
                    this.totalRecords = parseInt(data.total) || 0;
                }
            };

            // API Routes
            const API = {
                data: '{{ route('admin.kelompok.api.master-kontribusi.index') }}',
                detail: (id) => '{{ route('admin.kelompok.api.master-kontribusi.show', '') }}/' + id,
                create: '{{ route('admin.kelompok.api.master-kontribusi.store') }}',
                update: (id) => '{{ route('admin.kelompok.api.master-kontribusi.update', '') }}/' + id,
                destroy: (id) => '{{ route('admin.kelompok.api.master-kontribusi.destroy', '') }}/' + id
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

                // Format nomor urut
                formatRowNumber(index) {
                    return ((State.currentPage - 1) * State.perPage) + index + 1;
                },

                // Validasi form kontribusi
                validateForm(data, isEdit = false) {
                    if (!data.nama_kontribusi || data.nama_kontribusi.trim() === '') {
                        this.showToast('Nama kategori harus diisi', 'error');
                        return false;
                    }

                    if (data.nama_kontribusi.length > CONFIG.maxNamaLength) {
                        this.showToast(`Nama kategori maksimal ${CONFIG.maxNamaLength} karakter`, 'error');
                        return false;
                    }

                    if (data.jenis === 'SAVING') {
                        if (!data.tgl_mulai || !data.tgl_selesai) {
                            this.showToast('Tgl mulai & tgl selesai wajib untuk saving', 'error');
                            return false;
                        }
                        if (!data.targets || data.targets.length === 0) {
                            this.showToast('Target kontribusi wajib diisi untuk saving', 'error');
                            return false;
                        }
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

                    if (loadingEl) loadingEl.style.display = isLoading ? 'block' : 'none';
                    if (emptyEl && isLoading) emptyEl.style.display = 'none';
                },

                // Build query string
                buildQuery() {
                    const params = new URLSearchParams({
                        page: State.currentPage,
                        per_page: State.perPage
                    });

                    if (State.searchQuery) {
                        params.append('search', State.searchQuery);
                    }

                    return params.toString();
                },

                toggleSavingFields(prefix, isSaving) {
                    const range = document.getElementById(prefix + 'PeriodeRange');
                    const targets = document.getElementById(prefix + 'Targets');
                    const periode = document.getElementById(prefix + 'Periode');

                    if (isSaving) {
                        if (range) range.style.display = 'grid';
                        if (targets) targets.style.display = 'block';
                        if (periode) periode.value = 'WEEKLY';
                    } else {
                        if (range) range.style.display = 'none';
                        if (targets) targets.style.display = 'none';
                        if (periode) periode.value = 'MONTHLY';
                    }
                },

                targetRowHtml(prefix, nama = '', nominal = '') {
                    return `
                        <div class="form-row" data-target-row="1">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Nama target"
                                    value="${this.escapeHtml(nama)}">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Nominal"
                                    value="${nominal}">
                            </div>
                            <div class="form-group" style="display:flex; align-items:center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="KontribusiApp.removeTargetRow(this)">
                                    <i class="bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                },

                addTargetRow(prefix, nama = '', nominal = '') {
                    const list = document.getElementById(prefix + 'TargetList');
                    if (!list) return;
                    list.insertAdjacentHTML('beforeend', this.targetRowHtml(prefix, nama, nominal));
                },

                resetTargets(prefix) {
                    const list = document.getElementById(prefix + 'TargetList');
                    if (list) list.innerHTML = '';
                },

                fillTargets(prefix, targets) {
                    if (!targets || targets.length === 0) return;
                    targets.forEach(t => {
                        this.addTargetRow(prefix, t.nama_target || '', t.nominal_target || '');
                    });
                },

                collectTargets(prefix) {
                    const list = document.getElementById(prefix + 'TargetList');
                    if (!list) return [];
                    const rows = list.querySelectorAll('[data-target-row="1"]');
                    const result = [];
                    rows.forEach(row => {
                        const inputs = row.querySelectorAll('input');
                        const nama = inputs[0]?.value?.trim() || '';
                        const nominal = inputs[1]?.value ? parseFloat(inputs[1].value) : 0;
                        if (nama) {
                            result.push({ nama_target: nama, nominal_target: nominal });
                        }
                    });
                    return result;
                }
            };

            // ============================================================================
            // API CALLS
            // ============================================================================
            const Api = {
                async getKontribusi() {
                    if (State.isLoading) return null;

                    Helpers.setLoading(true);

                    try {
                        const url = `${API.data}?${Helpers.buildQuery()}`;
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
                        const res = await fetch(API.detail(id));
                        const result = await res.json();
                        return result.success ? result : null;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.showToast('Gagal memuat detail', 'error');
                        return null;
                    }
                },

                async create(data) {
                    const res = await fetch(API.create, {
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
                        const statusBadge = item.is_aktif ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-danger">Tidak Aktif</span>';

                        const jenisBadge = item.jenis === 'SAVING' ?
                            '<span class="badge badge-info">Tabungan</span>' :
                            '<span class="badge badge-success">Setoran</span>';
                        const periodeText = item.periode === 'WEEKLY' ? 'Mingguan' : 'Bulanan';

                        return `<tr>
                    <td>${no}</td>
                    <td>
                        <span class="code-badge">${item.kode_kontribusi || '-'}</span>
                    </td>
                    <td>${Helpers.escapeHtml(item.nama_kontribusi)}</td>
                    <td>${jenisBadge}</td>
                    <td>${periodeText}</td>
                    <td>
                        <span class="badge badge-info">${item.nama_kelompok || 'KELOMPOK'}</span>
                    </td>
                    <td>${statusBadge}</td>
                    <td>${item.keterangan ? Helpers.escapeHtml(item.keterangan) : '-'}</td>
                    <td>
                        <button class="btn btn-edit btn-sm" onclick="KontribusiApp.showEditModal('${item.id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="KontribusiApp.showDeleteModal('${item.id}', '${Helpers.escapeHtml(item.nama_kontribusi)}')" title="Hapus">
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

                showCreate() {
                    document.getElementById('createForm').reset();
                    document.getElementById('statusCreate').style.display = 'none';
                    Helpers.resetTargets('create');
                    Helpers.toggleSavingFields('create', false);
                    document.getElementById('createJenis').value = 'BILLING';
                    document.getElementById('createPeriode').value = 'MONTHLY';
                    document.getElementById('createTglMulai').value = '';
                    document.getElementById('createTglSelesai').value = '';
                    this.show('createModal');

                    // Focus ke input setelah modal muncul
                    setTimeout(() => {
                        document.querySelector('#createForm input[name="nama_kontribusi"]')?.focus();
                    }, 300);
                },

                async showEdit(id) {
                    const res = await Api.getDetail(id);
                    if (!res) return;

                    const data = res.data;
                    const targets = res.targets || [];

                    document.getElementById('editKontribusiId').value = data.id;
                    document.getElementById('editNamaKontribusi').value = data.nama_kontribusi;
                    document.getElementById('editKeterangan').value = data.keterangan || '';
                    document.getElementById('editIsAktif').value = data.is_aktif ? '1' : '0';
                    document.getElementById('editJenis').value = data.jenis || 'BILLING';
                    document.getElementById('editPeriode').value = data.periode || 'MONTHLY';
                    document.getElementById('editTglMulai').value = data.tgl_mulai || '';
                    document.getElementById('editTglSelesai').value = data.tgl_selesai || '';

                    Helpers.resetTargets('edit');
                    Helpers.fillTargets('edit', targets);
                    Helpers.toggleSavingFields('edit', (data.jenis === 'SAVING'));

                    this.show('editModal');

                    // Focus ke input setelah modal muncul
                    setTimeout(() => {
                        document.getElementById('editNamaKontribusi')?.focus();
                    }, 300);
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
                getCreateData() {
                    const form = document.getElementById('createForm');
                    const formData = new FormData(form);

                    return {
                        nama_kontribusi: formData.get('nama_kontribusi')?.trim(),
                        keterangan: formData.get('keterangan')?.trim() || '',
                        is_aktif: true, // Default true untuk create
                        jenis: formData.get('jenis') || 'BILLING',
                        periode: formData.get('periode') || 'MONTHLY',
                        tgl_mulai: formData.get('tgl_mulai') || null,
                        tgl_selesai: formData.get('tgl_selesai') || null,
                        targets: Helpers.collectTargets('create')
                    };
                },

                getEditData() {
                    return {
                        nama_kontribusi: document.getElementById('editNamaKontribusi')?.value?.trim(),
                        keterangan: document.getElementById('editKeterangan')?.value?.trim() || '',
                        is_aktif: document.getElementById('editIsAktif')?.value === '1',
                        jenis: document.getElementById('editJenis')?.value || 'BILLING',
                        periode: document.getElementById('editPeriode')?.value || 'MONTHLY',
                        tgl_mulai: document.getElementById('editTglMulai')?.value || null,
                        tgl_selesai: document.getElementById('editTglSelesai')?.value || null,
                        targets: Helpers.collectTargets('edit')
                    };
                },

                async submitCreate() {
                    const data = this.getCreateData();

                    if (!Helpers.validateForm(data)) return;

                    try {
                        const result = await Api.create(data);

                        if (result.success) {
                            Modal.hide('createModal');
                            State.resetToFirstPage();
                            await loadData();
                            Helpers.showToast('Data kontribusi berhasil ditambahkan', 'success');
                        } else {
                            throw new Error(result.message || 'Gagal menambahkan data');
                        }
                    } catch (error) {
                        Helpers.showToast(error.message, 'error');
                    }
                },

                async submitEdit() {
                    const id = document.getElementById('editKontribusiId')?.value;
                    const data = this.getEditData();

                    if (!Helpers.validateForm(data, true)) return;

                    try {
                        const result = await Api.update(id, data);

                        if (result.success) {
                            Modal.hide('editModal');
                            await loadData(); // Reload di halaman yang sama
                            Helpers.showToast('Data kontribusi berhasil diupdate', 'success');
                        } else {
                            throw new Error(result.message || 'Gagal mengupdate data');
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
                            Helpers.showToast('Data kontribusi berhasil dihapus', 'success');
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

                const result = await Api.getKontribusi();
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
                // Search debounce
                let timeout;
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        State.searchQuery = e.target.value.trim();
                        State.resetToFirstPage();
                        loadData();
                    }, CONFIG.debounceDelay);
                });

                // Per page
                document.getElementById('perPageSelect').addEventListener('change', function(e) {
                    State.perPage = parseInt(e.target.value) || CONFIG.defaultPerPage;
                    State.resetToFirstPage();
                    loadData();
                });

                // Toggle saving fields
                document.getElementById('createJenis')?.addEventListener('change', function(e) {
                    Helpers.toggleSavingFields('create', e.target.value === 'SAVING');
                });

                document.getElementById('editJenis')?.addEventListener('change', function(e) {
                    Helpers.toggleSavingFields('edit', e.target.value === 'SAVING');
                });

                // Pagination
                document.getElementById('prevPage').addEventListener('click', () => {
                    if (State.currentPage > 1) loadData(State.currentPage - 1);
                });

                document.getElementById('nextPage').addEventListener('click', () => {
                    if (State.currentPage < State.totalPages) loadData(State.currentPage + 1);
                });

                // Modal backdrop clicks
                ['createModal', 'editModal', 'deleteModal'].forEach(id => {
                    document.getElementById(id)?.addEventListener('click', function(e) {
                        if (e.target === this) Modal.hide(id);
                    });
                });

                // Prevent form submission on Enter
                const forms = ['createForm', 'editForm'];
                forms.forEach(formId => {
                    document.getElementById(formId)?.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                        }
                    });
                });
            }

            // ============================================================================
            // INITIALIZE
            // ============================================================================
            async function init() {
                setupListeners();
                await loadData(1);

                // Expose ke global
                window.KontribusiApp = {
                    // Data & Table
                    reloadData: () => loadData(1),

                    // Modals
                    showCreateModal: () => Modal.showCreate(),
                    hideCreateModal: () => Modal.hide('createModal'),
                    showEditModal: (id) => Modal.showEdit(id),
                    hideEditModal: () => Modal.hide('editModal'),
                    showDeleteModal: (id, name) => Modal.showDelete(id, name),
                    hideDeleteModal: () => Modal.hide('deleteModal'),

                    // Forms
                    submitCreateForm: () => Form.submitCreate(),
                    submitEditForm: () => Form.submitEdit(),
                    confirmDelete: () => Form.confirmDelete(),
                    addTargetRow: (prefix) => Helpers.addTargetRow(prefix),
                    removeTargetRow: (btn) => btn.closest('[data-target-row="1"]')?.remove()
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
