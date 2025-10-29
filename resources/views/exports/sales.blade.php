<table border="1">
    <thead style="background-color:#e0e0e0; font-weight:bold; text-align:center;">
        <tr>
            <th>No. Transaksi</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>UOM</th>
            <th>Harga</th>
            <th>Subtotal</th>
            <th>Diskon</th>
            <th>Total Transaksi</th>
            <th>Dibayar</th>
            <th>Kembalian</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $grandTotal = 0;
            $alternate = false;
        @endphp

        @foreach($sales as $sale)
            @php
                $itemCount = $sale->items->count();
                // Warna selang-seling antar transaksi
                $rowColor = $alternate ? '#f9f9f9' : '#ffffff';
                $alternate = !$alternate;
            @endphp
           
            @foreach($sale->items as $index => $item)
                <tr style="background-color:{{ $rowColor }}; text-align:left;">
                    @if($loop->first)
                        {{-- Kolom gabungan hanya di baris pertama tiap transaksi --}}
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top;">{{ $sale->no_trans }}</td>
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top;">{{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}</td>
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top;">{{ $sale->cashier->name ?? '-' }}</td>
                    @endif

                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ $item->qty }}</td>
                    <td>{{ $item->uom->uomName ?? '-' }}</td>
                    <td style="text-align:right;">{{ $item->price_cents }}</td>
                    <td style="text-align:right;">{{ $item->subtotal_cents }}</td>

                    @if($loop->first)
                        {{-- Kolom gabungan hanya di baris pertama --}}
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top; text-align:right;">{{ $sale->discount_cents }}</td>
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top; text-align:right;">{{ $sale->total_cents }}</td>
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top; text-align:right;">{{ $sale->paid_cents }}</td>
                        <td rowspan="{{ $itemCount }}" style="vertical-align:top; text-align:right;">{{ $sale->change_cents }}</td>

                        @php $grandTotal += $sale->total_cents; @endphp
                    @endif
                </tr>
            @endforeach

            {{-- Garis pemisah antar transaksi --}}
            <tr>
                <td colspan="12" style="background-color:#dfe6e9; height: 3px; border: none;"></td>
            </tr>
        @endforeach

        {{-- Total Keseluruhan --}}
        <tr style="font-weight:bold; background-color:#d9edf7;">
            <td colspan="9" style="text-align:right;">TOTAL PENJUALAN</td>
            <td colspan="3" style="text-align:right;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
