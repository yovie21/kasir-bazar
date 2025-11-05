<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            /* Font mirip thermal printer */
            font-family: 'Courier New', Courier, monospace; 
            font-size: 13px;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .receipt {
            /* Lebar disesuaikan agar padat, seperti nota fisik */
            width: 250px; 
            margin: 20px auto;
            padding: 10px 12px;
            border: 1px dashed #000;
        }
        h2 {
            text-align: center;
            margin: 0 0 2px 0;
            font-size: 15px;
            font-weight: normal; /* Untuk mengurangi ketebalan */
        }
        .center-text {
            text-align: center;
            margin: 0 0 8px 0;
            font-size: 13px;
        }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        
        /* Gaya untuk info transaksi */
        .info-transaksi p {
            margin: 2px 0;
            line-height: 1.4;
            /* Penting untuk menjaga spasi agar teks sejajar */
            white-space: pre; 
        }
        
        /* --- DAFTAR BARANG BARU (DIV/SPAN BASED) --- */
        .header-item {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin-top: 6px;
        }
        .item-row {
            margin-top: 5px;
            padding-top: 2px;
            line-height: 1.4;
        }
        .item-name-line {
            display: flex;
            justify-content: space-between;
            font-weight: normal; 
            margin-bottom: 1px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            /* Indentasi untuk detail harga/qty */
            padding-left: 5px; 
            font-size: 12px;
        }
        .subtotal-val {
            text-align: right;
            font-weight: normal;
        }

        /* --- STYLING UNTUK TOTALS (Tabel Sederhana) --- */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 1px 0;
            font-size: 13px;
        }
        .totals-table .label {
            width: 65%;
            font-weight: bold;
        }
        .totals-table .value {
            width: 35%;
            text-align: right;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 12px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-size: 12px;
            line-height: 1.6;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <h2 style="font-weight: bold;">Assallam Hypermarket Bazar</h2>
        <p class="center-text">Jl. Ahmad Yani 308, Pabelan, Kec. Kartasura, Kabupaten Sukoharjo</p>

        <div class="info-transaksi">
            <p>No. Trans:{{ $sale->no_trans }}<span style="float: right;"></span></p>
            <p>Kasir:{{ $sale->cashier->name ?? 'Admin' }}<span style="float: right;"></span></p>
            <p>Tanggal:{{ $sale->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <hr>

        <div class="header-item">
            <span>Barang</span>
            <span>Jumlah</span>
        </div>
        
        <div class="item-list">
            @foreach($sale->items as $item)
            <div class="item-row">
                <div class="item-name-line">
                    <span style="font-weight: bold;">{{ $item->product->name }}</span>
                    <span class="subtotal-val">{{ number_format($item->qty * $item->price_cents, 0, ',', '.') }}</span>
                </div>
                <div class="item-details">
                    <span>{{ $item->qty }} {{ $item->uom->uomName ?? '-' }} x {{ number_format($item->price_cents, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <hr>

        <table class="totals-table">
            <tr class="totals-row">
                <td class="label">Subtotal</td>
                <td class="value">{{ number_format($sale->subtotal_cents, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-row">
                <td class="label">Diskon</td>
                <td class="value">(-)</td>
            </tr>
            <tr class="totals-row" style="font-size: 14px;">
                <td class="label">Total</td>
                <td class="value">{{ number_format($sale->total_cents, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-row">
                <td class="label">Bayar</td>
                <td class="value">{{ number_format($sale->paid_cents, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-row">
                <td class="label">Kembali</td>
                <td class="value">{{ number_format($sale->change_cents, 0, ',', '.') }}</td>
            </tr>
        </table>

        <hr>

        <div class="footer">
            <p>Terima kasih telah berbelanja 🙏</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p>Kritik dan Saran: 0812 2604 8447 </p>
            <p>No Telepon: 0271743333</p>
        </div>
    </div>
</body>
</html>