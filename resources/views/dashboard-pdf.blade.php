<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4e73df;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #4e73df;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background: #4e73df;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f8f9fc;
        }
        .total-box {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
        }
        .total-box .label {
            font-weight: bold;
            color: #4e73df;
        }
        .total-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #1cc88a;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ASSALAAM HYPERMARKET</h1>
        <p>Jl. Ahmad Yani 308, Pabejan, Kec. Kartasura, Kabupaten Sukoharjo</p>
        <p>Telp: 0271-743333 | Email: assalaam.hypermarket@gmail.com</p>
    </div>

    <div class="info-box">
        <h3>Laporan Penjualan</h3>
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">No. Transaksi</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 20%;">Kasir</th>
                <th style="width: 35%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sale->no_trans }}</td>
                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $sale->cashier->name ?? '-' }}</td>
                <td style="text-align: right;">Rp {{ number_format($sale->total_cents, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <p class="label">TOTAL PENJUALAN:</p>
        <p class="value">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        <p style="margin-top: 10px; color: #666;">Jumlah Transaksi: {{ $sales->count() }}</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem Kasir Bazar</p>
        <p>&copy; {{ date('Y') }} Assalaam Hypermarket. All Rights Reserved.</p>
    </div>
</body>
</html>