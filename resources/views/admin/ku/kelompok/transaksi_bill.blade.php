<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bill Pembayaran</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
        }
        .muted { color: #666; }
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 12px 0;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }
        .col {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background: #f7f7f7;
            font-weight: 600;
        }
        .text-right { text-align: right; }
        .total {
            font-size: 13px;
            font-weight: bold;
        }
        .footer {
            margin-top: 16px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">Bill Pembayaran</div>
            <div class="muted">{{ $infoKelompok->nama_kelompok ?? 'Kelompok' }}</div>
        </div>
        <div>
            <div><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</div>
            <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d M Y') }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col">
            <div class="section-title">Jamaah</div>
            <div>{{ $transaksi->nama_lengkap }}</div>
            <div class="muted">{{ $transaksi->telepon }}</div>
            <div class="muted">{{ $transaksi->alamat }}</div>
        </div>
        <div class="col">
            <div class="section-title">Pembayaran</div>
            <div><strong>Metode:</strong> {{ $transaksi->metode_bayar }}</div>
            <div><strong>Status:</strong> {{ $transaksi->status }}</div>
            <div><strong>Kategori:</strong> {{ $transaksi->nama_kontribusi ?? '-' }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="section-title">Rincian</div>
    <table>
        <thead>
            <tr>
                <th>Sub Kontribusi</th>
                <th>Level</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $d)
                <tr>
                    <td>{{ $d->sub_nama ?? 'Kontribusi' }}</td>
                    <td>{{ $d->level ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right total">Total</td>
                <td class="text-right total">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    @if (!empty($transaksi->keterangan))
        <div class="divider"></div>
        <div class="section-title">Catatan</div>
        <div>{{ $transaksi->keterangan }}</div>
    @endif

    <div class="footer">
        Bill ini dibuat otomatis oleh sistem.
    </div>
</body>
</html>
