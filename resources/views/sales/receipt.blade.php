{{-- resources/views/sales/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace, sans-serif;
            font-size: 14px;
        }
        .receipt {
            width: 300px;
            margin: auto;
            padding: 10px;
            border: 1px dashed #000;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table th, table td {
            padding: 4px;
            text-align: left;
        }
        table td:last-child, table th:last-child {
            text-align: right;
        }
        .totals td {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <h2>Assalaam Hypermarket</h2>
        <h4>Struk Belanja</h4>
        <hr>

        <p>
            <strong>No. Transaksi:</strong> {{ $sale->no_trans }} <br>
            <strong>Tanggal:</strong> {{ $sale->created_at->format('d/m/Y H:i') }} <br>
            <strong>Kasir:</strong> {{ $sale->cashier->name ?? 'Admin' }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price_cents, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->qty * $item->price_cents, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="totals">
                    <td colspan="3">Subtotal</td>
                    <td>{{ number_format($sale->subtotal_cents, 0, ',', '.') }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Total</td>
                    <td>{{ number_format($sale->total_cents, 0, ',', '.') }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Bayar</td>
                    <td>{{ number_format($sale->paid_cents, 0, ',', '.') }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Kembali</td>
                    <td>{{ number_format($sale->change_cents, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Terima kasih telah berbelanja 🙏</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>
</body>
</html>
