@extends('layouts.app')

@section('title', 'Data Jamaah')
@section('page-title', 'Data Jamaah')
@section('icon-page-title', 'bi-people')


@section('content')
<div class="master-container">
    <div class="card">
        <div class="card-header">
            <div style="display: flex;gap: 10px;justify-content: space-between;align-items: center;">
                <h3 class="card-title">Data Jamaah</h3>
                <div>
                    <button class="btn btn-print"
                        onclick="window.open('{{ route('admin.kelompok.data-jamaah.print') }}', '_blank')">
                        <i class="bi-printer"></i> Print
                    </button>
                    <button class="btn btn-primary" onclick="showCreateModal()">
                        <i class="bi-plus"></i> Tambah Jamaah
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-controls">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select id="perPageSelect" class="form-select"
                        style="width:auto;display:inline-block; padding:5px 0px; font-size:13px;">
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
                            <!-- Data will be loaded via AJAX -->
                            {{-- <td>Tidak Ada Data</td> --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="bi-people" style="font-size: 3rem;"></i>
                <h4>Tidak ada data jamaah</h4>
            </div>

            <div id="loadingState" class="empty-state">
                <div style="height: 20px; width: 200px; margin: 0 auto 10px; background: #f0f0f0; border-radius: 4px;">
                </div>
                <div style="height: 20px; width: 150px; margin: 0 auto; background: #f0f0f0; border-radius: 4px;"></div>
            </div>

            <div class="pagination" id="pagination" style="display: none;">
                <button class="page-btn" id="prevPage" onclick="changePage(currentPage - 1)">
                    <i class="bi-chevron-left"></i> Prev
                </button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="page-btn" id="nextPage" onclick="changePage(currentPage + 1)">
                    Next <i class="bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal" id="formModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Tambah Jamaah</h3>
            <button class="modal-close" onclick="hideModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="jamaahForm">
                <input type="hidden" id="editId">

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap *</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin *</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="golongan_darah">Golongan Darah</label>
                        <select class="form-select" id="golongan_darah" name="golongan_darah">
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
                        <label class="form-label" for="status_menikah">Status Menikah *</label>
                        <select class="form-select" id="status_menikah" name="status_menikah" required>
                            <option value="">Pilih Status</option>
                            <option value="BELUM_MENIKAH">Belum Menikah</option>
                            <option value="MENIKAH">Menikah</option>
                            <option value="JANDA">Janda</option>
                            <option value="DUDA">Duda</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pekerjaan">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="telepon">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" maxlength="15">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dapuan_id">Dapuan *</label>
                        <select class="form-select" id="dapuan_id" name="dapuan_id" required>
                            <option value="">Pilih Dapuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="is_aktif">Status Aktif *</label>
                        <select class="form-select" id="is_aktif" name="is_aktif" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="hideModal()">Batal</button>
            <button class="btn btn-success" id="submitBtn" onclick="submitForm()">Simpan</button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title">Edit Jamaah</h3>
            <button class="modal-close" onclick="hideEditModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editJamaahForm">
                <input type="hidden" id="editIdEdit">

                <div class="form-group">
                    <label class="form-label" for="nama_lengkap_edit">Nama Lengkap *</label>
                    <input type="text" class="form-control" id="nama_lengkap_edit" name="nama_lengkap" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="tempat_lahir_edit">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir_edit" name="tempat_lahir">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tanggal_lahir_edit">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir_edit" name="tanggal_lahir">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="jenis_kelamin_edit">Jenis Kelamin *</label>
                        <select class="form-select" id="jenis_kelamin_edit" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="golongan_darah_edit">Golongan Darah</label>
                        <select class="form-select" id="golongan_darah_edit" name="golongan_darah">
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
                        <label class="form-label" for="status_menikah_edit">Status Menikah *</label>
                        <select class="form-select" id="status_menikah_edit" name="status_menikah" required>
                            <option value="">Pilih Status</option>
                            <option value="BELUM_MENIKAH">Belum Menikah</option>
                            <option value="MENIKAH">Menikah</option>
                            <option value="JANDA">Janda</option>
                            <option value="DUDA">Duda</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pekerjaan_edit">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan_edit" name="pekerjaan">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="telepon_edit">Telepon</label>
                        <input type="text" class="form-control" id="telepon_edit" name="telepon" maxlength="15">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email_edit">Email</label>
                        <input type="email" class="form-control" id="email_edit" name="email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dapuan_id_edit">Dapuan *</label>
                        <select class="form-select" id="dapuan_id_edit" name="dapuan_id" required>
                            <option value="">Pilih Dapuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="is_aktif_edit">Status Aktif *</label>
                        <select class="form-select" id="is_aktif_edit" name="is_aktif" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat_edit">Alamat</label>
                    <textarea class="form-control" id="alamat_edit" name="alamat" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="hideEditModal()">Batal</button>
            <button class="btn btn-success" id="submitEditBtn" onclick="submitEditForm()">Simpan</button>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal" id="detailModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title">Detail Jamaah</h3>
            <button class="modal-close" onclick="hideDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="detailBody">
            <!-- Data will be loaded via JS -->
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        let aktifFilter = '';
        let perPage = 10;
        // let deleteId = null;
        let dapuanOptions = [];

        document.addEventListener('DOMContentLoaded', function () {
            loadDapuanOptions();
            loadData();
            setupEventListeners();
        });

        function setupEventListeners() {
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value;
                    currentPage = 1;
                    loadData();
                }, 500);
            });

            document.getElementById('filterAktif').addEventListener('change', function (e) {
                aktifFilter = e.target.value;
                currentPage = 1;
                loadData();
            });

            document.getElementById('perPageSelect').addEventListener('change', function (e) {
                perPage = parseInt(e.target.value);
                currentPage = 1;
                loadData();
            });
        }

        async function loadDapuanOptions() {
            try {
                const response = await fetch(
                    '{{ route('admin.kelompok.api.jamaah.dapuan-options') }}');
                const data = await response.json();

                if (data.success) {
                    dapuanOptions = data.data;
                    updateDapuanDropdown();
                }
            } catch (error) {
                console.error('Error loading dapuan options:', error);
            }
        }

        function updateDapuanDropdown(selectedId = '') {
            const dropdown = document.getElementById('dapuan_id');
            const editDropdown = document.getElementById('dapuan_id_edit');
            const dropdowns = [dropdown, editDropdown];

            dropdowns.forEach(d => {
                if (d) {
                    d.innerHTML = '<option value="">Pilih Dapuan</option>';

                    dapuanOptions.forEach(dapuan => {
                        const option = document.createElement('option');
                        option.value = String(dapuan.dapuan_id);
                        option.textContent = `${dapuan.nama_dapuan} `;
                        d.appendChild(option);
                    });

                    // Set selected value setelah options dimuat
                    if (selectedId !== undefined && selectedId !== null && selectedId !== '') {
                        d.value = String(selectedId);
                    } else {
                        d.value = '';
                    }
                }
            });
        }

        async function loadData() {
            showLoading();
            try {
                let url =
                    `{{ route('admin.kelompok.api.jamaah.index') }}?page=${currentPage}&search=${encodeURIComponent(searchQuery)}&per_page=${perPage}`;
                if (aktifFilter !== '') {
                    url += `&is_aktif=${aktifFilter}`;
                }
                const response = await fetch(url);
                const data = await response.json();
                if (data.success) {
                    renderTable(data.data);
                    updatePagination(data);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showError(error.message);
            }
        }

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
                <td>${index + 1 + ((currentPage - 1) * perPage)}</td>
                <td>${item.nama_lengkap}</td>
                <td>${item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                <td>
                    ${item.tempat_lahir || '-'},
                   ${item.tanggal_lahir ? new Date(item.tanggal_lahir).toLocaleDateString('id-ID') : '-'}
                </td>
                <td>${item.telepon || '-'}</td>
                <td>${item.pekerjaan || '-'}</td>
                <td>
                    <span class="badge badge-info">${item.status_menikah.replace('_', ' ')}</span>
                </td>
                <td>${item.nama_dapuan || '-'}</td>
                <td>
                    ${item.is_aktif ? 
                        '<span class="badge badge-success">Aktif</span>' : 
                        '<span class="badge badge-danger">Tidak Aktif</span>'
                    }
                </td>
                <td>
                    <button class="btn btn-edit btn-sm" onclick="editItem('${item.jamaah_id}')" title="Edit">
                        <i class="bi-pencil"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="showDetailModal('${item.jamaah_id}')" title="Detail">
                        <i class="bi-eye"></i>
                    </button>
                </td>
            </tr>
        `).join('');
        }

        function updatePagination(data) {
            currentPage = data.current_page;
            totalPages = data.last_page;

            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadData();
            }
        }

        function showCreateModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Jamaah';
            document.getElementById('editId').value = '';
            document.getElementById('jamaahForm').reset();
            updateDapuanDropdown();
            document.getElementById('formModal').classList.add('show');
        }

        async function editItem(id) {
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.jamaah.show', '') }}/${id}`
                );
                const data = await response.json();

                if (data.success) {
                    const item = data.data;
                    document.getElementById('editIdEdit').value = item.jamaah_id;

                    // Fill form fields for edit modal
                    document.getElementById('nama_lengkap_edit').value = item.nama_lengkap;
                    document.getElementById('tempat_lahir_edit').value = item.tempat_lahir || '';

                    // Tanggal lahir: format ke yyyy-MM-dd jika valid, jika null/kosong, kosongkan
                    if (item.tanggal_lahir && item.tanggal_lahir !== '0000-01-01') {
                        let tgl = item.tanggal_lahir;
                        if (/^\d{4}-\d{2}-\d{2}$/.test(tgl)) {
                            document.getElementById('tanggal_lahir_edit').value = tgl;
                        } else {
                            const d = new Date(tgl);
                            if (!isNaN(d.getTime())) {
                                const yyyy = d.getFullYear();
                                const mm = String(d.getMonth() + 1).padStart(2, '0');
                                const dd = String(d.getDate()).padStart(2, '0');
                                document.getElementById('tanggal_lahir_edit').value = `${yyyy}-${mm}-${dd}`;
                            } else {
                                document.getElementById('tanggal_lahir_edit').value = '';
                            }
                        }
                    } else {
                        document.getElementById('tanggal_lahir_edit').value = '';
                    }

                    document.getElementById('jenis_kelamin_edit').value = item.jenis_kelamin;
                    document.getElementById('golongan_darah_edit').value = item.golongan_darah || '-';

                    // Status menikah: normalisasi ke uppercase agar cocok dengan value option
                    let statusMenikah = item.status_menikah;
                    if (statusMenikah) {
                        statusMenikah = statusMenikah.toUpperCase().replace(/\s/g, '_');
                    }
                    document.getElementById('status_menikah_edit').value = statusMenikah || '';

                    document.getElementById('pekerjaan_edit').value = item.pekerjaan || '';
                    document.getElementById('telepon_edit').value = item.telepon || '';
                    document.getElementById('email_edit').value = item.email || '';
                    document.getElementById('alamat_edit').value = item.alamat || '';
                    document.getElementById('is_aktif_edit').value = item.is_aktif ? '1' : '0';

                    // Dapuan: pastikan value string/number cocok, dan tunggu options sudah dimuat
                    let dapuanValue = item.dapuan_id !== undefined && item.dapuan_id !== null ? String(item
                        .dapuan_id) : '';
                    const setDapuanEditValue = () => {
                        const editDropdown = document.getElementById('dapuan_id_edit');
                        if (editDropdown) {
                            editDropdown.value = dapuanValue;
                        }
                    };
                    if (dapuanOptions.length === 0) {
                        await loadDapuanOptions();
                        updateDapuanDropdown(dapuanValue);
                        setTimeout(setDapuanEditValue, 100); // fallback jika async
                    } else {
                        updateDapuanDropdown(dapuanValue);
                        setDapuanEditValue();
                    }

                    document.getElementById('editModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading item:', error);
                showError(error.message);
            }
        }

        function hideModal() {
            document.getElementById('formModal').classList.remove('show');
        }

        function hideEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        async function submitForm() {
            const formData = new FormData(document.getElementById('jamaahForm'));
            const id = document.getElementById('editId').value;

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

            try {
                const url = id ?
                    `{{ route('admin.kelompok.api.jamaah.update', '') }}/${id}` :
                    '{{ route('admin.kelompok.api.jamaah.store') }}';
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
                    showToast('success', 'Berhasil', id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showError('Gagal menyimpan data: ' + error.message);
            }
        }

        async function submitEditForm() {
            const formData = new FormData(document.getElementById('editJamaahForm'));
            const id = document.getElementById('editIdEdit').value;

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

            try {
                const url =
                    `{{ route('admin.kelompok.api.jamaah.update', '') }}/${id}`;
                const method = 'PUT';

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
                    hideEditModal();
                    loadData();
                    showToast('success', 'Berhasil', 'Data berhasil diupdate');
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error submitting edit form:', error);
                showError('Gagal menyimpan data: ' + error.message);
            }
        }


        async function showDetailModal(id) {
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.jamaah.show', '') }}/${id}`
                );
                const data = await response.json();
                if (data.success) {
                    const item = data.data;
                    let html = `<div style=\"display:grid;grid-template-columns:150px 1fr;gap:12px;\">`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Nama Lengkap</div><div>${item.nama_lengkap}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Jenis Kelamin</div><div>${item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Tempat, Tanggal Lahir</div><div>${item.tempat_lahir || '-'}, ${item.tanggal_lahir ? new Date(item.tanggal_lahir).toLocaleDateString('id-ID') : '-'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Telepon</div><div>${item.telepon || '-'}</div>`;
                    html += `<div style=\"font-weight:500;color:#333;\">Email</div><div>${item.email || '-'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Pekerjaan</div><div>${item.pekerjaan || '-'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Status Menikah</div><div>${item.status_menikah.replace('_', ' ')}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Dapuan</div><div>${item.nama_dapuan || '-'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Status Aktif</div><div>${item.is_aktif ? 'Aktif' : 'Tidak Aktif'}</div>`;
                    html +=
                        `<div style=\"font-weight:500;color:#333;\">Alamat</div><div>${item.alamat || '-'}</div>`;
                    html += `</div>`;
                    document.getElementById('detailBody').innerHTML = html;
                    document.getElementById('detailModal').classList.add('show');
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError(error.message);
            }
        }

        function hideDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
        }

        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const icons = {
                success: 'bi-check-circle',
                error: 'bi-exclamation-triangle',
                warning: 'bi-exclamation-circle',
                info: 'bi-info-circle'
            };

            toast.className = `toast ${type}`;

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[type] || 'bi-info-circle'}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="bi-x"></i>
                </button>
            `;

            container.appendChild(toast);

            const existingToasts = container.children.length - 1;
            const delay = existingToasts * 300; // 300ms delay between each toast

            setTimeout(() => {
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 250);
                }, 3000); // Auto hide after 3 seconds
            }, delay);
        }

        function getToastIcon(type) {
            switch (type) {
                case 'success':
                    return 'bi-check-circle-fill';
                case 'error':
                    return 'bi-exclamation-triangle-fill';
                case 'warning':
                    return 'bi-exclamation-triangle-fill';
                case 'info':
                    return 'bi-info-circle-fill';
                default:
                    return 'bi-info-circle-fill';
            }
        }

        function showSuccess(message) {
            showToast('success', 'Berhasil', message);
        }

        function showError(message) {
            showToast('error', 'Error', message);
        }

        document.getElementById('formModal').addEventListener('click', function (e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('detailModal').addEventListener('click', function (e) {
            if (e.target === this) hideDetailModal();
        });

    </script>
@endpush
