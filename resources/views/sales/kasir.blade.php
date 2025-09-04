@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <!-- Area Scan Barang -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Scan / Input Barang</h5>
                </div>
                <div class="card-body">
                    <!-- PENTING: Hapus action dan method dari form -->
                    <form id="formScan">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" id="barcode" name="barcode" class="form-control form-control-lg" placeholder="Scan Barcode / Ketik Manual" autofocus autocomplete="off">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upc-scan"></i> Tambah
                            </button>
                        </div>
                    </form>
                    <div id="alertBox"></div>
                    
                    <!-- Debug info -->
                    <div id="debugInfo" class="mt-2 small text-muted"></div>
                </div>
            </div>
        </div>

        <!-- Area Keranjang -->
        <div class="col-md-8">
            <div class="card shadow-lg rounded-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Keranjang Belanja</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered align-middle" id="cartTable">
                        <thead class="table-secondary">
                            <tr>
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
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th id="totalBelanja">0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Area Pembayaran -->
            <div class="card shadow-lg rounded-3 mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form id="formCheckout">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Total</label>
                            <div class="col-md-4">
                                <input type="text" id="totalInput" class="form-control form-control-lg" readonly>
                            </div>
                            <label class="col-md-2 col-form-label">Bayar</label>
                            <div class="col-md-4">
                                <input type="number" id="bayarInput" class="form-control form-control-lg" placeholder="Jumlah Bayar" required>
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

            <!-- Info Kembalian -->
            <div id="kembalianBox" class="alert alert-info mt-3 d-none"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Pastikan jQuery loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
// Test jQuery dan debugging
$(document).ready(function() {
    console.log('=== KASIR PAGE LOADED ===');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Form exists:', $('#formScan').length > 0);
    console.log('Barcode input exists:', $('#barcode').length > 0);
    
    // Update debug info
    $('#debugInfo').html('jQuery: ' + $.fn.jquery + ' | Form: ' + ($('#formScan').length > 0 ? 'OK' : 'ERROR'));
});

let cart = [];
let isProcessing = false; // Prevent double submission

function updateCart() {
    let tbody = $("#cartTable tbody");
    tbody.empty();
    let total = 0;

    cart.forEach((item, index) => {
        let uomOptions = item.uoms.map(u =>
            `<option value="${u.uom_id}" ${u.uom_id == item.uom_id ? 'selected' : ''}>
                ${u.uom_name} - Rp ${parseFloat(u.harga).toLocaleString('id-ID')}
            </option>`
        ).join("");

        let row = `
            <tr>
                <td>${item.barcode}</td>
                <td>${item.nama}</td>
                <td>
                    <select class="form-select form-select-sm uomSelect" data-index="${index}">
                        ${uomOptions}
                    </select>
                </td>
                <td>Rp ${parseFloat(item.harga).toLocaleString('id-ID')}</td>
                <td>
                    <input type="number" min="1" value="${item.jumlah}" 
                        class="form-control form-control-sm qtyInput" data-index="${index}">
                </td>
                <td>Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm removeItem" data-index="${index}"><i class="bi bi-trash"></i></button></td>
            </tr>`;
        tbody.append(row);
        total += parseFloat(item.subtotal);
    });

    $("#totalBelanja").text('Rp ' + total.toLocaleString('id-ID'));
    $("#totalInput").val(total.toLocaleString('id-ID'));
}

// Ganti UOM
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


