@extends('layouts.app')

@section('title', 'Data Keluarga')
@section('page-title', 'Data Keluarga')
@section('icon-page-title', 'bi-house-door')

@section('content')
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="master-container">
        <div class="card">
            <div class="card-header">
                <div style="display: flex;gap: 10px;justify-content: space-between;align-items: center;">
                    <h3 class="card-title">Data Keluarga</h3>
                    <div>
                        <button class="btn btn-print"
                            onclick="window.open('{{ route('admin.kelompok.data-keluarga.print') }}', '_blank')">
                            <i class="bi-printer"></i> Print
                        </button>
                        <button class="btn btn-primary" onclick="showCreateModal()">
                            <i class="bi-plus"></i> Tambah Keluarga
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-controls" style="display: flex; align-items: center; gap: 15px;">
                    <div>
                        <select id="perPageSelect" class="form-select"
                            style="width:auto;display:inline-block; padding:5px 0px; font-size:13px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari...">
                        <i class="bi-search search-icon"></i>
                    </div>
                </div>

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
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="bi-people" style="font-size: 3rem;"></i>
                    <h4>Tidak ada data keluarga</h4>
                    {{-- <p>Mulai dengan menambahkan data keluarga pertama Anda.</p> --}}
                    {{-- <button class="btn btn-primary" onclick="showCreateModal()">
                        <i class="bi-plus"></i> Tambah Keluarga
                    </button> --}}
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

    <!-- Create/Edit Modal -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Keluarga</h3>
                <button class="modal-close" onclick="hideModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="keluargaForm">
                    <input type="hidden" id="editId">
                    <input type="hidden" id="kepala_keluarga_id" name="kepala_keluarga_id">

                    <div class="form-group">
                        <label class="form-label" for="nama_keluarga">Nama Keluarga<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="nama_keluarga" name="nama_keluarga" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="kepala_keluarga_search">Kepala Keluarga<span
                                style="color:red">*</span></label>
                        <div class="searchable-dropdown">
                            <div class="dropdown-search-container">
                                <input type="text" class="form-control dropdown-search-input" id="kepala_keluarga_search"
                                    placeholder="Ketik untuk mencari jamaah..." autocomplete="off">
                                <i class="bi-search dropdown-search-icon"></i>
                            </div>
                            <div class="dropdown-options" id="kepala_keluarga_options" style="display: none;"></div>
                            <div id="selected_kepala_text" class="selected-option-display" style="display: none;"></div>
                        </div>
                        <div class="form-text">Ketik nama jamaah untuk mencari</div>
                    </div>

                    {{-- 
                    <div class="form-row" style="margin-top: 25px">
                        <div class="form-group">
                            <label class="form-label" for="telepon">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" maxlength="15">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="total_anggota">Total Anggota</label>
                            <input type="number" class="form-control" id="total_anggota" name="total_anggota"
                                min="1" value="1">
                        </div>
                    </div> --}}

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

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Keluarga</h3>
                <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus keluarga <strong id="deleteItemName"></strong>?</p>
                <p class="form-text">Data yang dihapus tidak dapat dikembalikan. Semua anggota keluarga juga akan terhapus.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="hideDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <!-- View Members Modal -->
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Anggota Keluarga</h3>
                <button class="modal-close" onclick="hideViewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="membersList">
                    <!-- Members will be loaded here -->
                </div>
                {{-- <hr> --}}
                <button class="btn btn-primary" type="button" onclick="showTambahAnggotaForm()">
                    <i class="bi-plus"></i> Tambah Anggota Keluarga
                </button>
                <div id="tambahAnggotaForm" style="display:none; margin-top:20px;">
                    <form id="formTambahAnggota" onsubmit="return submitTambahAnggota(event)">
                        <input type="hidden" id="anggota_jamaah_id">
                        <div class="form-group">
                            <label class="form-label" for="anggota_jamaah_search">Pilih Jamaah<span
                                    style="color:red">*</span></label>
                            <div class="searchable-dropdown">
                                <div class="dropdown-search-container">
                                    <input type="text" class="form-control dropdown-search-input"
                                        id="anggota_jamaah_search" placeholder="Ketik untuk mencari jamaah..."
                                        autocomplete="off">
                                    <i class="bi-search dropdown-search-icon"></i>
                                </div>
                                <div class="dropdown-options" id="anggota_jamaah_options" style="display: none;"></div>
                                <div id="selected_anggota_text" class="selected-option-display" style="display: none;">
                                </div>
                            </div>
                            <div class="form-text">Ketik nama jamaah untuk mencari (hanya yang belum menjadi anggota
                                keluarga)</div>
                        </div>
                        <div class="form-group">
                            <label for="status_hubungan">Status Hubungan</label>
                            <select class="form-select" id="status_hubungan" required>
                                <option value="ISTRI">Istri</option>
                                <option value="ANAK">Anak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="urutan">Urutan (opsional)</label>
                            <input type="number" class="form-control" id="urutan" min="1"
                                placeholder="Urutan">
                        </div>
                        <button class="btn btn-success" type="submit">Simpan Anggota</button>
                        <button class="btn" type="button" onclick="hideTambahAnggotaForm()">Batal</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="hideViewModal()">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        let deleteId = null;
        let kepalaKeluargaSearchTimeout = null;
        let isDropdownOpen = false;
        let anggotaKeluargaSearchTimeout = null;
        let anggotaDipilih = [];
        let perPage = 10;

        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupEventListeners();
            setupKepalaKeluargaDropdown();
            // setupAnggotaKeluargaDropdown(); // removed, not needed
            document.getElementById('perPageSelect').addEventListener('change', function() {
                perPage = parseInt(this.value);
                currentPage = 1;
                loadData();
            });
        });

        function setupEventListeners() {
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

        function setupKepalaKeluargaDropdown() {
            const searchInput = document.getElementById('kepala_keluarga_search');
            const optionsContainer = document.getElementById('kepala_keluarga_options');
            const formModal = document.getElementById('formModal');

            // Event untuk pencarian real-time
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                clearTimeout(kepalaKeluargaSearchTimeout);

                if (query.length >= 2) {
                    showLoadingOptions();
                    kepalaKeluargaSearchTimeout = setTimeout(() => {
                        searchJamaahOptions(query);
                    }, 300);
                } else if (query.length === 0) {
                    optionsContainer.innerHTML = '';
                    optionsContainer.style.display = 'none';
                    isDropdownOpen = false;
                } else {
                    showMinCharsMessage();
                }
            });

            // Event untuk focus
            searchInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    searchJamaahOptions(query);
                } else if (query.length === 0) {
                    optionsContainer.innerHTML = '<div class="no-options">Ketik untuk mencari jamaah</div>';
                    optionsContainer.style.display = 'block';
                    isDropdownOpen = true;
                }
            });

            // Event untuk keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' && isDropdownOpen) {
                    e.preventDefault();
                    const firstOption = optionsContainer.querySelector('.option-item');
                    if (firstOption) firstOption.focus();
                }

                if (e.key === 'Escape') {
                    optionsContainer.style.display = 'none';
                    isDropdownOpen = false;
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

        function showLoadingOptions() {
            const optionsContainer = document.getElementById('kepala_keluarga_options');
            optionsContainer.innerHTML = '<div class="loading-options"><i class="bi-spinner bi-spin"></i> Mencari...</div>';
            optionsContainer.style.display = 'block';
            isDropdownOpen = true;
        }

        function showMinCharsMessage() {
            const optionsContainer = document.getElementById('kepala_keluarga_options');
            optionsContainer.innerHTML = '<div class="no-options">Ketik minimal 2 karakter untuk mencari</div>';
            optionsContainer.style.display = 'block';
            isDropdownOpen = true;
        }

        async function searchJamaahOptions(searchTerm = '') {
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.keluarga.jamaah-options') }}?search=${encodeURIComponent(searchTerm)}`
                );
                const data = await response.json();

                const optionsContainer = document.getElementById('kepala_keluarga_options');

                if (data.success && data.data.length > 0) {
                    optionsContainer.innerHTML = data.data.map(jamaah => `
                    <div class="option-item" 
                         tabindex="0"
                         data-value="${jamaah.jamaah_id}"
                         data-name="${escapeHtml(jamaah.nama_lengkap)}"
                         onclick="selectKepalaKeluarga('${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}')"
                         onkeydown="handleOptionKeydown(event, '${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}')">
                        ${jamaah.nama_lengkap}
                    </div>
                `).join('');
                } else {
                    optionsContainer.innerHTML = '<div class="no-options">Tidak ditemukan jamaah</div>';
                }

                optionsContainer.style.display = 'block';
                isDropdownOpen = true;
            } catch (error) {
                console.error('Error searching jamaah options:', error);
                const optionsContainer = document.getElementById('kepala_keluarga_options');
                optionsContainer.innerHTML = '<div class="no-options">Gagal memuat data</div>';
                optionsContainer.style.display = 'block';
                isDropdownOpen = true;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function handleOptionKeydown(event, id, name) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                selectKepalaKeluarga(id, name);
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
                    document.getElementById('kepala_keluarga_search').focus();
                }
            } else if (event.key === 'Escape') {
                document.getElementById('kepala_keluarga_options').style.display = 'none';
                isDropdownOpen = false;
                document.getElementById('kepala_keluarga_search').focus();
            }
        }

        function selectKepalaKeluarga(id, name) {
            const hiddenInput = document.getElementById('kepala_keluarga_id');
            const searchInput = document.getElementById('kepala_keluarga_search');
            const displayText = document.getElementById('selected_kepala_text');
            const optionsContainer = document.getElementById('kepala_keluarga_options');

            // Set nilai
            hiddenInput.value = id;
            displayText.textContent = name;
            searchInput.value = name;

            // Sembunyikan dropdown dan reset
            optionsContainer.innerHTML = '';
            optionsContainer.style.display = 'none';
            isDropdownOpen = false;

            // Validasi
            validateKepalaKeluarga();
        }



        function validateKepalaKeluarga() {
            const hiddenInput = document.getElementById('kepala_keluarga_id');
            if (!hiddenInput.value) {
                hiddenInput.setCustomValidity('Pilih kepala keluarga terlebih dahulu');
            } else {
                hiddenInput.setCustomValidity('');
            }
        }


        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
        }

        async function loadData() {
            showLoading();

            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.keluarga.index') }}?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(searchQuery)}`
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
            <td>${escapeHtml(item.nama_keluarga)}</td>
            <td>${escapeHtml(item.kepala_keluarga_nama)}</td>
            <td>${item.alamat ? escapeHtml(item.alamat.substring(0, 50) + (item.alamat.length > 50 ? '...' : '')) : '-'}</td>
            <td>
                <button class="btn btn-info btn-sm" onclick="viewMembers('${item.keluarga_id}')" title="Lihat Anggota">
                    <i class="bi-eye"></i>
                </button>
                <button class="btn btn-edit btn-sm" onclick="editItem('${item.keluarga_id}')" title="Edit">
                    <i class="bi-pencil"></i>
                </button>
                <button class="btn btn-delete btn-sm" onclick="showDeleteModal('${item.keluarga_id}', '${escapeHtml(item.nama_keluarga)}')" title="Hapus">
                    <i class="bi-trash"></i>
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
            document.getElementById('modalTitle').textContent = 'Tambah Keluarga';
            document.getElementById('editId').value = '';
            document.getElementById('keluargaForm').reset();
            document.getElementById('formModal').classList.add('show');

            // Focus ke search input setelah modal muncul
            setTimeout(() => {
                document.getElementById('kepala_keluarga_search').focus();
            }, 300);
        }

        async function editItem(id) {
            try {
                const response = await fetch(`{{ route('admin.kelompok.api.keluarga.show', '') }}/${id}`);
                const data = await response.json();

                if (data.success) {
                    const item = data.data;
                    console.log(item);

                    document.getElementById('modalTitle').textContent = 'Edit Data Keluarga';
                    document.getElementById('editId').value = item.keluarga_id;
                    document.getElementById('nama_keluarga').value = item.nama_keluarga;
                    document.getElementById('alamat').value = item.alamat || '';
                    // document.getElementById('telepon').value = item.telepon || '';
                    // document.getElementById('total_anggota').value = item.total_anggota;
                    document.getElementById('kepala_keluarga_search').value = item.kepala_keluarga_nama;

                    // Set kepala keluarga
                    const hiddenInput = document.getElementById('kepala_keluarga_id');
                    const displayText = document.getElementById('selected_kepala_text');

                    hiddenInput.value = item.kepala_keluarga_id;

                    document.getElementById('formModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading item:', error);
                showError('Gagal memuat data: ' + error.message);
            }
        }

        async function viewMembers(id) {
            window.currentDetailKeluargaId = id;
            try {
                const response = await fetch(`{{ route('admin.kelompok.api.keluarga.show', '') }}/${id}`);
                const data = await response.json();

                if (data.success) {
                    const item = data.data;
                    const membersList = document.getElementById('membersList');

                    let membersHtml = `
                <div class="form-group">
                    <label class="form-label">Kepala Keluarga:</label>
                    <div class="member-item">
                        <div class="member-info">
                            <div class="member-name">${escapeHtml(item.kepala_keluarga_nama)}</div>
                            <div class="member-relation">KEPALA KELUARGA</div>
                        </div>
                    </div>
                </div>
            `;

                    if (item.anggota && item.anggota.length > 0) {
                        membersHtml += `
                    <div class="form-group">
                        <label class="form-label">Anggota Keluarga:</label>
                        <div class="members-list">
                            ${item.anggota.map(anggota => `
                                                                                                                                        <div class="member-item">
                                                                                                                                            <div class="member-info">
                                                                                                                                                <div class="member-name">${escapeHtml(anggota.nama_lengkap)}</div>
                                                                                                                                                <div class="member-relation">${anggota.status_hubungan}</div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    `).join('')}
                        </div>
                    </div>
                `;
                    } else {
                        membersHtml += `
                    <div class="empty-state">
                        <i class="bi-person" style="font-size: 2rem;"></i>
                        <p>Belum ada anggota keluarga lainnya</p>
                    </div>
                `;
                    }

                    membersList.innerHTML = membersHtml;
                    document.getElementById('viewModal').classList.add('show');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error loading members:', error);
                showError('Gagal memuat data anggota: ' + error.message);
            }
        }

        function hideModal() {
            document.getElementById('formModal').classList.remove('show');
        }

        function hideViewModal() {
            document.getElementById('viewModal').classList.remove('show');
        }

        async function submitForm() {
            // Validasi kepala keluarga
            validateKepalaKeluarga();

            const hiddenInput = document.getElementById('kepala_keluarga_id');
            if (!hiddenInput.value) {
                showError('Harap pilih kepala keluarga');
                document.getElementById('kepala_keluarga_search').focus();
                return;
            }

            const formData = new FormData(document.getElementById('keluargaForm'));
            const id = document.getElementById('editId').value;

            const data = {
                nama_keluarga: formData.get('nama_keluarga'),
                kepala_keluarga_id: hiddenInput.value,
                telepon: formData.get('telepon'),
                alamat: formData.get('alamat'),
                // total_anggota: parseInt(formData.get('total_anggota')) || 1
                anggota: anggotaDipilih.map(a => ({
                    jamaah_id: a.jamaah_id,
                    nama_lengkap: a.nama_lengkap,
                    status_hubungan: a.status_hubungan,
                    urutan: a.urutan
                }))
            };

            try {
                const url = id ? `{{ route('admin.kelompok.api.keluarga.update', '') }}/${id}` :
                    '{{ route('admin.kelompok.api.keluarga.store') }}';
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

        function showDeleteModal(id, name) {
            deleteId = id;
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        function hideDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').classList.remove('show');
        }

        async function confirmDelete() {
            try {
                const response = await fetch(`{{ route('admin.kelompok.api.keluarga.destroy', '') }}/${deleteId}`, {
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

        // Toast notification functions
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

        function closeToast(button) {
            const toast = button.closest('.toast');
            toast.classList.remove('show');
            toast.classList.add('hide');

            // Remove from DOM after animation
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }

        function showSuccess(message) {
            showToast('success', 'Berhasil', message);
        }

        function showError(message) {
            showToast('error', 'Error', message);
        }

        // Close modals when clicking outside
        document.getElementById('formModal').addEventListener('click', function(e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) hideDeleteModal();
        });

        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) hideViewModal();
        });

        // Tambah Anggota Keluarga Modal Logic
        async function showTambahAnggotaForm() {
            document.getElementById('tambahAnggotaForm').style.display = 'block';
            document.getElementById('anggota_jamaah_id').value = '';
            document.getElementById('status_hubungan').value = '';
            document.getElementById('urutan').value = '';

            // Load Jamaah options into dropdown
            await loadJamaahOptionsForAnggota();
        }

        async function loadJamaahOptionsForAnggota() {
            try {
                const response = await fetch('{{ route('admin.kelompok.api.keluarga.jamaah-fam') }}');
                const data = await response.json();

                const dropdown = document.getElementById('anggota_jamaah_id');
                dropdown.innerHTML = '<option value="">Pilih Jamaah</option>';

                if (data.success && data.data.length > 0) {
                    data.data.forEach(jamaah => {
                        const option = document.createElement('option');
                        option.value = jamaah.jamaah_id;
                        option.textContent =
                            `${jamaah.nama_lengkap} (${jamaah.jenis_kelamin === 'L' ? 'L' : 'P'})`;
                        dropdown.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading jamaah options:', error);
            }
        }

        function hideTambahAnggotaForm() {
            document.getElementById('tambahAnggotaForm').style.display = 'none';
        }

        // Searchable Jamaah for anggota
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('anggota_jamaah_search');
            const optionsContainer = document.getElementById('anggota_jamaah_options');
            let searchTimeout = null;
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.trim();
                    clearTimeout(searchTimeout);
                    if (query.length >= 2) {
                        optionsContainer.innerHTML =
                            '<div class="loading-options"><i class="bi-spinner bi-spin"></i> Mencari...</div>';
                        optionsContainer.style.display = 'block';
                        searchTimeout = setTimeout(() => {
                            fetch(
                                    `{{ route('admin.kelompok.api.keluarga.jamaah-fam') }}?search=${encodeURIComponent(query)}`
                                )
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success && data.data.length > 0) {
                                        optionsContainer.innerHTML = data.data.map(jamaah =>
                                            `<div class="option-item" tabindex="0" data-value="${jamaah.jamaah_id}" data-name="${escapeHtml(jamaah.nama_lengkap)}" onclick="selectJamaahAnggota('${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}')">${jamaah.nama_lengkap}</div>`
                                        ).join('');
                                    } else {
                                        optionsContainer.innerHTML =
                                            '<div class="no-options">Tidak ditemukan jamaah</div>';
                                    }
                                    optionsContainer.style.display = 'block';
                                });
                        }, 300);
                    } else {
                        optionsContainer.style.display = 'none';
                    }
                });
            }
        });

        function selectJamaahAnggota(id, name) {
            const hiddenInput = document.getElementById('anggota_jamaah_id');
            const searchInput = document.getElementById('anggota_jamaah_search');
            const displayText = document.getElementById('selected_anggota_text');
            const optionsContainer = document.getElementById('anggota_jamaah_options');

            // Set nilai
            hiddenInput.value = id;
            displayText.textContent = name;
            searchInput.value = name;

            // Sembunyikan dropdown dan reset
            optionsContainer.innerHTML = '';
            optionsContainer.style.display = 'none';
        }

        function submitTambahAnggota(e) {
            e.preventDefault();
            const keluargaId = window.currentDetailKeluargaId; // set this when opening detail modal
            const jamaahId = document.getElementById('anggota_jamaah_id').value;
            const status = document.getElementById('status_hubungan').value;
            const urutan = document.getElementById('urutan').value;
            if (!keluargaId || !jamaahId || !status) {
                showError('Lengkapi data anggota keluarga');
                return false;
            }
            fetch(`{{ route('admin.kelompok.api.anggota-keluarga.insert-anggota-keluarga') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        keluarga_id: keluargaId,
                        jamaah_id: jamaahId,
                        status_hubungan: status,
                        urutan: urutan
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Anggota keluarga berhasil ditambahkan');
                        hideTambahAnggotaForm();
                        // reload members list
                        viewMembers(keluargaId);
                    } else {
                        showError(data.message || 'Gagal menambah anggota');
                    }
                })
                .catch(() => showError('Gagal menambah anggota'));
            return false;
        }

        // Close dropdown when clicking anywhere on the page (except the dropdown itself)
        document.addEventListener('click', function(e) {
            const optionsContainer = document.getElementById('kepala_keluarga_options');
            const searchInput = document.getElementById('kepala_keluarga_search');
            const formModal = document.getElementById('formModal');

            if (!formModal.classList.contains('show')) return;

            if (!optionsContainer.contains(e.target) &&
                e.target !== searchInput &&
                !e.target.closest('.selected-option-display')) {
                optionsContainer.style.display = 'none';
                isDropdownOpen = false;
            }
        });
    </script>
@endpush
