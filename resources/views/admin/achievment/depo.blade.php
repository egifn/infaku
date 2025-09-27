<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'Achievement / Depo')
@section('page-title', 'Achievement / Depo')
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
<div style="margin: 5px 0px">
    <h2>Achievement / Depo</h2>
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
            <label>Month</label>
            <input type="month" id="filter-bulan" class="filter-select">
        </div>

        {{-- <div class="filter-group">
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
            </select>
        </div> --}}
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="text-align: left;">SEGMENT</th>
                    <th style="text-align: right;">AO</th>
                    <th style="text-align: right;">EC</th>
                    <th style="text-align: right;">VALUE</th>
                    <th style="text-align: right;">MTD</th>
                    <th style="text-align: right;">ACT VS LM</th>
                    <th style="text-align: right;">AVG (2 BS)</th>
                    <th style="text-align: right;">AVG (1 BS)</th>
                    <th style="text-align: right;">GR by SPD</th>
                    <th style="text-align: right;">BE</th>
                    <th style="text-align: right;">BE VS MTD</th>
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
            segment: "GT",
            ao: 962,
            ec: 2549,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "(2,364,450,231)",
            act_vs_lm: 5,
            avg_2bs: "126509183",
            avg_1bs: "3,289,238,745",
            gr_by_spd: 10,
            be: "3,289,238,745",
            be_vs_mtd: 10
        },
        {
            segment: "Mr",
            ao: 7,
            ec: 199,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "(20,235,073)",
            act_vs_lm: 5,
            avg_2bs: "26,320,596",
            avg_1bs: "684,335,505",
            gr_by_spd: 10,
            be: "684,335,505",
            be_vs_mtd: 10
        },
        {
            segment: "FS",
            ao: 2,
            ec: 142,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "209,362,535",
            act_vs_lm: 5,
            avg_2bs: "19,032,958",
            avg_1bs: "494,856,903",
            gr_by_spd: 10,
            be: "494,856,903",
            be_vs_mtd: 10
        },
        {
            segment: "AFH",
            ao: 241,
            ec: 344,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "(671,747,267)",
            act_vs_lm: 5,
            avg_2bs: "8,303,662",
            avg_1bs: "216051,217",
            gr_by_spd: 10,
            be: "216051,217",
            be_vs_mtd: 10
        },
        {
            segment: "INTERN",
            ao: 4,
            ec: 42,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "7,255,711",
            act_vs_lm: 5,
            avg_2bs: "659.510",
            avg_1bs: "17,149,862",
            gr_by_spd: 10,
            be: "17,149,862",
            be_vs_mtd: 10
        },
        {
            segment: "ioD",
            ao: 6,
            ec: 6,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "1,778.23",
            act_vs_lm: 5,
            avg_2bs: "161,657",
            avg_1bs: "4,208,073",
            gr_by_spd: 10,
            be: "4,208,073",
            be_vs_mtd: 10
        },
        {
            segment: "AHS",
            ao: 3,
            ec: 5532100, // Perhatikan: 5 532,100 dalam gambar
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "(12,618,874)",
            act_vs_lm: 5,
            avg_2bs: "48,373",
            avg_1bs: "1,257,681",
            gr_by_spd: 10,
            be: "1,257,681",
            be_vs_mtd: 10
        },
        {
            segment: "Grand Total",
            ao: 2395,
            ec: 3287,
            value: 111111111, // Perhatikan: 111,111,111 dalam gambar
            mtd: "(2,860,303,332)",
            act_vs_lm: 5,
            avg_2bs: "181,042,038",
            avg_1bs: "4,707,092,937",
            gr_by_spd: "4,707,092,937",
            be: "4,707,092,937",
            be_vs_mtd: 10
        }
    ];

        // Format angka ke format mata uang Indonesia
        function formatCurrency(number) {
            if (number === null || number === undefined) return '';
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Render data ke tabel
        function renderTable(data) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 20px;">Tidak ada data yang sesuai dengan filter</td></tr>';
                return;
            }
            
            // Render setiap baris data
            data.forEach(item => {
                const row = document.createElement('tr');
                
                // Tambahkan class untuk Grand Total
                if (item.segment === "Grand Total") {
                    row.className = 'subtotal-row main-row';
                } else if (item.segment === "GT" || item.segment === "Mr" || item.segment === "FS") {
                    row.className = 'main-row';
                }
                
                row.innerHTML = `
                    <td style="text-align: left; font-weight: ${item.segment === "Grand Total" ? '700' : '600'};">${item.segment}</td>
                    <td class="numeric-cell">${item.ao !== null ? formatCurrency(item.ao) : ''}</td>
                    <td class="numeric-cell">${item.ec !== null ? formatCurrency(item.ec) : ''}</td>
                    <td class="value-cell">${item.value || ''}</td>
                    <td class="numeric-cell">${item.mtd || ''}</td>
                    <td class="numeric-cell">${item.act_vs_lm + '%'|| ''}</td>
                    <td class="numeric-cell">${item.avg_2bs || ''}</td>
                    <td class="numeric-cell">${item.avg_1bs || ''}</td>
                    <td class="numeric-cell">${item.gr_by_spd + '%'|| ''}</td>
                    <td class="numeric-cell">${item.be || ''}</td>
                    <td class="numeric-cell">${item.be_vs_mtd + '%'|| ''}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    </script>
</script>
@endpush