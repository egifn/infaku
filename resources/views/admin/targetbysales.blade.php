<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'Target By Sales')
@section('page-title', 'Target By Sales')
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
    <h2>Target by Sales</h2>
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
                <option>Jawa Barat</option>
                <option>Jawa Tengah</option>
                <option>Jawa Timur</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Depo</label>
            <select id="filter-depo" class="filter-select">
                <option value="">Semua</option>
                <option>DEPO METRO</option>
                <option>DEPO BARAT</option>
                <option>DEPO TIMUR</option>
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

        <div class="filter-group">
            <label>Sales</label>
            <select id="filter-sales" class="filter-select">
                <option value="">Semua</option>
                <option>ARIS RIFAN MUNANDAR</option>
                <option>SALES 2</option>
                <option>SALES 3</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="text-align: left;">NAMA DEPO</th>
                    <th style="text-align: left;">NAMA SALES</th>
                    <th style="text-align: left;">SUPPLIER</th>
                    <th style="text-align: right;">AO</th>
                    <th style="text-align: right;">EC</th>
                    <th style="text-align: right;">VALUE</th>
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
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT YOKLA RITEL INDOINEMA",
                ao: 15,
                ec: 17,
                value: 50858472,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT MITRANAS MARAVIM PERKASA",
                ao: 15,
                ec: 15,
                value: 35543432,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT PINSA MERAIRABADI",
                ao: 15,
                ec: 21,
                value: 20070840,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT TRITA INVESTAMA",
                ao: 4,
                ec: 6,
                value: 1695100,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT BEVERA GREEN INDONESIA",
                ao: 4,
                ec: 4,
                value: 1600000,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT DAESANG AGUNG INDONESIA",
                ao: 5,
                ec: 6,
                value: 1497855,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT RICHELEECENTRING INDONESIA",
                ao: 3,
                ec: 3,
                value: 1747041,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT HENZAGAC INDONESIA",
                ao: 6,
                ec: 6,
                value: 1273230,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT TABURA GENTRI KLEANTARA",
                ao: 4,
                ec: 4,
                value: 836000,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT ESHAM DIMA MANUJNI",
                ao: 4,
                ec: 4,
                value: 791520,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT URAGAN GUMIJANG INDONESIA",
                ao: 1,
                ec: 1,
                value: 651000,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT SUKARDA DIAYA",
                ao: 1,
                ec: 1,
                value: 189701,
                area: "Jawa Barat"
            },
            {
                depo: "DEPO METRO",
                sales: "ARIS RIFAN MUNANDAR",
                supplier: "PT VIRIDA INTERNASIONAL INDONESIA",
                ao: 2,
                ec: 2,
                value: 185877,
                area: "Jawa Barat"
            }
        ];

        // Format angka ke format mata uang Indonesia
        function formatCurrency(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Render data ke tabel
        function renderTable(data) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data yang sesuai dengan filter</td></tr>';
                return;
            }
            
            // Kelompokkan data berdasarkan sales
            const salesGroups = {};
            data.forEach(item => {
                if (!salesGroups[item.sales]) {
                    salesGroups[item.sales] = [];
                }
                salesGroups[item.sales].push(item);
            });
            
            // Render data untuk setiap sales group
            Object.keys(salesGroups).forEach(salesName => {
                const salesData = salesGroups[salesName];
                
                // Hitung subtotal untuk sales ini
                const subtotal = salesData.reduce((acc, item) => {
                    return {
                        ao: acc.ao + item.ao,
                        ec: acc.ec + item.ec,
                        value: acc.value + item.value
                    };
                }, { ao: 0, ec: 0, value: 0 });
                
                // Baris utama (header untuk sales)
                const mainRow = document.createElement('tr');
                mainRow.className = 'main-row';
                mainRow.innerHTML = `
                    <td class="data-sales">${salesData[0].depo}</td>
                    <td class="data-sales">${salesName}</td>
                    <td>${salesData[0].supplier}</td>
                    <td class="numeric-cell">${salesData[0].ao}</td>
                    <td class="numeric-cell">${salesData[0].ec}</td>
                    <td class="value-cell">${formatCurrency(salesData[0].value)}</td>
                `;
                tableBody.appendChild(mainRow);
                
                // Baris untuk setiap item dalam sales group (dimulai dari index 1)
                for (let i = 1; i < salesData.length; i++) {
                    const item = salesData[i];
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td></td>
                        <td></td>
                        <td>${item.supplier}</td>
                        <td class="numeric-cell">${item.ao}</td>
                        <td class="numeric-cell">${item.ec}</td>
                        <td class="value-cell">${formatCurrency(item.value)}</td>
                    `;
                    tableBody.appendChild(row);
                }
                
                // Baris subtotal
                const subtotalRow = document.createElement('tr');
                subtotalRow.className = 'subtotal-row';
                subtotalRow.innerHTML = `
                    <td></td>
                    <td class="data-sales">TOTAL</td>
                    <td></td>
                    <td class="numeric-cell"><strong>${subtotal.ao}</strong></td>
                    <td class="numeric-cell"><strong>${subtotal.ec}</strong></td>
                    <td class="value-cell"><strong>${formatCurrency(subtotal.value)}</strong></td>
                `;
                tableBody.appendChild(subtotalRow);
            });
        }

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