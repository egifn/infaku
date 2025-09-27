<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'Lead Time ')
@section('page-title', 'Lead Time ')
@section('icon-page-title', 'eg-document')

@push('style')
    <style>
      .dashboard-container {
            background: #ffffff;
            border: 1px solid #dcdcdc;
            overflow: hidden;
            margin-bottom: 20px;
        }

        /* ---------- HEADER ---------- */
        .table-header {
            background: #dcdcdc;
            border-bottom: 1px solid #dcdcdc;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .table-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }
        .table-actions {
            display: flex;
            gap: 8px;
        }
        .btn {
            padding: 6px 10px;
            font-size: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
        }
        .btn:hover { background: #f1f5f9; }

        /* ---------- FILTER ---------- */
        .filter-container {
            padding: 10px 16px;
            background: #ffffff;
            border-bottom: 1px solid #f2f2f2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }
        .filter-group label {
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            display: block;
            margin-bottom: 4px;
        }
        .filter-select {
            width: 100%;
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }

        /* ---------- TABLE ---------- */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        .data-table {
            width: 100%;
            min-width: 600px; /* agar bisa scroll horizontal di layar kecil */
            border-collapse: collapse;
            font-size: 12px;
        }
        .data-table th,
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f2f2f2;
            white-space: nowrap;
        }
        .data-table th {
            background: #dcdcdc;
            color: #334155;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        .data-table tr:hover td { background: #f2f2f2; }
        .data-table .main-row   { font-weight: 600; }
        .data-sales { font-weight: 700;}
        .data-table .subtotal-row { background: #f2f2f2; font-weight: 600; }
        .numeric-cell,
        .value-cell {
            text-align: right;
            font-family: "SF Mono", Monaco, Inconsolata, monospace;
            color: #0f172a;
        }

        /* ---------- FOOTER ---------- */
        .footer-info {
            padding: 8px 10px;
            background: #bababa;
            border-top: 1px solid #f2f2f2;
            font-size: 12px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px) {
            .table-header,
            .filter-container,
            .footer-info {
                flex-direction: column;
                align-items: flex-start;
            }
            .filter-select { width: 100%; }
        }
    </style>
@endpush

@section('content')
<div style="margin-bottom: 10px">
    <h2>Lead Time </h2>
</div>
<div class="dashboard-container">
    <!-- Header -->
    <div class="table-header">
        <h1>Data Report</h1>
        <div class="table-actions">
            <button class="btn"><i class="fas fa-download"></i> Export</button>
            <button class="btn"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-container">
        <div class="filter-group">
            <label>Area</label>
            <select id="filter-area" class="filter-select">
                <option value="">Semua</option>
                <option>TUA</option>
                <option>LP</option>
                <option>WPS</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Depo</label>
            <select id="filter-depo" class="filter-select">
                <option value="">Semua</option>
                <option>DEPO METRO</option>
                <option>DEPO SEDAKELING</option>
            </select>
        </div>

         <div class="filter-group">
            <label>Channel</label>
            <select id="filter-sales" class="filter-select">
                <option value="">Semua</option>
                <option>SO</option>
                <option>DO</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Supplier</label>
            <select id="filter-supplier" class="filter-select">
                <option value="">Semua</option>
                <option>PT YOKLA RITEL INDOINEMA</option>
                <option>PT MITRANAS MARAVIM PERKASA</option>
                <option>PT PINSA MERAIRABADI</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="text-align: left;">DEPO</th>
                    <th style="text-align: right;">SO</th>
                    <th style="text-align: right;">DO REAL</th>
                    <th style="text-align: right;">DO MAX</th>
                    <th style="text-align: right;">%</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer-info">
        <span>Generated: <span id="current-date"></span></span>
        <span>Source: Internal System</span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        // Data JSON untuk tabel
        const tableData = [
            { depo: "DEPO BOGOR", so: 1306, do_real: 1110, do_max: 1110, percentage: 85 },
            { depo: "DEPO CIANJUR", so: 310, do_real: 309, do_max: 309, percentage: 100 },
            { depo: "DEPO CIREBON", so: 275, do_real: 275, do_max: 275, percentage: 100 },
            { depo: "DEPO GARUT", so: 376, do_real: 375, do_max: 375, percentage: 100 },
            { depo: "DEPO JATIBARANG", so: 95, do_real: 95, do_max: 95, percentage: 100 },
            { depo: "DEPO JATISARI", so: 772, do_real: 770, do_max: 770, percentage: 100 },
            { depo: "DEPO JONGGOL", so: 244, do_real: 244, do_max: 244, percentage: 100 },
            { depo: "DEPO KUNINGAN", so: 155, do_real: 154, do_max: 154, percentage: 99 },
            { depo: "DEPO MAJALENGKA", so: 78, do_real: 78, do_max: 78, percentage: 100 },
            { depo: "DEPO METRO", so: 3628, do_real: 3605, do_max: 3604, percentage: 99 },
            { depo: "DEPO PADALARANG", so: 217, do_real: 190, do_max: 190, percentage: 88 },
            { depo: "DEPO SUKABUMI", so: 7, do_real: 7, do_max: 7, percentage: 100 },
            { depo: "DEPO SUMEDANG", so: 506, do_real: 506, do_max: 506, percentage: 100 },
            { depo: "DEPO TASIKMALAYA", so: 5439, do_real: 5391, do_max: 5391, percentage: 99 }
        ];

        // Format angka
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Render data ke tabel
        function renderTable(data) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            
            // Hitung grand total
            const grandTotal = data.reduce((acc, item) => {
                return {
                    so: acc.so + item.so,
                    do_real: acc.do_real + item.do_real,
                    do_max: acc.do_max + item.do_max
                };
            }, { so: 0, do_real: 0, do_max: 0 });
            
            // Hitung persentase grand total
            const grandPercentage = Math.round((grandTotal.do_real / grandTotal.so) * 100);
            
            // Render setiap baris data
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="text-align: left;">${item.depo}</td>
                    <td class="numeric-cell">${formatNumber(item.so)}</td>
                    <td class="numeric-cell">${formatNumber(item.do_real)}</td>
                    <td class="numeric-cell">${formatNumber(item.do_max)}</td>
                    <td class="numeric-cell">${item.percentage}%</td>
                `;
                tableBody.appendChild(row);
            });
            
            // Baris Grand Total
            const grandTotalRow = document.createElement('tr');
            grandTotalRow.className = 'subtotal-row';
            grandTotalRow.innerHTML = `
                <td style="text-align: left; font-weight: 700;">Grand Total</td>
                <td class="numeric-cell" style="font-weight: 700;">${formatNumber(grandTotal.so)}</td>
                <td class="numeric-cell" style="font-weight: 700;">${formatNumber(grandTotal.do_real)}</td>
                <td class="numeric-cell" style="font-weight: 700;">${formatNumber(grandTotal.do_max)}</td>
                <td class="numeric-cell" style="font-weight: 700;">${grandPercentage}%</td>
            `;
            tableBody.appendChild(grandTotalRow);
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            renderTable(tableData);
        });

        // Filter data berdasarkan kriteria
        function filterData() {
            const areaFilter = document.getElementById('filter-area').value;
            const depoFilter = document.getElementById('filter-depo').value;
            const supplierFilter = document.getElementById('filter-supplier').value;
            const salesFilter = document.getElementById('filter-sales').value;
            
            const filteredData = tableData.filter(item => {
                return (!areaFilter || item.area === areaFilter) &&
                       (!depoFilter || item.depo === depoFilter) &&
                       (!supplierFilter || item.supplier === supplierFilter) &&
                       (!salesFilter || item.sales === salesFilter);
            });
            
            renderTable(filteredData);
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal hari ini
            const today = new Date();
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('current-date').textContent = today.toLocaleDateString('id-ID', options);
            
            // Render data awal
            renderTable(tableData);
            
            // Tambahkan event listener untuk filter
            document.getElementById('filter-area').addEventListener('change', filterData);
            document.getElementById('filter-depo').addEventListener('change', filterData);
            document.getElementById('filter-supplier').addEventListener('change', filterData);
            document.getElementById('filter-sales').addEventListener('change', filterData);
            
            // Event handlers untuk tombol export dan print
            document.querySelector('.btn-primary').addEventListener('click', function() {
                alert('Fitur export akan diimplementasikan di sini');
            });
            
            document.querySelector('.btn-secondary').addEventListener('click', function() {
                window.print();
            });
        });
    </script>
</script>
@endpush