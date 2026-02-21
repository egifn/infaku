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
                            <tbody id="tableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- States -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-people"></i>
                    <h4>Tidak ada data jamaah</h4>
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

    <!-- Edit Modal -->
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

    <!-- Detail Modal -->
    <div class="modal" id="detailModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Detail Jamaah</h3>
                <button class="modal-close" onclick="JamaahApp.hideDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                <!-- Data akan diisi oleh JavaScript -->
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
        let aktifFilter = '';
        let perPage = 10;
        let dapuanOptions = [];
        let isLoading = false;

        const API_ROUTES = {
            data: '{{ route('admin.kelompok.api.jamaah.index') }}',
            detail: '{{ route('admin.kelompok.api.jamaah.show', '') }}',
            create: '{{ route('admin.kelompok.api.jamaah.store') }}',
            update: '{{ route('admin.kelompok.api.jamaah.update', '') }}',
            dapuan: '{{ route('admin.kelompok.api.jamaah.dapuan-options') }}',
            print: '{{ route('admin.kelompok.data-jamaah.print') }}'
        };

        // ============================================================================
        // FUNGSI UTAMA - LOAD DATA & RENDER TABEL
        // ============================================================================

        // Fungsi untuk memuat data jamaah
        async function loadJamaahData(page = null) {
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

                if (aktifFilter !== '') {
                    params.append('is_aktif', aktifFilter);
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
                    // alert(error.message);
                    window.showToast(error.message, 'error');
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
                <td>${escapeHtml(item.nama_lengkap || '')}</td>
                <td>${item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                <td>
                    ${item.tempat_lahir || '-'}, 
                    ${item.tanggal_lahir ? formatDate(item.tanggal_lahir) : '-'}
                </td>
                <td>${item.telepon || '-'}</td>
                <td>${escapeHtml(item.pekerjaan || '-')}</td>
                <td><span class="badge badge-info">${formatStatus(item.status_menikah)}</span></td>
                <td>${item.nama_role || '-'}</td>
                <td>
                    ${item.is_aktif ? 
                        '<span class="badge badge-success">Aktif</span>' : 
                        '<span class="badge badge-danger">Tidak Aktif</span>'
                    }
                </td>
                <td>
                    <button class="btn btn-edit btn-sm" onclick="showEditModal('${item.id}')" title="Edit">
                        <i class="bi-pencil"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="showDetailModal('${item.id}')" title="Detail">
                        <i class="bi-eye"></i>
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
            loadJamaahData(page);
        }

        // ============================================================================
        // FUNGSI MODAL - CREATE, EDIT, DETAIL
        // ============================================================================

        // Modal Create
        function showCreateModal() {
            document.getElementById('createForm').reset();
            fillDapuanDropdown('createDapuanSelect');
            document.getElementById('createModal').classList.add('show');
        }

        function hideCreateModal() {
            document.getElementById('createModal').classList.remove('show');
        }

        // Modal Edit
        async function showEditModal(jamaahId) {
            try {
                const response = await fetch(`${API_ROUTES.detail}/${jamaahId}`);
                const result = await response.json();

                if (result.success) {
                    const jamaah = result.data;

                    // Isi form
                    document.getElementById('editJamaahId').value = jamaah.id;
                    document.getElementById('editNamaLengkap').value = jamaah.nama_lengkap;
                    document.getElementById('editTempatLahir').value = jamaah.tempat_lahir || '';

                    // Format tanggal lahir
                    if (jamaah.tanggal_lahir && jamaah.tanggal_lahir !== '0000-01-01') {
                        document.getElementById('editTanggalLahir').value = jamaah.tanggal_lahir.split('T')[0];
                    } else {
                        document.getElementById('editTanggalLahir').value = '';
                    }

                    document.getElementById('editJenisKelamin').value = jamaah.jenis_kelamin;
                    document.getElementById('editGolonganDarah').value = jamaah.golongan_darah || '-';
                    document.getElementById('editStatusMenikah').value = jamaah.status_menikah;
                    document.getElementById('editPekerjaan').value = jamaah.pekerjaan || '';
                    document.getElementById('editTelepon').value = jamaah.telepon || '';
                    document.getElementById('editEmail').value = jamaah.email || '';
                    document.getElementById('editAlamat').value = jamaah.alamat || '';
                    document.getElementById('editIsAktif').value = jamaah.is_aktif ? '1' : '0';

                    // Isi dropdown dapuan
                    fillDapuanDropdown('editDapuanSelect', jamaah.dapuan_id);

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

        // Modal Detail
        async function showDetailModal(jamaahId) {
            try {
                const response = await fetch(`${API_ROUTES.detail}/${jamaahId}`);
                const result = await response.json();

                if (result.success) {
                    const jamaah = result.data;
                    const detailHtml = `
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 12px;">
                    <div style="font-weight: 500;">Nama Lengkap</div>
                    <div>${escapeHtml(jamaah.nama_lengkap)}</div>
                    <div style="font-weight: 500;">Jenis Kelamin</div>
                    <div>${jamaah.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</div>
                    <div style="font-weight: 500;">Tempat, Tanggal Lahir</div>
                    <div>${jamaah.tempat_lahir || '-'}, ${jamaah.tanggal_lahir ? formatDate(jamaah.tanggal_lahir) : '-'}</div>
                    <div style="font-weight: 500;">Telepon</div>
                    <div>${jamaah.telepon || '-'}</div>
                    <div style="font-weight: 500;">Email</div>
                    <div>${jamaah.email || '-'}</div>
                    <div style="font-weight: 500;">Pekerjaan</div>
                    <div>${escapeHtml(jamaah.pekerjaan || '-')}</div>
                    <div style="font-weight: 500;">Status Menikah</div>
                    <div>${formatStatus(jamaah.status_menikah)}</div>
                    <div style="font-weight: 500;">Dapuan</div>
                    <div>${jamaah.nama_role || '-'}</div>
                    <div style="font-weight: 500;">Status Aktif</div>
                    <div>${jamaah.is_aktif ? 'Aktif' : 'Tidak Aktif'}</div>
                    <div style="font-weight: 500;">Alamat</div>
                    <div>${escapeHtml(jamaah.alamat || '-')}</div>
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

        // ============================================================================
        // FUNGSI FORM - SUBMIT CREATE & EDIT
        // ============================================================================

        // Submit Create Form
        async function submitCreateForm() {
            const form = document.getElementById('createForm');
            const formData = new FormData(form);

            const data = {
                nama_lengkap: formData.get('nama_lengkap'),
                tempat_lahir: formData.get('tempat_lahir'),
                tanggal_lahir: formData.get('tanggal_lahir'),
                jenis_kelamin: formData.get('jenis_kelamin'),
                golongan_darah: formData.get('golongan_darah'),
                status_menikah: formData.get('status_menikah'),
                pekerjaan: formData.get('pekerjaan'),
                telepon: formData.get('telepon'),
                email: formData.get('email'),
                alamat: formData.get('alamat'),
                dapuan_id: formData.get('dapuan_id'),
                is_aktif: formData.get('is_aktif') === '1'
            };

            // Validasi
            if (!data.nama_lengkap || !data.jenis_kelamin || !data.status_menikah || !data.dapuan_id) {
                if (window.showToast) {
                    window.showToast('Harap isi semua field yang wajib diisi', 'error');
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
                    loadJamaahData();
                    if (window.showToast) {
                        window.showToast('Data jamaah berhasil ditambahkan', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal menambahkan data');
                }
            } catch (error) {
                console.error('Error creating jamaah:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // Submit Edit Form
        async function submitEditForm() {
            const jamaahId = document.getElementById('editJamaahId').value;
            const form = document.getElementById('editForm');
            const formData = new FormData(form);

            const data = {
                nama_lengkap: formData.get('nama_lengkap'),
                tempat_lahir: formData.get('tempat_lahir'),
                tanggal_lahir: formData.get('tanggal_lahir'),
                jenis_kelamin: formData.get('jenis_kelamin'),
                golongan_darah: formData.get('golongan_darah'),
                status_menikah: formData.get('status_menikah'),
                pekerjaan: formData.get('pekerjaan'),
                telepon: formData.get('telepon'),
                email: formData.get('email'),
                alamat: formData.get('alamat'),
                dapuan_id: formData.get('dapuan_id'),
                is_aktif: formData.get('is_aktif') === '1'
            };

            // Validasi
            if (!data.nama_lengkap || !data.jenis_kelamin || !data.status_menikah || !data.dapuan_id) {
                if (window.showToast) {
                    window.showToast('Harap isi semua field yang wajib diisi', 'error');
                }
                return;
            }

            try {
                const response = await fetch(`${API_ROUTES.update}/${jamaahId}`, {
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
                    loadJamaahData(); // Reload data di halaman yang sama
                    if (window.showToast) {
                        window.showToast('Data jamaah berhasil diupdate', 'success');
                    }
                } else {
                    throw new Error(result.message || 'Gagal mengupdate data');
                }
            } catch (error) {
                console.error('Error updating jamaah:', error);
                if (window.showToast) {
                    window.showToast(error.message, 'error');
                }
            }
        }

        // ============================================================================
        // FUNGSI BANTU (HELPER FUNCTIONS)
        // ============================================================================

        // Format tanggal
        function formatDate(dateString) {
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
        }

        // Format status
        function formatStatus(status) {
            if (!status) return '-';
            return status.replace('_', ' ');
        }

        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load dapuan options
        async function loadDapuanOptions() {
            try {
                const response = await fetch(API_ROUTES.dapuan);
                const result = await response.json();
                if (result.success) {
                    dapuanOptions = result.data || [];
                }
            } catch (error) {
                console.error('Error loading dapuan options:', error);
                dapuanOptions = [];
            }
        }

        // Isi dropdown dapuan
        function fillDapuanDropdown(dropdownId, selectedValue = '') {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) return;

            let options = '<option value="">Pilih Dapuan</option>';
            dapuanOptions.forEach(dapuan => {
                const selected = String(dapuan.role_id) === String(selectedValue) ? 'selected' : '';
                options +=
                    `<option value="${dapuan.role_id}" ${selected}>${escapeHtml(dapuan.nama_role)}</option>`;
            });
            dropdown.innerHTML = options;
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
                    loadJamaahData();
                }, 500);
            });

            // Filter aktif
            document.getElementById('filterAktif').addEventListener('change', function(e) {
                aktifFilter = e.target.value;
                currentPage = 1; // Reset ke halaman 1
                loadJamaahData();
            });

            // Per page
            document.getElementById('perPageSelect').addEventListener('change', function(e) {
                perPage = parseInt(e.target.value) || 10;
                currentPage = 1; // Reset ke halaman 1
                loadJamaahData();
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

            document.getElementById('detailModal').addEventListener('click', function(e) {
                if (e.target === this) hideDetailModal();
            });
        }

        // Initialize aplikasi
        async function initializeApp() {
            await loadDapuanOptions();
            setupEventListeners();
            await loadJamaahData(1); // Mulai dari halaman 1
        }

        // ============================================================================
        // PUBLIC API (JamaahApp)
        // ============================================================================
        const JamaahApp = {
            printData() {
                window.open(API_ROUTES.print, '_blank');
            },
            reloadData() {
                loadJamaahData(1);
            },
            goToPage: goToPage,
            showCreateModal: showCreateModal,
            hideCreateModal: hideCreateModal,
            showEditModal: showEditModal,
            hideEditModal: hideEditModal,
            showDetailModal: showDetailModal,
            hideDetailModal: hideDetailModal,
            submitCreateForm: submitCreateForm,
            submitEditForm: submitEditForm
        };

        // ============================================================================
        // START APP
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();

            // Expose ke global scope
            window.JamaahApp = JamaahApp;
            window.showEditModal = showEditModal;
            window.showDetailModal = showDetailModal;

            // Debug
            window.debugPagination = function() {
                console.log('Pagination State:', {
                    currentPage,
                    totalPages,
                    totalRecords,
                    perPage,
                    searchQuery,
                    aktifFilter
                });
            };
        });
    </script>
@endpush
