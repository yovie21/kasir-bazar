@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Area Scan Barang -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Scan / Input Barang</h5>
                </div>
                <div class="card-body">
                    <form id="formScan">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" id="barcode" name="barcode" 
                                   class="form-control form-control-lg" 
                                   placeholder="Scan Barcode / Ketik Manual" autofocus autocomplete="off">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upc-scan"></i> Tambah
                            </button>
                        </div>
                    </form>
                    <div id="alertBox"></div>
                    <div id="debugInfo" class="mt-2 small text-muted"></div>
                </div>
            </div>
        </div>

        <!-- Area Keranjang -->
        <div class="col-md-8 d-flex flex-column mb-5">
            <div class="card shadow-lg rounded-3 flex-grow-1">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Keranjang Belanja</h5>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <!-- Scrollable Table -->
                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                        <table class="table table-hover table-bordered align-middle mb-0" id="cartTable">
                            <thead class="table-secondary sticky-top bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Barcode</th>
                                    <th>Nama</th>
                                    <th>UOM</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- FIXED footer total belanja -->
                <div class="card-footer bg-dark text-white d-flex justify-content-between">
                    <strong>Total</strong>
                    <span id="totalBelanja">Rp 0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Area Pembayaran -->
<div class="container-fluid mb-5"> 
    <div id="paymentBox" class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Pembayaran</h5>
        </div>
        <div class="card-body">
            <form id="formCheckout">
                @csrf
                <div class="row mb-3 align-items-center">
                    <!-- Total -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Total</label>
                        <input type="text" id="totalInput" class="form-control form-control-lg" readonly>
                    </div>

                    <!-- Bayar -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Bayar</label>
                        <input type="text" id="bayarInput" 
                               class="form-control form-control-lg" 
                               placeholder="Jumlah Bayar" required>
                    </div>

                    <!-- Kembalian -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kembalian</label>
                        <input type="text" id="kembalianInput" class="form-control form-control-lg" readonly>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-lg btn-success">
                        <i class="bi bi-cash-stack"></i> Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts') 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#debugInfo').html('jQuery: ' + $.fn.jquery + ' | Form: ' + ($('#formScan').length > 0 ? 'OK' : 'ERROR'));
});

let cart = [];
let isProcessing = false;

/**
 * Format angka ke Rupiah
 */
function formatRupiah(value) {
    let number = parseFloat(value) || 0;
    return "Rp " + number.toLocaleString("id-ID");
}

/**
 * Update isi keranjang & hitungan total
 */
function updateCart() {
    let tbody = $("#cartTable tbody");
    tbody.empty();
    let total = 0;

    cart.forEach((item, index) => {
        let uomOptions = item.uoms.map(u =>
            `<option value="${u.uom_id}" ${u.uom_id == item.uom_id ? 'selected' : ''}>
                ${u.uom_name} - ${formatRupiah(u.harga)}
            </option>`
        ).join("");

        let row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.barcode}</td>
                <td>${item.nama}</td>
                <td>
                    <select class="form-select form-select-sm uomSelect" data-index="${index}">
                        ${uomOptions}
                    </select>
                </td>
                <td>${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" min="1" value="${item.jumlah}" 
                        class="form-control form-control-sm qtyInput" data-index="${index}">
                </td>
                <td>${formatRupiah(item.subtotal)}</td>
                <td>
                    <button class="btn btn-danger btn-sm removeItem" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
        tbody.append(row);
        total += parseFloat(item.subtotal);
    });

    // Update total
    $("#totalBelanja").text(formatRupiah(total));
    $("#totalInput").val(formatRupiah(total));

    // Hitung kembalian realtime
    let bayar = parseInt($("#bayarInput").val().replace(/\D/g,'')) || 0;
    let kembalian = bayar - total;

    if (kembalian < 0) {
        $("#kembalianInput").val("-" + formatRupiah(Math.abs(kembalian)));
    } else {
        $("#kembalianInput").val(formatRupiah(kembalian));
    }
}

/**
 * Ganti UOM
 */
