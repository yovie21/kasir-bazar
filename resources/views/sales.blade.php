@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Sales Management</h2>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Data Sales --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Sales</div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <label>Cashier ID</label>
                        <input type="number" name="cashier_id" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>No Trans</label>
                        <input type="text" name="no_trans" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Subtotal (cents)</label>
                        <input type="number" name="subtotal_cents" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Discount (cents)</label>
                        <input type="number" name="discount_cents" class="form-control" value="0">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Total (cents)</label>
                        <input type="number" name="total_cents" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Paid (cents)</label>
                        <input type="number" name="paid_cents" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Change (cents)</label>
                        <input type="number" name="change_cents" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Sales --}}
    <div class="card">
        <div class="card-header">Daftar Sales</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cashier ID</th>
                        <th>No Trans</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Change</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->cashier_id }}</td>
                            <td>{{ $sale->no_trans }}</td>
                            <td>{{ $sale->subtotal_cents }}</td>
                            <td>{{ $sale->discount_cents }}</td>
                            <td>{{ $sale->total_cents }}</td>
                            <td>{{ $sale->paid_cents }}</td>
                            <td>{{ $sale->change_cents }}</td>
                            <td>{{ $sale->created_at }}</td>
                            <td>
                                {{-- Tombol Edit --}}
                                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data sales</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">🛒 Penjualan Kasir</h2>

    <!-- Input Scan Barcode -->
    <div class="card p-3 mb-3 shadow-sm">
        <label for="barcodeInput" class="form-label">Scan / Input Barcode</label>
        <input type="text" id="barcodeInput" class="form-control form-control-lg" placeholder="Scan barcode di sini...">
    </div>

    <!-- Tabel Keranjang -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Keranjang Belanja</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="cartTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Barcode</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th width="100px">Jumlah</th>
                            <th>Subtotal</th>
                            <th width="60px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h4>Total: <span class="badge bg-success fs-5">Rp <span id="grandTotal">0</span></span></h4>
                <button class="btn btn-danger btn-sm" onclick="clearCart()">Kosongkan</button>
            </div>
        </div>
    </div>

    <!-- Bayar -->
    <div class="card p-3 mt-4 shadow-sm">
        <label for="bayar" class="form-label">Uang Bayar</label>
        <input type="number" id="bayar" class="form-control form-control-lg mb-3" placeholder="Masukkan uang pembayaran">

        <button class="btn btn-success btn-lg w-100" id="btnCheckout">
            💾 Simpan & Bayar
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
let cart = [];

function renderCart() {
    let tbody = $("#cartTable tbody");
    tbody.empty();
    let total = 0;

    cart.forEach((item, index) => {
        total += item.subtotal;
        tbody.append(`
            <tr>
                <td>${item.barcode}</td>
                <td>${item.nama}</td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>
                    <input type="number" min="1" value="${item.jumlah}" 
                        onchange="updateQty(${index}, this.value)" 
                        class="form-control form-control-sm text-center">
                </td>
                <td>Rp ${item.subtotal.toLocaleString()}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">🗑</button>
                </td>
            </tr>
        `);
    });

    $("#grandTotal").text(total.toLocaleString());
}

function updateQty(index, qty) {
    cart[index].jumlah = parseInt(qty);
    cart[index].subtotal = cart[index].jumlah * cart[index].harga;
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

$("#barcodeInput").keypress(function(e) {
    if(e.which == 13) {
        let barcode = $(this).val();
        $.post("{{ route('penjualan.addItem') }}", { 
            _token: "{{ csrf_token() }}", 
            barcode: barcode 
        }, function(data) {
            let existing = cart.findIndex(item => item.id === data.id);
            if (existing >= 0) {
                cart[existing].jumlah += 1;
                cart[existing].subtotal = cart[existing].jumlah * cart[existing].harga;
            } else {
                cart.push(data);
            }
            renderCart();
            $("#barcodeInput").val('');
        }).fail(function(xhr){
            alert(xhr.responseJSON.error);
        });
    }
});

$("#btnCheckout").click(function(){
    let bayar = $("#bayar").val();
    if(cart.length === 0){
        alert("Keranjang masih kosong!");
        return;
    }
    if(bayar == "" || bayar <= 0){
        alert("Masukkan jumlah uang bayar!");
        return;
    }

    $.post("{{ route('penjualan.store') }}", { 
    _token: "{{ csrf_token() }}", 
    items: cart,
    bayar: bayar
}, function(res) {
    alert("✅ Transaksi Berhasil!\nKembalian: Rp " + res.kembalian.toLocaleString());
    cart = [];
    renderCart();
    $("#bayar").val('');
});
});
</script>
@endsection
