<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Profit</th>
        </tr>
        <tr>
            <td>{{ $data['date'] ?? '-' }}</td>
            <td>{{ $data['income'] ?? 0 }}</td>
            <td>{{ $data['expenses'] ?? 0 }}</td>
            <td>{{ $data['profit'] ?? 0 }}</td>
        </tr>
    </table>
</body>
</html>