$(document).on("change", ".uomSelect", function(){
    let index = $(this).data("index");
    let uomId = $(this).val();
    let selected = cart[index].uoms.find(u => u.uom_id == uomId);

    if (selected) {
        cart[index].uom_id = selected.uom_id;
        cart[index].uom = selected.uom_name;
        cart[index].harga = parseFloat(selected.harga);
        cart[index].subtotal = cart[index].harga * cart[index].jumlah;
        updateCart();
    }
});

/**
 * Tambah barang (scan)
 */
$(document).on('submit', '#formScan', function(e) {
    e.preventDefault();
    let barcode = $("#barcode").val().trim();
    if (!barcode) return;

    isProcessing = true;
    $("#alertBox").html(`<div class="alert alert-info">Mencari produk...</div>`);

    $.ajax({
        url: "{{ route('kasir.addItem') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", barcode: barcode },
        success: function(res) {
            res.harga = parseFloat(res.harga);
            res.jumlah = parseInt(res.jumlah) || 1;
            res.subtotal = res.harga * res.jumlah;

            let existingIndex = cart.findIndex(i => i.barcode === res.barcode);
            if (existingIndex >= 0) {
                cart[existingIndex].jumlah++;
                cart[existingIndex].subtotal = cart[existingIndex].jumlah * cart[existingIndex].harga;
            } else {
                cart.push(res);
            }
            updateCart();
            $("#barcode").val("").focus();
            $("#alertBox").html(`<div class="alert alert-success">Produk "${res.nama}" ditambahkan!</div>`);
            setTimeout(() => $("#alertBox").html(""), 2000);
        },
        error: function() {
            $("#alertBox").html(`<div class="alert alert-danger">Produk tidak ditemukan!</div>`);
        },
        complete: function() { isProcessing = false; }
    });
});

/**
 * Qty berubah
 */
$(document).on("change", ".qtyInput", function(){
    let index = $(this).data("index");
    let qty = parseInt($(this).val()) || 1;
    if (qty < 1) qty = 1;
    cart[index].jumlah = qty;
    cart[index].subtotal = qty * parseFloat(cart[index].harga);
    updateCart();
});

/**
 * Hapus item dengan SweetAlert konfirmasi
 */
$(document).on("click", ".removeItem", function(e){
    e.preventDefault();
    let index = $(this).data("index");

    Swal.fire({
        title: 'Hapus Item?',
        text: "Item akan dihapus dari keranjang!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            cart.splice(index, 1);
            updateCart();

            Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Item berhasil dihapus dari keranjang.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});

/**
 * Hitung kembalian realtime saat input bayar
 */
$(document).on("input", "#bayarInput", function(){
    // Format input ke rupiah saat diketik
    let raw = $(this).val().replace(/\D/g,'');
    let formatted = new Intl.NumberFormat('id-ID').format(raw);
    $(this).val(formatted);
    updateCart();
});

/**
 * Checkout
 */
$(document).on('submit', '#formCheckout', function(e){
    e.preventDefault();
    if (cart.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Tidak ada item di keranjang!' });
        return;
    }

    let bayar = parseInt($("#bayarInput").val().replace(/\D/g,'')) || 0;
    let total = cart.reduce((s, i) => s + parseFloat(i.subtotal), 0);

    if (bayar < total) {
        Swal.fire({
            icon: 'error',
            title: 'Pembayaran Kurang!',
            text: `Total belanja ${formatRupiah(total)} 
                   sedangkan uang bayar ${formatRupiah(bayar)}.`
        });
        return;
    }

    $.ajax({
        url: "{{ route('kasir.checkout') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", items: cart, bayar: bayar },
        success: function(res) {
            $("#kembalianBox").removeClass("d-none").html(
                `<strong>Kembalian: ${formatRupiah(res.kembalian)}</strong>`
            );
            $("#kembalianInput").val(formatRupiah(res.kembalian));
            cart = [];
            updateCart();
            $("#bayarInput").val("");

            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: `Transaksi selesai. Kembalian ${formatRupiah(res.kembalian)}.`,
                timer: 2000,
                showConfirmButton: false
            });

            if (res.sale_id) window.open("/kasir/receipt/" + res.sale_id, "_blank");
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat memproses pembayaran.' });
        }
    });
});
</script>
@endsection

