@extends('layouts.app')

@section('title', 'Input Pembayaran')
@section('page-title', 'Input Pembayaran')
@section('icon-page-title', 'bi-cash-coin')

@push('styles')
    <style>
        .input-container {
            padding: 5px;
        }

        /* Master Card Styles untuk halaman pilih kontribusi */
        .master-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .master-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #105a44 0%, #0d8b66 100%);
            position: relative;
            overflow: hidden;
        }

        .master-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .master-card-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }

        .master-card-body {
            padding: 20px;
            background: white;
        }

        /* Grid untuk kontribusi cards */
        .kontribusi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        /* Kontribusi Card Styles */
        .kontribusi-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
            height: 100%;
        }

        .kontribusi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #105a44;
        }

        .kontribusi-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .kontribusi-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: linear-gradient(135deg, #105a44 0%, #0d8b66 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .kontribusi-card-title-wrapper {
            flex: 1;
        }

        .kontribusi-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0 0 2px 0;
            line-height: 1.3;
        }

        .kontribusi-card-code {
            font-size: 12px;
            color: #666;
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
            font-family: 'Courier New', monospace;
        }

        .kontribusi-card-body {
            padding: 15px 20px;
        }

        .kontribusi-card-details {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kontribusi-card-details li {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .kontribusi-card-details li:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 500;
            text-align: right;
        }

        .kontribusi-card-footer {
            padding: 12px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
        }

        .select-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #105a44;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .select-btn:hover {
            background: #0d8b66;
            transform: translateY(-1px);
        }

        /* Styles untuk halaman form input pembayaran */


        .card-body-pos {
            padding: 15px 20px;
        }

        .form-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-section {
            padding-bottom: 10px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #105a44;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }

        .form-label.required::after {
            content: ' *';
            color: #dc3545;
        }

        .jamaah-info {
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 4px;
            border-left: 4px solid #105a44;
            margin-top: 5px;
        }

        .jamaah-name {
            font-weight: 600;
            color: #105a44;
            margin-bottom: 2px;
        }

        .jamaah-details {
            font-size: 12px;
            color: #666;
        }

        /* Sub Kontribusi Table */
        .sub-kontribusi-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            /* border-radius: 8px; */
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .sub-kontribusi-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
            font-size: 12px;
        }

        .sub-kontribusi-table td {
            padding: 5px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }

        .sub-kontribusi-table tbody tr:hover {
            background: #f8f9fa;
        }

        .sub-kontribusi-table .input-column {
            width: 200px;
        }

        .sub-kontribusi-table input[type="number"] {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            text-align: right;
        }

        .sub-kontribusi-table input[type="number"]:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .sub-kontribusi-table .rupiah-input {
            position: relative;
        }

        .sub-kontribusi-table .rupiah-input input {
            padding-right: 5px;
            padding-left: 10px;
            text-align: right;
        }

        .sub-kontribusi-table .rupiah-symbol {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 12px;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
            padding: 15px 0px;
            border-top: 2px solid #e0e0e0;
            border-bottom: 2px solid #e0e0e0;
        }

        .total-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-right: 15px;
        }

        .total-amount {
            font-weight: 700;
            color: #105a44;
            font-size: 16px;
            min-width: 150px;
            text-align: right;
            margin-right: 25px;
        }

        /* File Upload */
        .file-upload {
            position: relative;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .file-label:hover {
            background: #e9ecef;
            border-color: #105a44;
        }

        .file-name {
            font-size: 12px;
            color: #666;
            margin-left: 8px;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-container {
            margin-top: 10px;
        }

        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: block;
        }

        /* Loading State */
        .loading-container {
            text-align: center;
            padding: 40px 20px;
            grid-column: 1 / -1;
        }

        .loading-spinner {
            width: 2rem;
            height: 2rem;
            border: 0.2em solid #f1f5f9;
            border-top: 0.2em solid #105a44;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        <blade keyframes|%20spin%20%7B%0D>0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
        }

        .loading-text {
            color: #666;
            font-size: 13px;
            margin: 0;
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 2rem;
            color: #ddd;
            margin-bottom: 10px;
        }

        .empty-title {
            color: #666;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .empty-subtitle {
            color: #999;
            font-size: 12px;
            margin: 0;
        }

        /* Searchable Dropdown */
        .searchable-dropdown {
            position: relative;
        }

        .dropdown-search {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .dropdown-search:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .dropdown-search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 250px;
            overflow-y: auto;
            display: none;
        }

        .dropdown-options.show {
            display: block;
        }

        .option-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            color: #4d4d4d;
        }

        .option-item:hover {
            background: #f8f9fa;
        }

        .option-item:last-child {
            border-bottom: none;
        }

        .loading-options {
            padding: 12px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .no-options {
            padding: 12px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        .selected-kontribusi-badge {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kontribusi-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .kontribusi-icon-small {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: linear-gradient(135deg, #105a44 0%, #0d8b66 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 350px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            border-left: 4px solid #105a44;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.success {
            border-left-color: #28a745;
        }

        .toast.error {
            border-left-color: #dc3545;
        }

        .toast.warning {
            border-left-color: #ffc107;
        }

        .toast.info {
            border-left-color: #17a2b8;
        }

        .toast-icon {
            font-size: 1.2rem;
        }

        .toast.success .toast-icon {
            color: #28a745;
        }

        .toast.error .toast-icon {
            color: #dc3545;
        }

        .toast.warning .toast-icon {
            color: #ffc107;
        }

        .toast.info .toast-icon {
            color: #17a2b8;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .toast-message {
            font-size: 13px;
            color: #666;
        }

        .toast-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .toast-close:hover {
            color: #333;
        }

        /* Animation untuk card */
        @@keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .kontribusi-card {
            animation: fadeInUp 0.3s ease forwards;
            opacity: 0;
        }

        .kontribusi-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .kontribusi-card:nth-child(2) {
            animation-delay: 0.15s;
        }

        .kontribusi-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .kontribusi-card:nth-child(4) {
            animation-delay: 0.25s;
        }

        /* Utility Classes */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #28a745 !important;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: -0.125em;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }
    </style>
@endpush

@section('content')
    <div class="input-container">
        @if (request()->has('master_kontribusi_id'))
            <!-- Tampilkan form input pembayaran jika sudah memilih kontribusi -->
            @if (isset($selectedKontribusi))
                <div class="selected-kontribusi-badge">
                    <div class="kontribusi-info">
                        <div class="kontribusi-icon-small">
                            <i class="bi-cash-coin"></i>
                        </div>
                        <div>
                            <strong style="font-size: 13px;">{{ $selectedKontribusi->nama_kontribusi }}</strong><br>
                            <span style="font-size: 12px; color: #666;">Kode:
                                {{ $selectedKontribusi->kode_kontribusi }}</span>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="changeKontribusi()">
                        <i class="bi-arrow-repeat"></i> Ganti Kontribusi
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Pembayaran Kontribusi</h3>
                    <button class="back-btn" onclick="goBackToSelection()">
                        <i class="bi-arrow-left"></i> Kembali ke Pilihan Kontribusi
                    </button>
                </div>
                <div class="card-body-pos">
                    <div class="form-container">
                        <form id="pembayaranForm" method="POST"
                            action="{{ route('admin.kelompok.input-pembayaran.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Section 1: Data Jamaah -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi-people"></i> Data Jamaah
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Pilih Jamaah</label>
                                    <div class="searchable-dropdown">
                                        <input type="text" class="dropdown-search" id="jamaah_search"
                                            placeholder="Cari nama atau NIK jamaah..." autocomplete="off">
                                        <i class="bi-search dropdown-search-icon"></i>
                                        <div class="dropdown-options" id="jamaah_options"></div>
                                    </div>
                                    <input type="hidden" id="jamaah_id" name="jamaah_id">
                                    <div class="form-text">Ketik nama atau NIK untuk mencari jamaah</div>
                                </div>

                                <div id="jamaahInfo" class="jamaah-info" style="display: none;">
                                    <div class="jamaah-name" id="jamaahNama"></div>
                                    <div class="jamaah-details">
                                        <span id="jamaahNik"></span> |
                                        <span id="jamaahTelepon"></span> |
                                        <span id="jamaahAlamat"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Data Pembayaran -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi-cash"></i> Data Pembayaran
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label required" for="tgl_transaksi">Tanggal Pembayaran</label>
                                        <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label required" for="metode_bayar">Metode Pembayaran</label>
                                        <select class="form-select" id="metode_bayar" name="metode_bayar" required>
                                            <option value="">Pilih Metode</option>
                                            <option value="TUNAI" selected>TUNAI</option>
                                            <option value="TRANSFER">TRANSFER</option>
                                            <option value="QRIS">QRIS</option>
                                            <option value="LAINNYA">LAINNYA</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" id="master_kontribusi_id" name="master_kontribusi_id"
                                    value="{{ request('master_kontribusi_id') }}">

                                <!-- Sub Kontribusi Table -->
                                <div id="subKontribusiContainer" style="display: none;">
                                    <div class="section-title" style="margin-top: 20px;">
                                        <i class="bi-list-check"></i> Detail Kontribusi
                                    </div>

                                    <table class="sub-kontribusi-table" id="subKontribusiTable">
                                        <thead>
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Nama Kontribusi</th>
                                                <th width="100">Jenis</th>
                                                <th width="150">Value</th>
                                                <th width="200" class="input-column">Input Pembayaran</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subKontribusiBody">
                                            <!-- Data akan diisi oleh JavaScript -->
                                        </tbody>
                                    </table>

                                    <div class="total-section">
                                        <div class="total-label">TOTAL PEMBAYARAN:</div>
                                        <div class="total-amount" id="totalPembayaran">Rp 0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Bukti & Keterangan -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi-receipt"></i> Bukti & Keterangan
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <input class="form-control" id="keterangan" name="keterangan"
                                            style="height: 38px"
                                            placeholder="Catatan tambahan tentang pembayaran ini..."></input>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="bukti_bayar">Bukti Pembayaran</label>
                                        <div class="file-upload">
                                            <label class="file-label" for="bukti_bayar">
                                                <i class="bi-upload"></i>
                                                <span id="fileText">Unggah Bukti Pembayaran</span>
                                                <div class="file-name" id="fileName"></div>
                                            </label>
                                            <input type="file" class="file-input" id="bukti_bayar" name="bukti_bayar"
                                                accept="image/*" onchange="previewFile()">
                                        </div>
                                        <div class="form-text">Opsional, maks. 2MB (JPG, PNG, GIF)</div>
                                    </div>
                                </div>

                                <div class="preview-container" id="previewContainer" style="display: none;">
                                    <img id="previewImage" class="preview-image" src="" alt="Preview Bukti">
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImage()">
                                        <i class="bi-trash"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="bi-arrow-clockwise"></i> Reset Form
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="bi-check-circle"></i> Simpan Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Tampilkan pilihan kontribusi jika belum memilih -->
            <div class="master-card">
                <div class="master-card-header">
                    <h3 class="master-card-title">
                        <i class="bi bi-grid-3x3-gap"></i>
                        Pilih Master Kontribusi
                    </h3>
                    <div class="master-card-subtitle">
                        Pilih salah satu jenis kontribusi untuk melanjutkan
                    </div>
                </div>
                <div class="master-card-body">
                    <div class="kontribusi-grid" id="kontribusi-list">
                        <div class="loading-container" id="loadingKontribusi">
                            <div class="loading-spinner"></div>
                            <p class="loading-text">Memuat data kontribusi...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        // Pastikan fungsi navigasi selalu terdefinisi


        // Fungsi untuk halaman pilih kontribusi
        function fetchKontribusi() {
            fetch("{{ route('admin.kelompok.api.input-pembayaran.kontribusi-options') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const list = document.getElementById('kontribusi-list');
                    const loading = document.getElementById('loadingKontribusi');

                    if (!data.success || !data.data || data.data.length === 0) {
                        loading.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h4 class="empty-title">Tidak ada data kontribusi</h4>
                                <p class="empty-subtitle">Belum ada master kontribusi yang tersedia</p>
                            </div>
                        `;
                        return;
                    }

                    loading.style.display = 'none';
                    list.innerHTML = '';

                    data.data.forEach((item, index) => {
                        const card = document.createElement('div');
                        card.className = 'kontribusi-card';
                        card.style.animationDelay = `${(index + 1) * 0.05}s`;

                        let iconClass = 'bi-cash-coin';
                        let detailItems = [];

                        if (item.deskripsi) {
                            detailItems.push({
                                label: 'Deskripsi',
                                value: item.deskripsi.length > 40 ?
                                    item.deskripsi.substring(0, 40) + '...' : item.deskripsi
                            });
                        }

                        if (item.nominal_default) {
                            const nominal = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(item.nominal_default);
                            detailItems.push({
                                label: 'Nominal Default',
                                value: nominal
                            });
                        }

                        card.innerHTML = `
                            <div class="kontribusi-card-header">
                                <div class="kontribusi-card-icon">
                                    <i class="bi ${iconClass}"></i>
                                </div>
                                <div class="kontribusi-card-title-wrapper">
                                    <h5 class="kontribusi-card-title">${escapeHtml(item.nama_kontribusi)}</h5>
                                    <div class="kontribusi-card-code">Kode: ${escapeHtml(item.kode_kontribusi)}</div>
                                </div>
                            </div>
                            ${detailItems.length > 0 ? `
                                                                                                                                                            <div class="kontribusi-card-body">
                                                                                                                                                                <ul class="kontribusi-card-details">
                                                                                                                                                                    ${detailItems.map(detail => `
                                    <li>
                                        <span class="detail-label">${detail.label}</span>
                                        <span class="detail-value">${detail.value}</span>
                                    </li>
                                `).join('')}
                                                                                                                                                                </ul>
                                                                                                                                                            </div>
                                                                                                                                                        ` : ''}
                            <div class="kontribusi-card-footer">
                                <button class="select-btn" onclick="goToInputPembayaran('${item.master_kontribusi_id}')">
                                    <i class="bi bi-arrow-right"></i>
                                    Pilih
                                </button>
                            </div>
                        `;

                        card.addEventListener('click', function(e) {
                            if (!e.target.closest('.select-btn')) {
                                goToInputPembayaran(item.master_kontribusi_id);
                            }
                        });

                        list.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Error fetching kontribusi:', error);
                    const list = document.getElementById('kontribusi-list');
                    const loading = document.getElementById('loadingKontribusi');

                    loading.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h4 class="empty-title">Gagal memuat data</h4>
                            <p class="empty-subtitle">Terjadi kesalahan saat memuat data kontribusi</p>
                        </div>
                    `;
                });
        }

        function goToInputPembayaran(masterKontribusiId) {
            let button = null;
            if (window.event && window.event.target) {
                button = window.event.target.closest('.select-btn');
            }
            if (button) {
                const originalContent = button.innerHTML;
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Memproses...
                `;
                button.disabled = true;
            }

            setTimeout(() => {
                window.location.href =
                    `{{ route('admin.kelompok.input-pembayaran.create') }}?master_kontribusi_id=${masterKontribusiId}`;
            }, 200);
        }

        function goBackToSelection() {
            window.location.href = "{{ route('admin.kelompok.input-pembayaran.index') }}";
        }

        function changeKontribusi() {
            goBackToSelection();
        }

        // Fungsi untuk halaman form input pembayaran
        let currentSubKontribusi = [];
        let isSubmitting = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah sedang di halaman input pembayaran atau halaman pilih kontribusi
            const masterKontribusiId = "{{ request('master_kontribusi_id') }}";
            if (masterKontribusiId) {
                // Halaman input pembayaran
                loadSubKontribusi(masterKontribusiId);
                setupEventListeners();
                setupDropdowns();
            } else {
                // Halaman pilih kontribusi
                fetchKontribusi();
            }
        });

        function setupEventListeners() {
            // Recalculate total when input changes
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('sub-kontribusi-input')) {
                    calculateTotal();
                    validateForm();
                }
            });

            // Form submit event
            const form = document.getElementById('pembayaranForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validasi input sebelum submit
                    let valid = true;
                    document.querySelectorAll('.sub-kontribusi-input').forEach(input => {
                        if (!input.value || parseRupiah(input.value) <= 0) {
                            input.classList.add('is-invalid');
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });
                    if (!document.getElementById('jamaah_id').value) {
                        valid = false;
                        showToast('error', 'Jamaah wajib dipilih', 'Silakan pilih jamaah terlebih dahulu');
                    }
                    if (!valid) {
                        e.preventDefault();
                        showToast('error', 'Input tidak valid', 'Pastikan semua input terisi dan tidak kosong');
                        return;
                    }
                    // Ubah input ke angka sebelum submit
                    document.querySelectorAll('.sub-kontribusi-input').forEach(input => {
                        input.value = parseRupiah(input.value);
                    });
                    // Form akan submit ke backend
                });
            }

            // Validate form on input
            document.addEventListener('input', validateForm);
        }

        function setupDropdowns() {
            // Jamaah dropdown
            const jamaahSearch = document.getElementById('jamaah_search');
            if (!jamaahSearch) return;

            const jamaahOptions = document.getElementById('jamaah_options');
            let jamaahSearchTimeout;

            jamaahSearch.addEventListener('input', function(e) {
                clearTimeout(jamaahSearchTimeout);
                jamaahSearchTimeout = setTimeout(() => {
                    searchJamaah(e.target.value);
                }, 300);
            });

            jamaahSearch.addEventListener('focus', function() {
                if (this.value.length >= 2) {
                    searchJamaah(this.value);
                }
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.searchable-dropdown')) {
                    document.querySelectorAll('.dropdown-options').forEach(el => {
                        el.classList.remove('show');
                    });
                }
            });
        }

        async function searchJamaah(query) {
            const optionsContainer = document.getElementById('jamaah_options');
            if (!optionsContainer) return;

            if (query.length < 2) {
                optionsContainer.innerHTML = '<div class="no-options">Ketik minimal 2 karakter</div>';
                optionsContainer.classList.add('show');
                return;
            }

            optionsContainer.innerHTML =
                '<div class="loading-options"><i class="bi-spinner bi-spin"></i> Mencari...</div>';
            optionsContainer.classList.add('show');

            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.input-pembayaran.jamaah-options') }}?search=${encodeURIComponent(query)}`
                );
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    optionsContainer.innerHTML = data.data.map(jamaah => `
                    <div class="option-item" 
                         onclick="selectJamaah('${jamaah.jamaah_id}', '${escapeHtml(jamaah.nama_lengkap)}', '${escapeHtml(jamaah.nik || '')}', '${escapeHtml(jamaah.telepon || '')}', '${escapeHtml(jamaah.alamat || '')}')">
                        <strong>${escapeHtml(jamaah.nama_lengkap)}</strong><br>
                        <small>${escapeHtml(jamaah.nik || 'No NIK')} | ${escapeHtml(jamaah.telepon || 'No Telp')}</small>
                    </div>
                `).join('');
                } else {
                    optionsContainer.innerHTML = '<div class="no-options">Tidak ditemukan jamaah</div>';
                }
            } catch (error) {
                console.error('Error searching jamaah:', error);
                optionsContainer.innerHTML = '<div class="no-options">Gagal memuat data</div>';
            }
        }

        function selectJamaah(id, nama, nik, telepon, alamat) {
            document.getElementById('jamaah_id').value = id;
            document.getElementById('jamaah_search').value = nama;
            const optionsContainer = document.getElementById('jamaah_options');
            if (optionsContainer) {
                optionsContainer.classList.remove('show');
            }

            // Show jamaah info
            const jamaahInfo = document.getElementById('jamaahInfo');
            const jamaahNama = document.getElementById('jamaahNama');
            const jamaahNik = document.getElementById('jamaahNik');
            const jamaahTelepon = document.getElementById('jamaahTelepon');
            const jamaahAlamat = document.getElementById('jamaahAlamat');

            if (jamaahNama) jamaahNama.textContent = nama;
            if (jamaahNik) jamaahNik.textContent = `NIK: ${nik || 'Belum ada'}`;
            if (jamaahTelepon) jamaahTelepon.textContent = `Telp: ${telepon || 'Belum ada'}`;
            if (jamaahAlamat) {
                const truncatedAlamat = alamat ?
                    escapeHtml(alamat).substring(0, 50) + (alamat.length > 50 ? '...' : '') :
                    '';
                jamaahAlamat.textContent = alamat ? `Alamat: ${truncatedAlamat}` : 'Alamat: Belum ada';
            }

            if (jamaahInfo) jamaahInfo.style.display = 'block';
            validateForm();
        }

        async function loadSubKontribusi(masterKontribusiId) {
            const container = document.getElementById('subKontribusiContainer');
            const tbody = document.getElementById('subKontribusiBody');
            if (!container || !tbody) return;
            container.style.display = 'block';
            tbody.innerHTML =
                '<tr><td colspan="5" class="text-center"><div class="loading-container"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat data...</p></div></td></tr>';
            try {
                const response = await fetch(
                    `{{ route('admin.kelompok.api.input-pembayaran.sub-kontribusi-options', '') }}/${masterKontribusiId}`
                );
                const data = await response.json();
                if (data.success && Object.keys(data.data).length > 0) {
                    currentSubKontribusi = data.data;
                    const levelLabels = {
                        pusat: 'Pusat',
                        daerah: 'Daerah',
                        desa: 'Desa',
                        kelompok: 'Kelompok'
                    };
                    const levelOrder = ['pusat', 'daerah', 'desa', 'kelompok'];
                    let html = '';
                    let rowNum = 1;
                    levelOrder.forEach(level => {
                        if (data.data[level] && data.data[level].length > 0) {
                            html +=
                                `<tr><th colspan="5" style="background:#f8f9fa;border-bottom: 1px solid #e0e0e0;padding: 10px 15px;">${levelLabels[level]}</th></tr>`;
                            data.data[level].forEach(item => {
                                html += `
                                <tr>
                                    <td>${rowNum++}</td>
                                    <td>${escapeHtml(item.nama_kontribusi)}</td>
                                    <td>
                                        <span style="font-size: 12px; padding: 2px 8px; border-radius: 4px; background: ${item.jenis === 'percentage' ? '#d1ecf1' : '#d4edda'}; color: ${item.jenis === 'percentage' ? '#0c5460' : '#155724'}">
                                            ${item.jenis === 'percentage' ? 'Persentase' : 'Nominal'}
                                        </span>
                                    </td>
                                    <td>
                                        ${item.jenis === 'percentage' ? item.value + '%' : 'Rp ' + formatNumber(item.value)}
                                    </td>
                                    <td>
                                        <div class="rupiah-input">
                                            <span class="rupiah-symbol">Rp.</span>
                                            <input type="text" class="form-control sub-kontribusi-input" 
                                                name="sub_kontribusi[${rowNum-2}][input_value]"
                                                data-sub-kat-id="${item.sub_kat_id}"
                                                value="${item.jenis === 'percentage' ? '0' : formatNumber(item.value)}"
                                                 autocomplete="off">
                                            <input type="hidden" 
                                                name="sub_kontribusi[${rowNum-2}][sub_kat_id]" 
                                                value="${item.sub_kat_id}">
                                        </div>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                    });
                    tbody.innerHTML = html;
                    calculateTotal();
                } else {
                    tbody.innerHTML =
                        '<tr><td colspan="5" class="text-center py-4">Tidak ada data sub kontribusi</td></tr>';
                }
            } catch (error) {
                console.error('Error loading sub kontribusi:', error);
                tbody.innerHTML =
                    '<tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data</td></tr>';
            }
        }

        function parseRupiah(str) {
            return Number((str || '').replace(/[^\d]/g, '')) || 0;
        }

        function calculateTotal() {
            const totalElement = document.getElementById('totalPembayaran');
            if (!totalElement) return;
            let total = 0;
            document.querySelectorAll('.sub-kontribusi-input').forEach(input => {
                const value = parseRupiah(input.value);
                total += value;
            });
            totalElement.textContent = 'Rp ' + formatNumber(total);
        }
        // Format input saat user ketik (rupiah)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('sub-kontribusi-input')) {
                let val = parseRupiah(e.target.value);
                e.target.value = val ? formatNumber(val) : '';
                calculateTotal();
            }
        });

        // Saat submit, ubah semua input ke angka (agar backend dapat angka)
        document.getElementById('pembayaranForm')?.addEventListener('submit', function(e) {
            document.querySelectorAll('.sub-kontribusi-input').forEach(input => {
                input.value = parseRupiah(input.value);
            });
        });

        function validateForm() {
            const jamaahId = document.getElementById('jamaah_id')?.value;
            const submitBtn = document.getElementById('submitBtn');

            if (!submitBtn) return;

            // Check if all required inputs are filled
            let allInputsFilled = true;
            if (jamaahId) {
                const inputs = document.querySelectorAll('.sub-kontribusi-input');
                inputs.forEach(input => {
                    if (!input.value || parseFloat(input.value) <= 0) {
                        allInputsFilled = false;
                    }
                });
            }

            submitBtn.disabled = !jamaahId || !allInputsFilled;
        }

        async function submitForm() {
            if (isSubmitting) return;

            const submitBtn = document.getElementById('submitBtn');
            if (!submitBtn) return;

            const originalText = submitBtn.innerHTML;

            // Validate required inputs in sub kontribusi
            let hasEmptyInput = false;
            document.querySelectorAll('.sub-kontribusi-input').forEach(input => {
                if (!input.value || parseFloat(input.value) <= 0) {
                    hasEmptyInput = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (hasEmptyInput) {
                showToast('warning', 'Peringatan', 'Harap isi semua input pembayaran');
                return;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            try {
                const formData = new FormData(document.getElementById('pembayaranForm'));

                const response = await fetch(
                    '{{ route('admin.kelompok.input-pembayaran.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Berhasil', 'Pembayaran berhasil dicatat');

                    // Reset form after successful submission
                    setTimeout(() => {
                        resetForm();
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showToast('error', 'Error', 'Gagal mencatat pembayaran: ' + error.message);
            } finally {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        function previewFile() {
            const fileInput = document.getElementById('bukti_bayar');
            const fileName = document.getElementById('fileName');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const fileText = document.getElementById('fileText');

            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('error', 'Error', 'Ukuran file maksimal 2MB');
                    fileInput.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    showToast('error', 'Error', 'File harus berupa gambar');
                    fileInput.value = '';
                    return;
                }

                if (fileName) fileName.textContent = file.name;
                if (fileText) fileText.textContent = 'File dipilih:';

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            const fileInput = document.getElementById('bukti_bayar');
            const fileName = document.getElementById('fileName');
            const fileText = document.getElementById('fileText');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');

            if (fileInput) fileInput.value = '';
            if (fileName) fileName.textContent = '';
            if (fileText) fileText.textContent = 'Unggah Bukti Pembayaran';
            if (previewContainer) previewContainer.style.display = 'none';
            if (previewImage) previewImage.src = '';
        }

        function resetForm() {
            const form = document.getElementById('pembayaranForm');
            if (form) form.reset();

            document.getElementById('jamaah_id').value = '';
            document.getElementById('jamaah_search').value = '';
            const jamaahInfo = document.getElementById('jamaahInfo');
            if (jamaahInfo) jamaahInfo.style.display = 'none';

            const tbody = document.getElementById('subKontribusiBody');
            if (tbody) tbody.innerHTML = '';

            document.getElementById('totalPembayaran').textContent = 'Rp 0';
            document.getElementById('tgl_transaksi').value = '{{ date('Y-m-d') }}';
            document.getElementById('submitBtn').disabled = true;
            removeImage();

            // Reload sub kontribusi
            const masterKontribusiId = "{{ request('master_kontribusi_id') }}";
            if (masterKontribusiId) {
                loadSubKontribusi(masterKontribusiId);
            }

            showToast('info', 'Reset', 'Form telah direset');
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

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
                    <div class="toast-title">${escapeHtml(title)}</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="bi-x"></i>
                </button>
            `;

            container.appendChild(toast);

            const existingToasts = container.children.length - 1;
            const delay = existingToasts * 300;

            setTimeout(() => {
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 250);
                }, 3000);
            }, delay);
        }
    </script>
@endpush
