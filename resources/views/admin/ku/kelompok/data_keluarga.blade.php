@extends('layouts.app')

@section('title', 'Data Keluarga')
@section('page-title', 'Data Keluarga')
@section('icon-page-title', 'bi-house-door')

@section('content')
    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Keluarga</h3>
                <div>
                    <button class="btn btn-print" onclick="KeluargaApp.printData()">
                        <i class="bi-printer"></i> Print
                    </button>
                    <button class="btn btn-primary" onclick="KeluargaApp.showCreateModal()">
                        <i class="bi-plus"></i> Tambah Keluarga
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
                            placeholder="Cari nama keluarga, alamat...">
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
                                    <th>Nama Keluarga</th>
                                    <th>Kepala Keluarga</th>
                                    <th>Alamat</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-people"></i>
                    <h4>Tidak ada data keluarga</h4>
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

    <!-- ========== FORM MODAL (CREATE/EDIT) ========== -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideFormModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="keluargaForm">
                    <input type="hidden" id="editKeluargaId">
                    <input type="hidden" id="kepalaKeluargaId" name="kepala_keluarga_id">

                    <div class="form-group">
                        <label class="form-label">Nama Keluarga *</label>
                        <input type="text" class="form-control" id="namaKeluarga" name="nama_keluarga" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kepala Keluarga *</label>
                        <div class="searchable-dropdown">
                            <div class="dropdown-search-container">
                                <input style="width: 95%;margin-right: 5px;" type="text"
                                    class="form-control dropdown-search-input" id="kepalaKeluargaSearch"
                                    placeholder="Ketik minimal 2 karakter..." autocomplete="off">
                                <i class="bi-search dropdown-search-icon"></i>
                            </div>
                            <div class="dropdown-options" id="kepalaKeluargaOptions"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideFormModal()">Batal</button>
                <button class="btn btn-success" onclick="KeluargaApp.submitForm()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- ========== DETAIL MODAL ========== -->
    <div class="modal" id="detailModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Detail Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody"></div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ========== DELETE CONFIRMATION MODAL ========== -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus keluarga <strong id="deleteItemName"></strong>?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan. Semua anggota keluarga juga akan
                    terhapus.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-danger" onclick="KeluargaApp.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <!-- ========== ANGGOTA MODAL ========== -->
    <div class="modal" id="anggotaModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Anggota Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideAnggotaModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="anggotaForm">
                    <input type="hidden" id="anggotaKeluargaId">

                    <div class="form-group">
                        <label class="form-label">Pilih Jamaah *</label>
                        <div class="searchable-dropdown">
                            <div class="dropdown-search-container">
                                <input type="text" class="form-control dropdown-search-input" id="anggotaJamaahSearch"
                                    placeholder="Ketik minimal 2 karakter..." autocomplete="off">
                                <i class="bi-search dropdown-search-icon"></i>
                            </div>
                            <div class="dropdown-options" id="anggotaJamaahOptions"></div>
                        </div>
                        <input type="hidden" id="anggotaJamaahId">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Hubungan *</label>
                        <select class="form-select" id="statusHubungan" required>
                            <option value="">Pilih Status</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" id="urutan" min="1" value="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideAnggotaModal()">Batal</button>
                <button class="btn btn-success" onclick="KeluargaApp.submitAnggotaForm()">Simpan</button>
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

        /* Dropdown Styles */
        .searchable-dropdown {
            position: relative;
        }

        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border: 1px solid #cfcfcf;
            border-top: none;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .option-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        .option-item:hover,
        .option-item:focus {
            background: #f0f7ff;
            outline: none;
        }

        .option-item:last-child {
            border-bottom: none;
        }

        .no-options,
        .loading-options {
            padding: 12px;
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .loading-options i {
            margin-right: 5px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            color: #333;
        }

        .anggota-list {
            margin-top: 20px;
        }

        .anggota-header {
            display: grid;
            grid-template-columns: 150px 2fr 1fr;
            gap: 12px;
            padding: 8px;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 500;
        }

        .anggota-item {
            display: grid;
            grid-template-columns: 150px 2fr 1fr;
            gap: 12px;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .empty-anggota {
            text-align: center;
            padding: 20px;
            color: #666;
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
                minSearchChars: 2
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
                data: '{{ route('admin.kelompok.api.keluarga.index') }}',
                detail: (id) => '{{ route('admin.kelompok.api.keluarga.show', '') }}/' + id,
                create: '{{ route('admin.kelompok.api.keluarga.store') }}',
                update: (id) => '{{ route('admin.kelompok.api.keluarga.update', '') }}/' + id,
                destroy: (id) => '{{ route('admin.kelompok.api.keluarga.destroy', '') }}/' + id,
                jamaahOptions: '{{ route('admin.kelompok.api.keluarga.jamaah-options') }}',
                jamaahFam: '{{ route('admin.kelompok.api.keluarga.jamaah-fam') }}',
                insertAnggota: '{{ route('admin.kelompok.api.anggota-keluarga.insert-anggota-keluarga') }}',
                print: '{{ route('admin.kelompok.data-keluarga.print') }}'
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

                // Truncate text
                truncate(text, length = 50) {
                    if (!text) return '-';
                    return text.length > length ? text.substring(0, length) + '...' : text;
                },

                // Validasi form keluarga
                validateKeluargaForm(data) {
                    if (!data.nama_keluarga) {
                        this.showToast('Nama keluarga harus diisi', 'error');
                        return false;
                    }
                    if (!data.kepala_keluarga_id) {
                        this.showToast('Kepala keluarga harus dipilih', 'error');
                        return false;
                    }
                    return true;
                },

                // Validasi form anggota
                validateAnggotaForm(data) {
                    if (!data.jamaah_id) {
                        this.showToast('Jamaah harus dipilih', 'error');
                        return false;
                    }
                    if (!data.status_hubungan) {
                        this.showToast('Status hubungan harus dipilih', 'error');
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

                    if (State.searchQuery) {
                        params.append('search', State.searchQuery);
                    }

                    return params.toString();
                },

                // Format nomor urut
                formatRowNumber(index) {
                    return ((State.currentPage - 1) * State.perPage) + index + 1;
                }
            };

            // ============================================================================
            // API CALLS
            // ============================================================================
            const Api = {
                async getKeluarga() {
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
                        return result.success ? result.data : null;
                    } catch (error) {
                        console.error('Error:', error);
                        Helpers.showToast('Gagal memuat detail', 'error');
                        return null;
                    }
                },

                async searchJamaah(query, type = 'kepala') {
                    const endpoint = type === 'kepala' ? API.jamaahOptions : API.jamaahFam;
                    try {
                        const res = await fetch(`${endpoint}?search=${encodeURIComponent(query)}`);
                        const result = await res.json();
                        return result.success ? result.data : [];
                    } catch (error) {
                        console.error('Error searching jamaah:', error);
                        return [];
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
                },

                async addAnggota(data) {
                    const res = await fetch(API.insertAnggota, {
                        method: 'POST',
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

                    const rows = data.map((item, index) => {
                        const no = Helpers.formatRowNumber(index);
                        const alamat = item.alamat ? Helpers.truncate(item.alamat) : '-';

                        return `<tr>
                    <td>${no}</td>
                    <td>${Helpers.escapeHtml(item.nama_keluarga)}</td>
                    <td>${Helpers.escapeHtml(item.kepala_keluarga_nama)}</td>
                    <td>${Helpers.escapeHtml(alamat)}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="KeluargaApp.showDetailModal('${item.keluarga_id}')" title="Detail">
                            <i class="bi-eye"></i>
                        </button>
                        <button class="btn btn-edit btn-sm" onclick="KeluargaApp.showEditModal('${item.keluarga_id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="KeluargaApp.showDeleteModal('${item.keluarga_id}', '${Helpers.escapeHtml(item.nama_keluarga)}')" title="Hapus">
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

                renderDetail(data) {
                    let html = `
                <div class="detail-grid">
                    <div class="detail-label">Nama Keluarga</div>
                    <div class="detail-value">${Helpers.escapeHtml(data.nama_keluarga)}</div>
                    
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">${data.alamat ? Helpers.escapeHtml(data.alamat) : '-'}</div>
                </div>
                
                <div class="anggota-list">
                    <h4 style="margin-bottom: 10px;">Anggota Keluarga</h4>
                    <div class="anggota-header">
                        <div>Status</div>
                        <div>Nama</div>
                        <div>Urutan</div>
                    </div>
            `;

                    // Kepala keluarga
                    html += `
                <div class="anggota-item">
                    <div>Kepala Keluarga</div>
                    <div>${Helpers.escapeHtml(data.kepala_keluarga_nama)}</div>
                    <div>-</div>
                </div>
            `;

                    // Anggota lainnya
                    if (data.anggota && data.anggota.length > 0) {
                        data.anggota.forEach(anggota => {
                            html += `
                        <div class="anggota-item">
                            <div>${Helpers.escapeHtml(anggota.status_hubungan)}</div>
                            <div>${Helpers.escapeHtml(anggota.nama_lengkap)}</div>
                            <div>${anggota.urutan || '-'}</div>
                        </div>
                    `;
                        });
                    } else {
                        html += `
                    <div class="empty-anggota">
                        <i class="bi-people" style="font-size: 48px; opacity: 0.3;"></i>
                        <p>Belum ada anggota keluarga lainnya</p>
                    </div>
                `;
                    }

                    html += `
                </div>
                <div style="margin-top: 20px; text-align: center;">
                    <button class="btn btn-primary" onclick="KeluargaApp.showAnggotaModal('${data.keluarga_id}')">
                        <i class="bi-plus"></i> Tambah Anggota
                    </button>
                </div>
            `;

                    document.getElementById('detailBody').innerHTML = html;
                }
            };

            // ============================================================================
            // DROPDOWN HANDLER
            // ============================================================================
            const Dropdown = {
                timeouts: {},

                async search(containerId, inputId, callback, type = 'kepala') {
                    const input = document.getElementById(inputId);
                    const container = document.getElementById(containerId);
                    const query = input.value.trim();

                    if (query.length < CONFIG.minSearchChars) {
                        container.innerHTML = '<div class="no-options">Ketik minimal 2 karakter</div>';
                        container.style.display = 'block';
                        return;
                    }

                    container.innerHTML =
                        '<div class="loading-options"><i class="bi-arrow-repeat"></i> Mencari...</div>';
                    container.style.display = 'block';

                    const results = await Api.searchJamaah(query, type);

                    if (results.length > 0) {
                        container.innerHTML = results.map(item => `
                    <div class="option-item" 
                         data-value="${item.jamaah_id}"
                         data-name="${Helpers.escapeHtml(item.nama_lengkap)}"
                         onclick="${callback}('${item.jamaah_id}', '${Helpers.escapeHtml(item.nama_lengkap)}')">
                        ${Helpers.escapeHtml(item.nama_lengkap)}
                    </div>
                `).join('');
                    } else {
                        container.innerHTML = '<div class="no-options">Tidak ditemukan jamaah</div>';
                    }
                },

                selectKepalaKeluarga(id, name) {
                    document.getElementById('kepalaKeluargaId').value = id;
                    document.getElementById('kepalaKeluargaSearch').value = name;
                    document.getElementById('kepalaKeluargaOptions').style.display = 'none';
                },

                selectAnggotaJamaah(id, name) {
                    document.getElementById('anggotaJamaahId').value = id;
                    document.getElementById('anggotaJamaahSearch').value = name;
                    document.getElementById('anggotaJamaahOptions').style.display = 'none';
                },

                setupKepalaDropdown() {
                    const input = document.getElementById('kepalaKeluargaSearch');
                    const container = document.getElementById('kepalaKeluargaOptions');

                    input.addEventListener('input', () => {
                        clearTimeout(this.timeouts.kepala);
                        this.timeouts.kepala = setTimeout(() => {
                            this.search('kepalaKeluargaOptions', 'kepalaKeluargaSearch',
                                'Dropdown.selectKepalaKeluarga', 'kepala');
                        }, 300);
                    });

                    input.addEventListener('focus', () => {
                        if (input.value.trim().length >= CONFIG.minSearchChars) {
                            this.search('kepalaKeluargaOptions', 'kepalaKeluargaSearch',
                                'Dropdown.selectKepalaKeluarga', 'kepala');
                        }
                    });
                },

                setupAnggotaDropdown() {
                    const input = document.getElementById('anggotaJamaahSearch');
                    const container = document.getElementById('anggotaJamaahOptions');

                    input.addEventListener('input', () => {
                        clearTimeout(this.timeouts.anggota);
                        this.timeouts.anggota = setTimeout(() => {
                            this.search('anggotaJamaahOptions', 'anggotaJamaahSearch',
                                'Dropdown.selectAnggotaJamaah', 'anggota');
                        }, 300);
                    });

                    input.addEventListener('focus', () => {
                        if (input.value.trim().length >= CONFIG.minSearchChars) {
                            this.search('anggotaJamaahOptions', 'anggotaJamaahSearch',
                                'Dropdown.selectAnggotaJamaah', 'anggota');
                        }
                    });
                }
            };

            // Expose dropdown functions to global
            window.Dropdown = Dropdown;

            // ============================================================================
            // MODAL CONTROLS
            // ============================================================================
            const Modal = {
                show(id) {
                    document.getElementById(id)?.classList.add('show');
                },

                hide(id) {
                    document.getElementById(id)?.classList.remove('show');
                    // Hide dropdowns when modal closes
                    if (id === 'formModal') {
                        document.getElementById('kepalaKeluargaOptions').style.display = 'none';
                    }
                    if (id === 'anggotaModal') {
                        document.getElementById('anggotaJamaahOptions').style.display = 'none';
                    }
                },

                showCreate() {
                    document.getElementById('modalTitle').textContent = 'Tambah Keluarga';
                    document.getElementById('editKeluargaId').value = '';
                    document.getElementById('keluargaForm').reset();
                    document.getElementById('kepalaKeluargaId').value = '';
                    this.show('formModal');
                    setTimeout(() => document.getElementById('kepalaKeluargaSearch').focus(), 300);
                },

                async showEdit(id) {
                    const data = await Api.getDetail(id);
                    if (!data) return;

                    document.getElementById('modalTitle').textContent = 'Edit Keluarga';
                    document.getElementById('editKeluargaId').value = data.keluarga_id;
                    document.getElementById('namaKeluarga').value = data.nama_keluarga;
                    document.getElementById('alamat').value = data.alamat || '';
                    document.getElementById('kepalaKeluargaId').value = data.kepala_keluarga_id;
                    document.getElementById('kepalaKeluargaSearch').value = data.kepala_keluarga_nama;

                    this.show('formModal');
                },

                async showDetail(id) {
                    const data = await Api.getDetail(id);
                    if (data) {
                        UI.renderDetail(data);
                        this.show('detailModal');
                    }
                },

                showDelete(id, name) {
                    State.deleteId = id;
                    document.getElementById('deleteItemName').textContent = name;
                    this.show('deleteModal');
                },

                showAnggota(keluargaId) {
                    document.getElementById('anggotaKeluargaId').value = keluargaId;
                    document.getElementById('anggotaForm').reset();
                    document.getElementById('anggotaJamaahId').value = '';

                    this.hide('detailModal');
                    setTimeout(() => {
                        this.show('anggotaModal');
                        setTimeout(() => document.getElementById('anggotaJamaahSearch').focus(), 300);
                    }, 300);
                }
            };

            // ============================================================================
            // FORM HANDLERS
            // ============================================================================
            const Form = {
                async submitKeluarga() {
                    const id = document.getElementById('editKeluargaId').value;
                    const data = {
                        nama_keluarga: document.getElementById('namaKeluarga').value,
                        kepala_keluarga_id: document.getElementById('kepalaKeluargaId').value,
                        alamat: document.getElementById('alamat').value
                    };

                    if (!Helpers.validateKeluargaForm(data)) return;

                    try {
                        const result = id ? await Api.update(id, data) : await Api.create(data);

                        if (result.success) {
                            Modal.hide('formModal');
                            State.resetToFirstPage();
                            await loadData();
                            Helpers.showToast(
                                id ? 'Data keluarga berhasil diupdate' :
                                'Data keluarga berhasil ditambahkan',
                                'success'
                            );
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        Helpers.showToast(error.message, 'error');
                    }
                },

                async submitAnggota() {
                    const data = {
                        keluarga_id: document.getElementById('anggotaKeluargaId').value,
                        jamaah_id: document.getElementById('anggotaJamaahId').value,
                        status_hubungan: document.getElementById('statusHubungan').value,
                        urutan: parseInt(document.getElementById('urutan').value) || 1
                    };

                    if (!Helpers.validateAnggotaForm(data)) return;

                    try {
                        const result = await Api.addAnggota(data);

                        if (result.success) {
                            Modal.hide('anggotaModal');
                            // Reload detail modal
                            Modal.showDetail(data.keluarga_id);
                            Helpers.showToast('Anggota keluarga berhasil ditambahkan', 'success');
                        } else {
                            throw new Error(result.message);
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
                            Helpers.showToast('Data keluarga berhasil dihapus', 'success');
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

                const result = await Api.getKeluarga();
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

                // Pagination
                document.getElementById('prevPage').addEventListener('click', () => {
                    if (State.currentPage > 1) loadData(State.currentPage - 1);
                });

                document.getElementById('nextPage').addEventListener('click', () => {
                    if (State.currentPage < State.totalPages) loadData(State.currentPage + 1);
                });

                // Modal backdrop clicks
                ['formModal', 'detailModal', 'deleteModal', 'anggotaModal'].forEach(id => {
                    document.getElementById(id)?.addEventListener('click', function(e) {
                        if (e.target === this) Modal.hide(id);
                    });
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', function(e) {
                    const kepalaSearch = document.getElementById('kepalaKeluargaSearch');
                    const kepalaOptions = document.getElementById('kepalaKeluargaOptions');
                    const anggotaSearch = document.getElementById('anggotaJamaahSearch');
                    const anggotaOptions = document.getElementById('anggotaJamaahOptions');

                    if (kepalaOptions && !kepalaOptions.contains(e.target) && e.target !== kepalaSearch) {
                        kepalaOptions.style.display = 'none';
                    }

                    if (anggotaOptions && !anggotaOptions.contains(e.target) && e.target !== anggotaSearch) {
                        anggotaOptions.style.display = 'none';
                    }
                });
            }

            // ============================================================================
            // INITIALIZE
            // ============================================================================
            async function init() {
                Dropdown.setupKepalaDropdown();
                Dropdown.setupAnggotaDropdown();
                setupListeners();
                await loadData(1);

                // Expose ke global
                window.KeluargaApp = {
                    // Data & Table
                    printData: () => window.open(API.print, '_blank'),
                    reloadData: () => loadData(1),

                    // Modals
                    showCreateModal: () => Modal.showCreate(),
                    hideFormModal: () => Modal.hide('formModal'),
                    showEditModal: (id) => Modal.showEdit(id),
                    showDetailModal: (id) => Modal.showDetail(id),
                    hideDetailModal: () => Modal.hide('detailModal'),
                    showDeleteModal: (id, name) => Modal.showDelete(id, name),
                    hideDeleteModal: () => Modal.hide('deleteModal'),
                    showAnggotaModal: (id) => Modal.showAnggota(id),
                    hideAnggotaModal: () => Modal.hide('anggotaModal'),

                    // Forms
                    submitForm: () => Form.submitKeluarga(),
                    submitAnggotaForm: () => Form.submitAnggota(),
                    confirmDelete: () => Form.confirmDelete()
                };

                // Untuk inline onclick
                window.showEditModal = (id) => Modal.showEdit(id);
                window.showDetailModal = (id) => Modal.showDetail(id);
                window.showDeleteModal = (id, name) => Modal.showDelete(id, name);
                window.showAnggotaModal = (id) => Modal.showAnggota(id);
            }

            // START!
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endpush
