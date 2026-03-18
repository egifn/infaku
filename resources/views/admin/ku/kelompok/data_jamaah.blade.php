@extends('layouts.app')

@section('title', 'Data Jamaah')
@section('page-title', 'Data Jamaah')
@section('icon-page-title', 'bi-people')

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Jamaah</h3>
                <div>
                    <button class="btn btn-print" onclick="JamaahApp.printData()">
                        <i class="bi-printer"></i> Print
                    </button>
                    <button class="btn btn-primary" onclick="JamaahApp.showCreateModal()">
                        <i class="bi-plus"></i> Tambah Jamaah
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="table-controls">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="perPageSelect" class="form-select" style="width:auto; padding:5px 0px">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <select id="filterAktif" class="form-select" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, telepon...">
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
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>TTL</th>
                                    <th>Telepon</th>
                                    <th>Pekerjaan</th>
                                    <th>Status</th>
                                    <th>Dapuan</th>
                                    <th>Status Aktif</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-people"></i>
                    <h4>Tidak ada data jamaah</h4>
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
                <h3 class="modal-title">Tambah Jamaah</h3>
                <button class="modal-close" onclick="JamaahApp.hideCreateModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" name="nama_lengkap" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin *</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Golongan Darah</label>
                            <select class="form-select" name="golongan_darah">
                                <option value="-">Tidak Tahu</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status Menikah *</label>
                            <select class="form-select" name="status_menikah" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Menikah">Belum Menikah</option>
                                <option value="Menikah">Menikah</option>
                                <option value="Janda">Janda</option>
                                <option value="Duda">Duda</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Dapuan *</label>
                            <select class="form-select" id="createDapuanSelect" name="dapuan_id" required>
                                <option value="">Pilih Dapuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status Aktif *</label>
                            <select class="form-select" name="is_aktif" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="JamaahApp.hideCreateModal()">Batal</button>
                <button class="btn btn-success" onclick="JamaahApp.submitCreateForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- ========== EDIT MODAL ========== -->
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Edit Jamaah</h3>
                <button class="modal-close" onclick="JamaahApp.hideEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editJamaahId">

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="editNamaLengkap" name="nama_lengkap" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="editTempatLahir" name="tempat_lahir">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="editTanggalLahir" name="tanggal_lahir">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin *</label>
                            <select class="form-select" id="editJenisKelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Golongan Darah</label>
                            <select class="form-select" id="editGolonganDarah" name="golongan_darah">
                                <option value="-">Tidak Tahu</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status Menikah *</label>
                            <select class="form-select" id="editStatusMenikah" name="status_menikah" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Menikah">Belum Menikah</option>
                                <option value="Menikah">Menikah</option>
                                <option value="Janda">Janda</option>
                                <option value="Duda">Duda</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="editPekerjaan" name="pekerjaan">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="editTelepon" name="telepon" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Dapuan *</label>
                            <select class="form-select" id="editDapuanSelect" name="dapuan_id" required>
                                <option value="">Pilih Dapuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status Aktif *</label>
                            <select class="form-select" id="editIsAktif" name="is_aktif" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" id="editAlamat" name="alamat" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="JamaahApp.hideEditModal()">Batal</button>
                <button class="btn btn-success" onclick="JamaahApp.submitEditForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- ========== DETAIL MODAL ========== -->
    <div class="modal" id="detailModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Detail Jamaah</h3>
                <button class="modal-close" onclick="JamaahApp.hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody"></div>
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

        .detail-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 12px;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            color: #333;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
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
    </style>

    <script>
        (function() {
            // ============================================================================
            // KONFIGURASI & STATE
            // ============================================================================
            const CONFIG = {
                debounceDelay: 500,
                defaultPerPage: 10,
                maxTeleponLength: 15
            };

            // STATE - Semua data aplikasi disimpan di sini
            const State = {
                currentPage: 1,
                totalPages: 1,
                totalRecords: 0,
                searchQuery: '',
                aktifFilter: '',
                perPage: CONFIG.defaultPerPage,
                dapuanOptions: [],
                isLoading: false,

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
                data: '{{ route('admin.kelompok.api.jamaah.index') }}',
                detail: (id) => '{{ route('admin.kelompok.api.jamaah.show', '') }}/' + id,
                create: '{{ route('admin.kelompok.api.jamaah.store') }}',
                update: (id) => '{{ route('admin.kelompok.api.jamaah.update', '') }}/' + id,
                dapuan: '{{ route('admin.kelompok.api.jamaah.dapuan-options') }}',
                print: '{{ route('admin.kelompok.data-jamaah.print') }}'
            };

            // ============================================================================
            // HELPER FUNCTIONS
            // ============================================================================
            const Helpers = {
                // Format tanggal ke Indonesia
                formatDate(dateString) {
                    if (!dateString || dateString === '0000-01-01') return '-';
                    try {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                    } catch (e) {
                        return '-';
                    }
                },

                // Format status
                formatStatus(status) {
                    if (!status) return '-';
                    return status.replace('_', ' ');
                },

                // ESCAPE HTML - PENTING UNTUK KEAMANAN!
                escapeHtml(text) {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                },

                // Validasi form
                validateForm(formData) {
                    const required = ['nama_lengkap', 'jenis_kelamin', 'status_menikah', 'dapuan_id'];
                    const missing = required.filter(field => !formData[field]);

                    if (missing.length > 0) {
                        this.showToast('Harap isi semua field yang wajib diisi', 'error');
                        return false;
                    }

                    if (formData.telepon && formData.telepon.length > CONFIG.maxTeleponLength) {
                        this.showToast(`Nomor telepon maksimal ${CONFIG.maxTeleponLength} karakter`, 'error');
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

                    if (loadingEl) loadingEl.style.display = isLoading ? 'block' : 'none';
                    if (emptyEl && isLoading) emptyEl.style.display = 'none';
                },

                // Build query string
                buildQuery() {
                    const params = new URLSearchParams({
                        page: State.currentPage,
                        per_page: State.perPage
                    });

                    if (State.searchQuery) params.append('search', State.searchQuery);
                    if (State.aktifFilter !== '') params.append('is_aktif', State.aktifFilter);

                    return params.toString();
                }
            };

            // ============================================================================
            // API CALLS
            // ============================================================================
            const Api = {
                async getJamaah() {
                    if (State.isLoading) return null;

                    Helpers.setLoading(true);

                    try {
                        const url = `${API.data}?${Helpers.buildQuery()}`;
                        const res = await fetch(url);
                        const result = await res.json();

                        if (!result.success) throw new Error(result.message || 'Gagal memuat data');
                        return result;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.showToast(error.message, 'error');
                        return null;
                    } finally {
                        Helpers.setLoading(false);
                    }
                },

                async getDapuanOptions() {
                    try {
                        const res = await fetch(API.dapuan);
                        const result = await res.json();
                        if (result.success) State.dapuanOptions = result.data || [];
                    } catch (error) {
                        console.error('Error loading dapuan:', error);
                        State.dapuanOptions = [];
                    }
                },

                async getDetail(id) {
                    try {
                        const res = await fetch(API.detail(id));
                        const result = await res.json();
                        return result.success ? result.data : null;
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

                    const startNum = ((State.currentPage - 1) * State.perPage) + 1;

                    const rows = data.map((item, idx) => {
                        const num = startNum + idx;
                        const ttl =
                            `${item.tempat_lahir || '-'}, ${Helpers.formatDate(item.tanggal_lahir)}`;
                        const statusBadge = item.is_aktif ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-danger">Tidak Aktif</span>';

                        return `<tr>
                    <td>${num}</td>
                    <td>${Helpers.escapeHtml(item.nama_lengkap || '')}</td>
                    <td>${item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                    <td>${ttl}</td>
                    <td>${item.telepon || '-'}</td>
                    <td>${Helpers.escapeHtml(item.pekerjaan || '-')}</td>
                    <td><span class="badge badge-info">${Helpers.formatStatus(item.status_menikah)}</span></td>
                    <td>${item.nama_role || '-'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-edit btn-sm" onclick="JamaahApp.showEditModal('${item.id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="JamaahApp.showDetailModal('${item.id}')" title="Detail">
                            <i class="bi-eye"></i>
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

                    // Update emptyState message
                    const emptyStateMsg = this.emptyState.querySelector('h4');
                    if (emptyStateMsg) {
                        if (State.searchQuery || State.aktifFilter !== '') {
                            emptyStateMsg.textContent = 'Data tidak ditemukan';
                        } else {
                            emptyStateMsg.textContent = 'Tidak ada data jamaah';
                        }
                    }
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

                fillDapuan(dropdownId, selected = '') {
                    const dropdown = document.getElementById(dropdownId);
                    if (!dropdown) return;

                    let options = '<option value="">Pilih Dapuan</option>';
                    State.dapuanOptions.forEach(d => {
                        const sel = String(d.role_id) === String(selected) ? 'selected' : '';
                        options +=
                            `<option value="${d.role_id}" ${sel}>${Helpers.escapeHtml(d.nama_role)}</option>`;
                    });

                    dropdown.innerHTML = options;
                },

                showDetail(data) {
                    const html = `
                <div class="detail-grid">
                    <div class="detail-label">Nama Lengkap</div>
                    <div class="detail-value">${Helpers.escapeHtml(data.nama_lengkap)}</div>
                    
                    <div class="detail-label">Jenis Kelamin</div>
                    <div class="detail-value">${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</div>
                    
                    <div class="detail-label">TTL</div>
                    <div class="detail-value">${data.tempat_lahir || '-'}, ${Helpers.formatDate(data.tanggal_lahir)}</div>
                    
                    <div class="detail-label">Telepon</div>
                    <div class="detail-value">${data.telepon || '-'}</div>
                    
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${data.email || '-'}</div>
                    
                    <div class="detail-label">Pekerjaan</div>
                    <div class="detail-value">${Helpers.escapeHtml(data.pekerjaan || '-')}</div>
                    
                    <div class="detail-label">Status</div>
                    <div class="detail-value">${Helpers.formatStatus(data.status_menikah)}</div>
                    
                    <div class="detail-label">Dapuan</div>
                    <div class="detail-value">${data.nama_role || '-'}</div>
                    
                    <div class="detail-label">Status Aktif</div>
                    <div class="detail-value">${data.is_aktif ? 'Aktif' : 'Tidak Aktif'}</div>
                    
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">${Helpers.escapeHtml(data.alamat || '-')}</div>
                </div>
            `;
                    document.getElementById('detailBody').innerHTML = html;
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

                async showCreate() {
                    document.getElementById('createForm').reset();
                    UI.fillDapuan('createDapuanSelect');
                    this.show('createModal');
                },

                async showEdit(id) {
                    const data = await Api.getDetail(id);
                    if (!data) return;

                    document.getElementById('editJamaahId').value = data.id;
                    document.getElementById('editNamaLengkap').value = data.nama_lengkap;
                    document.getElementById('editTempatLahir').value = data.tempat_lahir || '';

                    if (data.tanggal_lahir && data.tanggal_lahir !== '0000-01-01') {
                        document.getElementById('editTanggalLahir').value = data.tanggal_lahir.split('T')[0];
                    }

                    document.getElementById('editJenisKelamin').value = data.jenis_kelamin;
                    document.getElementById('editGolonganDarah').value = data.golongan_darah || '-';
                    document.getElementById('editStatusMenikah').value = data.status_menikah;
                    document.getElementById('editPekerjaan').value = data.pekerjaan || '';
                    document.getElementById('editTelepon').value = data.telepon || '';
                    document.getElementById('editEmail').value = data.email || '';
                    document.getElementById('editAlamat').value = data.alamat || '';
                    document.getElementById('editIsAktif').value = data.is_aktif ? '1' : '0';

                    UI.fillDapuan('editDapuanSelect', data.dapuan_id);
                    this.show('editModal');
                },

                async showDetail(id) {
                    const data = await Api.getDetail(id);
                    if (data) {
                        UI.showDetail(data);
                        this.show('detailModal');
                    }
                }
            };

            // ============================================================================
            // FORM HANDLERS
            // ============================================================================
            const Form = {
                getData(formId, withId = false) {
                    const form = document.getElementById(formId);
                    const fd = new FormData(form);

                    const data = {
                        nama_lengkap: fd.get('nama_lengkap'),
                        tempat_lahir: fd.get('tempat_lahir'),
                        tanggal_lahir: fd.get('tanggal_lahir'),
                        jenis_kelamin: fd.get('jenis_kelamin'),
                        golongan_darah: fd.get('golongan_darah'),
                        status_menikah: fd.get('status_menikah'),
                        pekerjaan: fd.get('pekerjaan'),
                        telepon: fd.get('telepon'),
                        email: fd.get('email'),
                        alamat: fd.get('alamat'),
                        dapuan_id: fd.get('dapuan_id'),
                        is_aktif: fd.get('is_aktif') === '1'
                    };

                    if (withId) data.id = document.getElementById('editJamaahId')?.value;
                    return data;
                },

                async submitCreate() {
                    const data = this.getData('createForm');
                    if (!Helpers.validateForm(data)) return;

                    try {
                        const result = await Api.create(data);
                        if (result.success) {
                            Modal.hide('createModal');
                            State.resetToFirstPage();
                            await loadData();
                            Helpers.showToast('Data berhasil ditambahkan', 'success');
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        Helpers.showToast(error.message, 'error');
                    }
                },

                async submitEdit() {
                    const data = this.getData('editForm');
                    const id = document.getElementById('editJamaahId').value;

                    if (!Helpers.validateForm(data)) return;

                    try {
                        const result = await Api.update(id, data);
                        if (result.success) {
                            Modal.hide('editModal');
                            await loadData();
                            Helpers.showToast('Data berhasil diupdate', 'success');
                        } else {
                            throw new Error(result.message);
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

                const result = await Api.getJamaah();
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

                // Filter
                document.getElementById('filterAktif').addEventListener('change', function(e) {
                    State.aktifFilter = e.target.value;
                    State.resetToFirstPage();
                    loadData();
                });

                // Per page
                document.getElementById('perPageSelect').addEventListener('change', function(e) {
                    State.perPage = parseInt(e.target.value) || CONFIG.defaultPerPage;
                    State.resetToFirstPage();
                    loadData();
                });

                // Pagination
                document.getElementById('prevPage').addEventListener('click', () => {
                    if (State.currentPage > 1) loadData(State.currentPage - 1);
                });

                document.getElementById('nextPage').addEventListener('click', () => {
                    if (State.currentPage < State.totalPages) loadData(State.currentPage + 1);
                });

                // Modal backdrop
                ['createModal', 'editModal', 'detailModal'].forEach(id => {
                    document.getElementById(id)?.addEventListener('click', function(e) {
                        if (e.target === this) Modal.hide(id);
                    });
                });
            }

            // ============================================================================
            // INITIALIZE
            // ============================================================================
            async function init() {
                await Api.getDapuanOptions();
                setupListeners();
                await loadData(1);

                // Expose ke global
                window.JamaahApp = {
                    printData: () => window.open(API.print, '_blank'),
                    showCreateModal: () => Modal.showCreate(),
                    hideCreateModal: () => Modal.hide('createModal'),
                    showEditModal: (id) => Modal.showEdit(id),
                    hideEditModal: () => Modal.hide('editModal'),
                    showDetailModal: (id) => Modal.showDetail(id),
                    hideDetailModal: () => Modal.hide('detailModal'),
                    submitCreateForm: () => Form.submitCreate(),
                    submitEditForm: () => Form.submitEdit()
                };

                // Untuk inline onclick
                window.showEditModal = (id) => Modal.showEdit(id);
                window.showDetailModal = (id) => Modal.showDetail(id);
            }

            // START!
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endpush
