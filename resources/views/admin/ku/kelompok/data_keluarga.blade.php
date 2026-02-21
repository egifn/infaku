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
                            <tbody id="tableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-people"></i>
                    <h4>Tidak ada data keluarga</h4>
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

    <!-- Create/Edit Modal -->
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
                                    placeholder="Ketik untuk mencari jamaah..." autocomplete="off">
                                <i class="bi-search dropdown-search-icon"></i>
                            </div>
                            <div class="dropdown-options" id="kepalaKeluargaOptions"
                                style="display: none;display: block;padding: 5px 13px;font-size: 12px;border: 1px solid #cfcfcf;width: 95%;margin-top: 5px;cursor: pointer;">
                            </div>
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

    <!-- Detail Modal -->
    <div class="modal" id="detailModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Detail Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                <!-- Data akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Keluarga</h3>
                <button class="modal-close" onclick="KeluargaApp.hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus keluarga <strong id="deleteItemName"></strong>?</p>
                <p class="form-text">Data yang dihapus tidak dapat dikembalikan. Semua anggota keluarga juga akan terhapus.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="KeluargaApp.hideDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="KeluargaApp.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Tambah Anggota Modal -->
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
                        <div class="searchable-dropdown" style="">
                            <div class="dropdown-search-container" style="display: flex;gap: 5px; align-item:center;">
                                <input type="text" class="form-control dropdown-search-input" id="anggotaJamaahSearch"
                                    placeholder="Ketik untuk mencari jamaah..." autocomplete="off"
                                    style="display: none;display: block;width: 95%;margin-right: 5px;">
                                <i class="bi-search dropdown-search-icon"></i>
                            </div>
                            <div class="dropdown-options" id="anggotaJamaahOptions"
                                style="display: none;display: block;padding: 5px 13px;font-size: 12px;border: 1px solid #cfcfcf;width: 95%;margin-top: 5px;cursor: pointer;">
                            </div>
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
        let kepalaKeluargaSearchTimeout = null;
        let anggotaJamaahSearchTimeout = null;

        const API_ROUTES = {
            data: '{{ route('admin.kelompok.api.keluarga.index') }}',
            detail: '{{ route('admin.kelompok.api.keluarga.show', '') }}',
            create: '{{ route('admin.kelompok.api.keluarga.store') }}',
            update: '{{ route('admin.kelompok.api.keluarga.update', '') }}',
            destroy: '{{ route('admin.kelompok.api.keluarga.destroy', '') }}',
            jamaahOptions: '{{ route('admin.kelompok.api.keluarga.jamaah-options') }}',
            jamaahFam: '{{ route('admin.kelompok.api.keluarga.jamaah-fam') }}',
            insertAnggota: '{{ route('admin.kelompok.api.anggota-keluarga.insert-anggota-keluarga') }}',
            print: '{{ route('admin.kelompok.data-keluarga.print') }}'
        };

        // ============================================================================
        // FUNGSI UTAMA - LOAD DATA & RENDER TABEL
        // ============================================================================

        // Fungsi untuk memuat data keluarga
        async function loadKeluargaData(page = null) {
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
                    <td>${escapeHtml(item.nama_keluarga)}</td>
                    <td>${escapeHtml(item.kepala_keluarga_nama)}</td>
                    <td>${item.alamat ? escapeHtml(item.alamat.substring(0, 50) + (item.alamat.length > 50 ? '...' : '')) : '-'}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="showDetailModal('${item.keluarga_id}')" title="Detail">
                            <i class="bi-eye"></i>
                        </button>
                        <button class="btn btn-edit btn-sm" onclick="showEditModal('${item.keluarga_id}')" title="Edit">
                            <i class="bi-pencil"></i>
                        </button>
                        <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.keluarga_id}', '${escapeHtml(item.nama_keluarga)}')" title="Hapus">
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
            loadKeluargaData(page);
        }

        // ============================================================================
        // FUNGSI MODAL - CREATE, EDIT, DETAIL, DELETE, ANGGOTA
        // ============================================================================

        // Modal Form (Create/Edit)
        function showCreateModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Keluarga';
            document.getElementById('editKeluargaId').value = '';
            document.getElementById('keluargaForm').reset();
            document.getElementById('kepalaKeluargaId').value = '';
            document.getElementById('kepalaKeluargaSearch').value = '';
            document.getElementById('formModal').classList.add('show');

            // Focus ke search input setelah modal muncul
            setTimeout(() => {
                document.getElementById('kepalaKeluargaSearch').focus();
            }, 300);
        }

        async function showEditModal(keluargaId) {
            try {
                const response = await fetch(`${API_ROUTES.detail}/${keluargaId}`);
                const result = await response.json();

                if (result.success) {
                    const keluarga = result.data;

                    document.getElementById('modalTitle').textContent = 'Edit Keluarga';
                    document.getElementById('editKeluargaId').value = keluarga.keluarga_id;
                    document.getElementById('namaKeluarga').value = keluarga.nama_keluarga;
                    document.getElementById('alamat').value = keluarga.alamat || '';
                    document.getElementById('kepalaKeluargaId').value = keluarga.kepala_keluarga_id;
                    document.getElementById('kepalaKeluargaSearch').value = keluarga.kepala_keluarga_nama;

                    document.getElementById('formModal').classList.add('show');
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

        function hideFormModal() {
            document.getElementById('formModal').classList.remove('show');
            document.getElementById('kepalaKeluargaOptions').style.display = 'none';
        }

        // Modal Detail
        async function showDetailModal(keluargaId) {
            try {
                const response = await fetch(`${API_ROUTES.detail}/${keluargaId}`);
                const result = await response.json();

                if (result.success) {
                    const keluarga = result.data;

                    let detailHtml = `
                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px;">
                        <div style="font-weight: 500;">Nama Keluarga</div>
                        <div>${escapeHtml(keluarga.nama_keluarga)}</div>
                        
                        <div style="font-weight: 500;">Alamat</div>
                        <div>${keluarga.alamat ? escapeHtml(keluarga.alamat) : '-'}</div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <h4 style="margin-bottom: 10px;">Anggota Keluarga</h4>
                `;

                    // Kepala keluarga
                    detailHtml += `
                      <div style="display: grid;grid-template-columns: 150px 2fr 1fr;gap: 12px;padding: 8px;border-bottom: 1px solid #eee;">
                                <div><strong>Keluarga</strong></div>
                                <div><strong>Nama</strong></div>
                                <div><strong>Urutan</strong></div>
                            </div>
                    <div style="display: grid;grid-template-columns: 150px 1fr;gap: 12px;padding: 8px;border-bottom: 1px solid #eeeeee;">
                        <div>Kepala Keluarga</div>
                        <div>${escapeHtml(keluarga.kepala_keluarga_nama)}</div>
                    </div>
                `;

                    // Anggota lainnya
                    if (keluarga.anggota && keluarga.anggota.length > 0) {
                        keluarga.anggota.forEach(anggota => {
                            detailHtml += `
                            <div style="display: grid;grid-template-columns: 150px 2fr 1fr;gap: 12px;padding: 8px;border-bottom: 1px solid #eee;">
                                <div>${escapeHtml(anggota.status_hubungan)}</div>
                                <div>${escapeHtml(anggota.nama_lengkap)}</div>
                                <div>${escapeHtml(anggota.urutan)}</div>
                            </div>
                        `;
                        });
                    } else {
                        detailHtml += `
                        <div style="text-align: center; padding: 20px; color: #666;">
                            <i class="bi-people" style="font-size: 48px; opacity: 0.3;"></i>
                            <p>Belum ada anggota keluarga lainnya</p>
                        </div>
                    `;
                    }

                    detailHtml += `
                    </div>
                    <div style="margin-top: 20px; text-align: center;">
                        <button class="btn btn-primary" onclick="showAnggotaModal('${keluarga.keluarga_id}')">
                            <i class="bi-plus"></i> Tambah Anggota
                        </button>
                    </div>
                `;

                    document.getElementById('detailBody').innerHTML = detailHtml;
                    document.getElementById('detailModal').classList.add('show');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        function hideDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        // Modal Delete
        function showDeleteModal(keluargaId, namaKeluarga) {
            deleteId = keluargaId;
            document.getElementById('deleteItemName').textContent = namaKeluarga;
            document.getElementById('deleteModal').classList.add('show');
        }

        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        // Modal Anggota
        function showAnggotaModal(keluargaId) {
            document.getElementById('anggotaKeluargaId').value = keluargaId;
            document.getElementById('anggotaForm').reset();
            document.getElementById('anggotaJamaahId').value = '';
            document.getElementById('anggotaJamaahSearch').value = '';
            document.getElementById('statusHubungan').value = '';
            document.getElementById('urutan').value = '1';

            hideDetailModal();
            setTimeout(() => {
                document.getElementById('anggotaModal').classList.add('show');
                setTimeout(() => {
                    document.getElementById('anggotaJamaahSearch').focus();
                }, 300);
            }, 300);
        }

        function hideAnggotaModal() {
            document.getElementById('anggotaModal').classList.remove('show');
            document.getElementById('anggotaJamaahOptions').style.display = 'none';
        }

        // ============================================================================
        // FUNGSI FORM - SUBMIT CREATE, EDIT, DELETE, ANGGOTA
        // ============================================================================

        // Submit Form (Create/Edit)
        async function submitForm() {
            const keluargaId = document.getElementById('editKeluargaId').value;
            const namaKeluarga = document.getElementById('namaKeluarga').value;
            const kepalaKeluargaId = document.getElementById('kepalaKeluargaId').value;
            const alamat = document.getElementById('alamat').value;

            // Validasi
            if (!namaKeluarga || !kepalaKeluargaId) {
                if (window.showToast) {
                    window.showToast('Harap isi semua field yang wajib diisi', 'error');
                }
                return;
            }

            const data = {
                nama_keluarga: namaKeluarga,
                kepala_keluarga_id: kepalaKeluargaId,
                alamat: alamat
            };

            try {
                const url = keluargaId ?
                    `${API_ROUTES.update}/${keluargaId}` :
                    API_ROUTES.create;
                const method = keluargaId ? 'PUT' : 'POST';

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
                    currentPage = 1; // Kembali ke halaman 1
                    loadKeluargaData();
                    if (window.showToast) {
                        window.showToast(keluargaId ? 'Data keluarga berhasil diupdate' :
                            'Data keluarga berhasil ditambahkan', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
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
                    loadKeluargaData();
                    if (window.showToast) {
                        window.showToast('Data keluarga berhasil dihapus', 'success');
                    }
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error deleting keluarga:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // Submit Anggota Form
        async function submitAnggotaForm() {
            const keluargaId = document.getElementById('anggotaKeluargaId').value;
            const jamaahId = document.getElementById('anggotaJamaahId').value;
            const statusHubungan = document.getElementById('statusHubungan').value;
            const urutan = document.getElementById('urutan').value || 1;

            // Validasi
            if (!keluargaId || !jamaahId || !statusHubungan) {
                if (window.showToast) {
                    window.showToast('Harap isi semua field yang wajib diisi', 'error');
                }
                return;
            }

            const data = {
                keluarga_id: keluargaId,
                jamaah_id: jamaahId,
                status_hubungan: statusHubungan,
                urutan: parseInt(urutan)
            };

            try {
                const response = await fetch(API_ROUTES.insertAnggota, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    hideAnggotaModal();
                    // Reload detail modal
                    showDetailModal(keluargaId);
                    if (window.showToast) {
                        window.showToast('Anggota keluarga berhasil ditambahkan', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal menambahkan anggota');
                }
            } catch (error) {
                console.error('Error submitting anggota:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // ============================================================================
        // FUNGSI DROPDOWN SEARCH
        // ============================================================================

        // Setup kepala keluarga dropdown
        function setupKepalaKeluargaDropdown() {
            const searchInput = document.getElementById('kepalaKeluargaSearch');
            const optionsContainer = document.getElementById('kepalaKeluargaOptions');

            // Event untuk pencarian real-time
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                clearTimeout(kepalaKeluargaSearchTimeout);

                if (query.length >= 2) {
                    showLoadingOptions('kepalaKeluargaOptions');
                    kepalaKeluargaSearchTimeout = setTimeout(() => {
                        searchJamaahOptions(query, 'kepalaKeluargaOptions', 'selectKepalaKeluarga');
                    }, 300);
                } else if (query.length === 0) {
                    optionsContainer.innerHTML = '';
                    optionsContainer.style.display = 'none';
                } else {
                    showMinCharsMessage('kepalaKeluargaOptions');
                }
            });

            // Event untuk focus
            searchInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    searchJamaahOptions(query, 'kepalaKeluargaOptions', 'selectKepalaKeluarga');
                }
            });

            // Prevent form submission on Enter in search
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstOption = optionsContainer.querySelector('.option-item');
                    if (firstOption) {
                        const id = firstOption.getAttribute('data-value');
                        const name = firstOption.getAttribute('data-name');
                        selectKepalaKeluarga(id, name);
                    }
                }
            });
        }

        // Setup anggota jamaah dropdown
        function setupAnggotaJamaahDropdown() {
            const searchInput = document.getElementById('anggotaJamaahSearch');
            const optionsContainer = document.getElementById('anggotaJamaahOptions');

            // Event untuk pencarian real-time
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                clearTimeout(anggotaJamaahSearchTimeout);

                if (query.length >= 2) {
                    showLoadingOptions('anggotaJamaahOptions');
                    anggotaJamaahSearchTimeout = setTimeout(() => {
                        searchJamaahFamOptions(query, 'anggotaJamaahOptions', 'selectAnggotaJamaah');
                    }, 300);
                } else if (query.length === 0) {
                    optionsContainer.innerHTML = '';
                    optionsContainer.style.display = 'none';
                } else {
                    showMinCharsMessage('anggotaJamaahOptions');
                }
            });

            // Event untuk focus
            searchInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    searchJamaahFamOptions(query, 'anggotaJamaahOptions', 'selectAnggotaJamaah');
                }
            });

            // Prevent form submission on Enter in search
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstOption = optionsContainer.querySelector('.option-item');
                    if (firstOption) {
                        const id = firstOption.getAttribute('data-value');
                        const name = firstOption.getAttribute('data-name');
                        selectAnggotaJamaah(id, name);
                    }
                }
            });
        }

        // Fungsi untuk search jamaah options (kepala keluarga)
        async function searchJamaahOptions(searchTerm = '', containerId, callback) {
            try {
                const response = await fetch(
                    `${API_ROUTES.jamaahOptions}?search=${encodeURIComponent(searchTerm)}`
                );
                const data = await response.json();

                const optionsContainer = document.getElementById(containerId);

                if (data.success && data.data.length > 0) {
                    optionsContainer.innerHTML = data.data.map(jamaah => `
                    <div class="option-item" 
                         tabindex="0" style="padding: 5px 0; border-bottom: 1px solid #eee;"
                         data-value="${jamaah.jamaah_id}"
                         data-name="${escapeHtml(jamaah.nama_lengkap)}"
                         onclick="${callback}('${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}')"
                         onkeydown="handleOptionKeydown(event, '${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}', '${callback}')">
                        ${jamaah.nama_lengkap}
                    </div>
                `).join('');
                } else {
                    optionsContainer.innerHTML = '<div class="no-options">Tidak ditemukan jamaah</div>';
                }

                optionsContainer.style.display = 'block';
            } catch (error) {
                console.error('Error searching jamaah options:', error);
                const optionsContainer = document.getElementById(containerId);
                optionsContainer.innerHTML = '<div class="no-options">Gagal memuat data</div>';
                optionsContainer.style.display = 'block';
            }
        }

        // Fungsi untuk search jamaah fam options (anggota keluarga)
        async function searchJamaahFamOptions(searchTerm = '', containerId, callback) {
            try {
                const response = await fetch(
                    `${API_ROUTES.jamaahFam}?search=${encodeURIComponent(searchTerm)}`
                );
                const data = await response.json();

                const optionsContainer = document.getElementById(containerId);

                if (data.success && data.data.length > 0) {
                    optionsContainer.innerHTML = data.data.map(jamaah => `
                    <div class="option-item" 
                         tabindex="0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;padding: 5px 0px;"
                         data-value="${jamaah.jamaah_id}"
                         data-name="${escapeHtml(jamaah.nama_lengkap)}"
                         onclick="${callback}('${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}')"
                         onkeydown="handleOptionKeydown(event, '${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}', '${callback}')">
                        ${jamaah.nama_lengkap}
                    </div>
                `).join('');
                } else {
                    optionsContainer.innerHTML = '<div class="no-options">Tidak ditemukan jamaah</div>';
                }

                optionsContainer.style.display = 'block';
            } catch (error) {
                console.error('Error searching jamaah fam options:', error);
                const optionsContainer = document.getElementById(containerId);
                optionsContainer.innerHTML = '<div class="no-options">Gagal memuat data</div>';
                optionsContainer.style.display = 'block';
            }
        }

        // Select kepala keluarga
        function selectKepalaKeluarga(id, name) {
            document.getElementById('kepalaKeluargaId').value = id;
            document.getElementById('kepalaKeluargaSearch').value = name;
            document.getElementById('kepalaKeluargaOptions').style.display = 'none';
        }

        // Select anggota jamaah
        function selectAnggotaJamaah(id, name) {
            document.getElementById('anggotaJamaahId').value = id;
            document.getElementById('anggotaJamaahSearch').value = name;
            document.getElementById('anggotaJamaahOptions').style.display = 'none';
        }

        // Handle keyboard navigation
        function handleOptionKeydown(event, id, name, callback) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                if (callback === 'selectKepalaKeluarga') {
                    selectKepalaKeluarga(id, name);
                } else if (callback === 'selectAnggotaJamaah') {
                    selectAnggotaJamaah(id, name);
                }
            } else if (event.key === 'ArrowDown') {
                event.preventDefault();
                const nextOption = event.target.nextElementSibling;
                if (nextOption && nextOption.classList.contains('option-item')) {
                    nextOption.focus();
                }
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                const prevOption = event.target.previousElementSibling;
                if (prevOption && prevOption.classList.contains('option-item')) {
                    prevOption.focus();
                } else {
                    event.target.closest('.dropdown-search-container').querySelector('input').focus();
                }
            } else if (event.key === 'Escape') {
                event.target.closest('.dropdown-options').style.display = 'none';
                event.target.closest('.dropdown-search-container').querySelector('input').focus();
            }
        }

        // Loading options
        function showLoadingOptions(containerId) {
            const optionsContainer = document.getElementById(containerId);
            optionsContainer.innerHTML =
                '<div class="loading-options"><i class="bi-spinner bi-spin"></i> Mencari...</div>';
            optionsContainer.style.display = 'block';
        }

        // Min chars message
        function showMinCharsMessage(containerId) {
            const optionsContainer = document.getElementById(containerId);
            optionsContainer.innerHTML = '<div class="no-options">Ketik minimal 2 karakter untuk mencari</div>';
            optionsContainer.style.display = 'block';
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
                    loadKeluargaData();
                }, 500);
            });

            // Per page
            document.getElementById('perPageSelect').addEventListener('change', function(e) {
                perPage = parseInt(e.target.value) || 10;
                currentPage = 1; // Reset ke halaman 1
                loadKeluargaData();
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

            document.getElementById('detailModal').addEventListener('click', function(e) {
                if (e.target === this) hideDetailModal();
            });

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) hideDeleteModal();
            });

            document.getElementById('anggotaModal').addEventListener('click', function(e) {
                if (e.target === this) hideAnggotaModal();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const kepalaOptions = document.getElementById('kepalaKeluargaOptions');
                const kepalaSearch = document.getElementById('kepalaKeluargaSearch');
                const anggotaOptions = document.getElementById('anggotaJamaahOptions');
                const anggotaSearch = document.getElementById('anggotaJamaahSearch');

                if (kepalaOptions && !kepalaOptions.contains(e.target) && e.target !== kepalaSearch) {
                    kepalaOptions.style.display = 'none';
                }

                if (anggotaOptions && !anggotaOptions.contains(e.target) && e.target !== anggotaSearch) {
                    anggotaOptions.style.display = 'none';
                }
            });
        }

        // Initialize aplikasi
        async function initializeApp() {
            setupEventListeners();
            setupKepalaKeluargaDropdown();
            setupAnggotaJamaahDropdown();
            await loadKeluargaData(1); // Mulai dari halaman 1
        }

        // ============================================================================
        // PUBLIC API (KeluargaApp)
        // ============================================================================
        const KeluargaApp = {
            // Data & Table
            printData() {
                window.open(API_ROUTES.print, '_blank');
            },
            reloadData() {
                loadKeluargaData(1);
            },
            goToPage: goToPage,

            // Modals
            showCreateModal: showCreateModal,
            hideFormModal: hideFormModal,
            showEditModal: showEditModal,
            showDetailModal: showDetailModal,
            hideDetailModal: hideDetailModal,
            showDeleteModal: showDeleteModal,
            hideDeleteModal: hideDeleteModal,
            showAnggotaModal: showAnggotaModal,
            hideAnggotaModal: hideAnggotaModal,

            // Forms
            submitForm: submitForm,
            confirmDelete: confirmDelete,
            submitAnggotaForm: submitAnggotaForm
        };

        // ============================================================================
        // START APP
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();

            // Expose ke global scope
            window.KeluargaApp = KeluargaApp;
            window.showEditModal = showEditModal;
            window.showDetailModal = showDetailModal;
            window.showDeleteModal = showDeleteModal;
            window.showAnggotaModal = showAnggotaModal;

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
