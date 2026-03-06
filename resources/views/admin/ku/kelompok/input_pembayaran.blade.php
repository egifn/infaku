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
            font-size: 12px;
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
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .select-btn:hover {
            background: #0d8b66;
            transform: translateY(-1px);
        }

        /* Styles untuk halaman form input pembayaran */
        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .card-body-pos {
            padding: 15px 20px;
        }

        .form-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
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
            font-size: 12px;
        }

        .form-label.required::after {
            content: ' *';
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            color: #4d4d4d;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            background: white;
            color: #4d4d4d;
            cursor: pointer;
        }

        .form-text {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 100px;
        }

        .btn-primary {
            background: #105a44;
            color: white;
        }

        .btn-primary:hover {
            background: #0d8b66;
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
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
            border-radius: 8px;
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
            padding: 10px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }

        .sub-kontribusi-table tbody tr:hover {
            background: #f8f9fa;
        }

        .sub-kontribusi-table .input-column {
            width: 200px;
        }

        .sub-kontribusi-table input[type="text"] {
            width: 100%;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            text-align: right;
        }

        .sub-kontribusi-table input[type="text"]:focus {
            outline: none;
            border-color: #105a44;
            box-shadow: 0 0 0 2px rgba(16, 90, 68, 0.1);
        }

        .sub-kontribusi-table .rupiah-input {
            position: relative;
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
            justify-content: space-between;
            margin-top: 15px;
            padding: 10px 20px;
            border-top: 2px solid #e0e0e0;
        }

        .total-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-left: 45px;
        }

        .total-amount {
            font-weight: 700;
            color: #105a44;
            font-size: 16px;
            min-width: 150px;
            text-align: right;
        }

        /* File Upload */
        .file-upload {
            position: relative;
            width: 100%;
            min-height: 36px;
        }

        .file-input {
            display: none;
        }

        .file-label {
            width: 100%;
            min-height: 33px;
            display: flex;
            font-size: 12px;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: #666;
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 12px;
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


        /* Animation untuk card */
        @keyframes fadeInUp {
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

        /* Validation Styles */
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff8f8;
        }

        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: block;
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 9px;
            color: #dc3545;
        }

        /* Utility Classes */
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

        .mt-3 {
            margin-top: 1rem;
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

        .me-2 {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="input-container">
        @if (request()->has('master_kontribusi_id'))
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">Input Pembayaran Kontribusi</h3>
                    <button type="button" class="back-btn" onclick="goBackToSelection()">
                        <i class="bi-arrow-left"></i> Kembali
                    </button>
                </div>

                <div class="card-body-pos">
                    <div class="form-container">
                        <form id="pembayaranForm" method="POST"
                            action="{{ route('admin.kelompok.input-pembayaran.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="master_kontribusi_id" name="master_kontribusi_id"
                                value="{{ request('master_kontribusi_id') }}">
                            <!-- DATA JAMAAH -->
                            <div class="form-section">
                                <div class="section-title"><i class="bi-people"></i> Data Jamaah</div>
                                <div class="form-group">
                                    <label class="form-label required">Pilih Jamaah</label>
                                    <div class="searchable-dropdown">
                                        <input type="text" class="dropdown-search" id="jamaah_search"
                                            placeholder="Cari jamaah..." autocomplete="off"><i
                                            class="bi-search dropdown-search-icon"></i>
                                        <div class="dropdown-options" id="jamaah_options"></div>
                                    </div>
                                    <input type="hidden" id="jamaah_id" name="jamaah_id">
                                </div>
                                <!-- INFO JAMAAH -->
                                <div id="jamaahInfo" class="jamaah-info" style="display:none">
                                    <div class="jamaah-name" id="jamaahNama"></div>
                                    <div class="jamaah-details"><span id="jamaahAlamat"></span> | <span id="jamaahTelepon">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- DATA PEMBAYARAN -->
                            <div class="form-section">
                                <div class="section-title"><i class="bi-cash"></i> Data Pembayaran</div>
                                <div class="form-row">
                                    <div class="form-group"><label class="form-label required">Tanggal</label><input
                                            type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group"><label class="form-label required">Metode</label><select
                                            class="form-select" id="metode_bayar" name="metode_bayar">
                                            <option value="">Pilih</option>
                                            <option value="TUNAI" selected>TUNAI</option>
                                            <option value="TRANSFER">TRANSFER</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- SUB KONTRIBUSI -->
                                <div id="subKontribusiContainer" style="display:none">
                                    <table class="sub-kontribusi-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Jenis</th>
                                                <th>Value</th>
                                                <th>Input</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subKontribusiBody"></tbody>
                                    </table>
                                    <div class="total-section">
                                        <div class="total-label">TOTAL</div>
                                        <div class="total-amount" id="totalPembayaran">Rp 0</div>
                                        <input type="hidden" id="total_pembayaran" name="total_pembayaran">
                                    </div>
                                </div>
                            </div>

                            <!-- BUKTI -->
                            <div class="form-section">
                                <div class="section-title"><i class="bi-receipt"></i> Bukti</div>
                                <div class="form-row">
                                    <div class="form-group"><label>Keterangan</label><input class="form-control"
                                            name="keterangan">
                                    </div>
                                    <div class="form-group">
                                        <label>Bukti</label>
                                        <div class="file-upload">
                                            <input type="file" class="file-input" id="bukti_bayar" name="bukti_bayar"
                                                accept="image/*" onchange="previewFile()">
                                            <label for="bukti_bayar" class="file-label">
                                                <i class="bi-upload"></i>
                                                <span id="fileText">Upload</span>
                                                <span class="file-name" id="fileName"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="preview-container" id="previewContainer" style="display:none">
                                    <img id="previewImage" class="preview-image">
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImage()">
                                        Hapus</button>
                                </div>
                            </div>

                            <!-- ACTION -->
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    Reset</button>
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection


@push('scripts')
    <script>
        let isSubmitting = false;
        /* INIT */
        document.addEventListener('DOMContentLoaded', () => {

            const id = "{{ request('master_kontribusi_id') }}";
            if (id) {
                loadSubKontribusi(id);
                initForm();
                initDropdown();
            }
        });
        /* FORM */
        function initForm() {

            const form = document.getElementById('pembayaranForm');
            form.addEventListener('input', e => {
                if (e.target.classList.contains('sub-kontribusi-input')) {
                    let v = parseRupiah(e.target.value);
                    e.target.value = formatNumber(v);
                    hitungTotal();
                    validateForm();
                }

                if (['jamaah_search', 'tgl_transaksi', 'metode_bayar'].includes(e.target.id)) {
                    validateForm();
                }
            });
            form.addEventListener('submit', handleSubmit);
        }
        /* VALIDASI */
        function validateForm() {

            const jamaah = document.getElementById('jamaah_id').value;
            const tgl = document.getElementById('tgl_transaksi').value;
            const metode = document.getElementById('metode_bayar').value;
            document.getElementById('submitBtn').disabled = !(jamaah && tgl && metode);
        }
        /* SUBMIT */
        function handleSubmit(e) {

            e.preventDefault();
            if (isSubmitting) return;

            /* VALIDASI WAJIB */
            if (!document.getElementById('jamaah_id').value) {
                window.showToast('Pilih jamaah terlebih dahulu', 'error');
                return;
            }

            if (!document.getElementById('tgl_transaksi').value) {
                window.showToast('Tanggal wajib diisi', 'error');
                return;
            }

            if (!document.getElementById('metode_bayar').value) {
                window.showToast('Pilih metode pembayaran', 'error');
                return;
            }

            showConfirmModal();
        }

        /* MODAL */
        function showConfirmModal() {

            const old = document.getElementById('confirmModal');
            if (old) old.remove();

            const nama = document.getElementById('jamaah_search').value;
            const total = document.getElementById('totalPembayaran').innerText;

            const html = `
                <div id="confirmModal" style="position:fixed;inset:0;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;z-index:9999">
                    <div style="background:#fff;width:100%;max-width:420px;border-radius:10px;overflow:hidden">
                        <div style="padding:12px 16px;background:#105a44;color:#fff;font-weight:600">Konfirmasi</div>
                        <div style="padding:18px;font-size:13px"><p>Yakin simpan transaksi?</p><div style="background:#f8f9fa;border:1px solid #ddd;border-radius:6px;padding:10px"><div><small>Nama Jamaah</small><br><b>${escapeHtml(nama)}</b></div><div style="margin-top:6px"><small>Total</small><br><b style="color:#0d8b66">${total}</b></div></div>
                        </div>
                        <div style="padding:10px 16px;background:#f3f3f3;text-align:right"><button id="btnCancelConfirm" class="btn btn-secondary btn-sm" style="margin-right: 5px;">Batal</button><button id="btnOkConfirm" class="btn btn-success btn-sm">Simpan</button>
                        </div>
                    </div>
                </div>`;

            document.body.insertAdjacentHTML('beforeend', html);
            document.getElementById('btnCancelConfirm').onclick = () => document.getElementById('confirmModal').remove();
            document.getElementById('btnOkConfirm').onclick = () => {
                document.getElementById('confirmModal').remove();
                submitFinal();
            };
        }
        /* SUBMIT AJAX */
        async function submitFinal() {

            const form = document.getElementById('pembayaranForm');
            const btn = document.getElementById('submitBtn');

            isSubmitting = true;
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            document.querySelectorAll('.sub-kontribusi-input').forEach(i => {
                i.value = parseRupiah(i.value);
            });

            try {
                const fd = new FormData(form);
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const json = await res.json();

                if (res.ok && json.success) {
                    window.showToast(json.message || 'Berhasil', 'success');
                    setTimeout(resetForm, 1500);
                } else {
                    throw new Error(json.message || 'Gagal');
                }
            } catch (err) {

                window.showToast(err.message, 'error');

            } finally {

                isSubmitting = false;
                btn.disabled = false;
                btn.innerHTML = 'Simpan';
            }
        }
        /* SUB KONTRIBUSI */
        async function loadSubKontribusi(id) {

            const box = document.getElementById('subKontribusiContainer');
            const body = document.getElementById('subKontribusiBody');

            box.style.display = 'block';
            body.innerHTML = '<tr><td colspan="5" style="text-size:10px">Loading...</td></tr>';

            const res = await fetch(
                `{{ route('admin.kelompok.api.input-pembayaran.sub-kontribusi-options', '') }}/${id}`);

            const json = await res.json();

            renderSubKontribusi(json.data);
        }


        function renderSubKontribusi(data) {

            const body = document.getElementById('subKontribusiBody');
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
                if (data[level] && data[level].length > 0) {
                    html +=
                        `<tr><th colspan="5" style="background:#f8f9fa;border-bottom: 1px solid #e0e0e0;padding: 10px 15px;">${levelLabels[level]}</th></tr>`;
                    data[level].forEach(item => {
                        html += `
                        <tr>
                            <td>${rowNum}</td>
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
                                        name="sub_kontribusi[${rowNum-1}][input_value]"
                                        data-sub-kat-id="${item.sub_kat_id || item.id}"
                                        value="${item.jenis === 'percentage' ? '0' : formatNumber(item.value)}"
                                         autocomplete="off">
                                    <input type="hidden" 
                                        name="sub_kontribusi[${rowNum-1}][sub_kat_id]" 
                                        value="${item.sub_kat_id || item.id}">
                                </div>
                            </td>
                        </tr>
                        `;
                        rowNum++;
                    });
                }
            });
            body.innerHTML = html;
            hitungTotal();
            validateForm();
        }

        /* TOTAL */
        function hitungTotal() {

            let t = 0;

            document.querySelectorAll('.sub-kontribusi-input').forEach(i => {
                t += parseRupiah(i.value);
            });

            document.getElementById('totalPembayaran').innerText = 'Rp ' + formatNumber(t);
            document.getElementById('total_pembayaran').value = t;
        }

        /* DROPDOWN */
        function initDropdown() {

            const s = document.getElementById('jamaah_search');
            const o = document.getElementById('jamaah_options');
            let t;

            s.addEventListener('input', e => {

                clearTimeout(t);

                if (e.target.value.length < 2) {
                    o.classList.remove('show');
                    return;
                }

                t = setTimeout(() => searchJamaah(e.target.value), 300);
            });

            document.addEventListener('click', e => {
                if (!e.target.closest('.searchable-dropdown')) o.classList.remove('show');
            });
        }


        async function searchJamaah(q) {

            const box = document.getElementById('jamaah_options');

            box.innerHTML = '<div class="loading-options"><i class="bi bi-arrow-repeat spin"></i> Mencari...</div>';
            box.classList.add('show');

            const res = await fetch(`{{ route('admin.kelompok.api.input-pembayaran.jamaah-options') }}?search=${q}`);
            const json = await res.json();

            if (json.success) {
                box.innerHTML = json.data.map(j => `
                        <div class="option-item" onclick='selectJamaah("${j.jamaah_id}","${escapeHtml(j.nama_lengkap)}","${escapeHtml(j.nik)}","${escapeHtml(j.telepon)}","${escapeHtml(j.alamat)}")'>
                            <strong>${escapeHtml(j.nama_lengkap)}</strong><br>
                            <small>${escapeHtml(j.alamat)} | ${escapeHtml(j.telepon)}</small>
                        </div>
                `).join('');
            }
        }

        /* SELECT JAMAAH */
        function selectJamaah(id, nama, nik, telp, alamat) {

            document.getElementById('jamaah_id').value = id;
            document.getElementById('jamaah_search').value = nama;

            document.getElementById('jamaahNama').innerText = nama;
            document.getElementById('jamaahTelepon').innerText = 'Telp: ' + (telp || '-');
            document.getElementById('jamaahAlamat').innerText = alamat || '-';

            document.getElementById('jamaahInfo').style.display = 'block';
            document.getElementById('jamaah_options').classList.remove('show');

            validateForm();
        }

        /* RESET */
        function resetForm() {

            document.getElementById('pembayaranForm').reset();

            document.getElementById('jamaah_id').value = '';
            document.getElementById('jamaahInfo').style.display = 'none';

            document.getElementById('totalPembayaran').innerText = 'Rp 0';
            document.getElementById('submitBtn').disabled = true;

            window.showToast('Form direset', 'info');
        }

        /* UTIL */
        function parseRupiah(s) {
            return parseInt((s || '').replace(/[^\d]/g, '')) || 0;
        }

        function formatNumber(n) {
            return new Intl.NumberFormat('id-ID').format(n || 0);
        }

        function escapeHtml(t) {
            const d = document.createElement('div');
            d.textContent = t;
            return d.innerHTML;
        }

        /* FILE */
        function previewFile() {
            const fileInput = document.getElementById('bukti_bayar');
            const fileName = document.getElementById('fileName');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const fileText = document.getElementById('fileText');

            // Reset preview terlebih dahulu
            if (fileName) fileName.textContent = '';
            if (fileText) fileText.textContent = 'Upload';
            if (previewContainer) previewContainer.style.display = 'none';
            if (previewImage) previewImage.src = '';

            if (fileInput.files && fileInput.files.length > 0 && fileInput.files[0]) {
                const file = fileInput.files[0];

                // Validasi ukuran file (maks 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    window.showToast && window.showToast('Ukuran file maksimal 2MB', 'error');
                    fileInput.value = '';
                    return;
                }

                // Validasi tipe file gambar
                if (!file.type.match('image/')) {
                    window.showToast && window.showToast('File harus berupa gambar', 'error');
                    fileInput.value = '';
                    return;
                }

                if (fileName) fileName.textContent = file.name;
                if (fileText) fileText.textContent = 'File dipilih:';

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'block';
                };
                reader.onerror = function() {
                    window.showToast && window.showToast('Gagal membaca file', 'error');
                    fileInput.value = '';
                    if (fileName) fileName.textContent = '';
                    if (fileText) fileText.textContent = 'Upload';
                    if (previewContainer) previewContainer.style.display = 'none';
                    if (previewImage) previewImage.src = '';
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {

            const input = document.getElementById('bukti_bayar');
            const preview = document.getElementById('previewContainer');
            const fileName = document.getElementById('fileName');
            const fileText = document.getElementById('fileText');

            input.value = '';

            if (preview) preview.style.display = 'none';
            if (fileName) fileName.textContent = '';
            if (fileText) fileText.textContent = 'Upload';
        }
    </script>
@endpush