// CRITICAL: Scan / Tambah Barang dengan event delegation
$(document).on('submit', '#formScan', function(e) {
    e.preventDefault();
    console.log('=== FORM SUBMITTED ===');
    
    if (isProcessing) {
        console.log('Already processing, skipping...');
        return false;
    }
    
    let barcode = $("#barcode").val().trim();
    console.log('Barcode entered:', barcode);
    
    if (!barcode) {
        $("#alertBox").html(`<div class="alert alert-warning">Masukkan barcode terlebih dahulu!</div>`);
        return false;
    }

    isProcessing = true;
    $("#alertBox").html(`<div class="alert alert-info">Mencari produk...</div>`);

    const ajaxUrl = "{{ route('kasir.addItem') }}";
    const csrfToken = "{{ csrf_token() }}";
    
    console.log('AJAX URL:', ajaxUrl);
    console.log('CSRF Token:', csrfToken);

    $.ajax({
        url: ajaxUrl,
        type: "POST",
        data: {
            _token: csrfToken,
            barcode: barcode
        },
        timeout: 10000, // 10 second timeout
        beforeSend: function(xhr) {
            console.log('AJAX Request starting...');
        },
        success: function(res, status, xhr) {
            console.log('=== AJAX SUCCESS ===');
            console.log('Response:', res);
            
            if (!res || typeof res !== 'object') {
                throw new Error('Invalid response format');
            }
            
            if (!res.id || !res.barcode || !res.nama || res.harga === undefined) {
                throw new Error('Missing required fields in response');
            }
            
            // Convert harga to number
            res.harga = parseFloat(res.harga);
            res.jumlah = parseInt(res.jumlah) || 1;
            res.subtotal = res.harga * res.jumlah;
            
            // Check if item already exists
            let existingItemIndex = cart.findIndex(item => item.barcode === res.barcode);
            
            if (existingItemIndex >= 0) {
                cart[existingItemIndex].jumlah++;
                cart[existingItemIndex].subtotal = cart[existingItemIndex].jumlah * cart[existingItemIndex].harga;
            } else {
                cart.push(res);
            }
            
            updateCart();
            $("#barcode").val("").focus();
            $("#alertBox").html(`<div class="alert alert-success">Produk "${res.nama}" berhasil ditambahkan!</div>`);
            
            setTimeout(function() {
                $("#alertBox").html("");
            }, 3000);
        },
        error: function(xhr, status, error) {
            console.log('=== AJAX ERROR ===');
            console.log('XHR:', xhr);
            console.log('Status:', status);
            console.log('Error:', error);
            
            let errorMsg = 'Terjadi kesalahan';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            } else if (xhr.status === 404) {
                errorMsg = 'Endpoint tidak ditemukan - periksa route!';
            } else if (xhr.status === 0) {
                errorMsg = 'Tidak dapat terhubung ke server';
            } else {
                errorMsg = `Error ${xhr.status}: ${error}`;
            }
            
            $("#alertBox").html(`<div class="alert alert-danger">${errorMsg}</div>`);
        },
        complete: function() {
            isProcessing = false;
            console.log('AJAX Request completed');
        }
    });
    
    return false; // Prevent default form submission
});

// Ubah Qty
$(document).on("change", ".qtyInput", function(){
    let index = $(this).data("index");
    let qty = parseInt($(this).val()) || 1;
    
    if (qty < 1) {
        $(this).val(1);
        qty = 1;
    }
    
    cart[index].jumlah = qty;
    cart[index].subtotal = qty * parseFloat(cart[index].harga);
    updateCart();
});

// Hapus Item dengan konfirmasi SweetAlert
$(document).on("click", ".removeItem", function(e){
    e.preventDefault();
    let index = $(this).data("index");
    let itemName = cart[index].nama;

    Swal.fire({
        title: 'Hapus Item?',
        text: `Apakah kamu yakin ingin menghapus "${itemName}" dari keranjang?`,
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
                text: `"${itemName}" berhasil dihapus dari keranjang.`,
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});

// Checkout
$(document).on('submit', '#formCheckout', function(e){
    e.preventDefault();

    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    let bayar = parseFloat($("#bayarInput").val()) || 0;
    let total = cart.reduce((sum, item) => sum + parseFloat(item.subtotal), 0);

    if (bayar < total) {
        alert("Uang pembayaran kurang!");
        return;
    }

    $.ajax({
        url: "{{ route('kasir.checkout') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            items: cart,
            bayar: bayar
        },
        success: function(res) {
            $("#kembalianBox").removeClass("d-none").html(
                `<strong>Kembalian: Rp ${parseFloat(res.kembalian).toLocaleString('id-ID')}</strong>`
            );
            cart = [];
            updateCart();
            $("#bayarInput").val("");
            
            if (res.sale_id) {
                window.open("/kasir/receipt/" + res.sale_id, "_blank");
            }
        },
        error: function(xhr) {
            console.log('Checkout error:', xhr);
            alert('Terjadi kesalahan saat memproses pembayaran');
        }
    });
});
</script>
@endsection