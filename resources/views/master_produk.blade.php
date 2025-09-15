@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Existing styles */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    .was-validated .form-control:invalid ~ .invalid-feedback {
        display: block;
    }

    /* New enhanced styles */
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .table thead th {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
    }
    .btn-warning {
        background: linear-gradient(45deg, #ffc107, #ff9800);
        border: none;
    }
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .uom-price-row {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
        animation: slideIn 0.3s ease-out;
    }
    .uom-price-row:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .modal-header {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-radius: 15px 15px 0 0;
    }
    .modal-footer {
        background: #f8f9fa;
        border-radius: 0 0 15px 15px;
    }

    /* Price display styling */
    .price-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 500;
    }
    .conversion-badge {
        background: linear-gradient(45deg, #17a2b8, #0dcaf0);
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
    }

    /* Animations */
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
/* Tambahan khusus tabel */
    .table-responsive {
        max-height: 500px;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #fafafa;
    }
    .table tbody tr:hover {
        background-color: #f1f5f9;
        transform: scale(1.01);
    }

    /* Badge harga UOM */
    .price-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    .price-wrapper .badge {
        font-size: 0.75rem;
        padding: 5px 8px;
        border-radius: 6px;
    }

    /* Tombol aksi */
    .btn-group .btn {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Pagination lebih nyaman di mobile */
    .pagination {
        justify-content: center;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    
</style>
@endsection

@section('content')
<!-- Header -->
<div class="container-fluid px-3">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-boxes me-2"></i> Master Produk
        </h2>

        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" 
                       name="search" 
                       class="form-control form-control-sm me-2" 
                       placeholder="Cari produk..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- Tombol Tambah -->        
            <button id="btnAddProduct" type="button"  
                class="btn btn-sm text-white shadow-sm px-3 py-2"
                style="background: linear-gradient(45deg, #54a567, #20c997); border-radius: 8px;"
                data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk
            </button>
        </div>
    </div>
</div>

<!-- Tabel Produk -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>No</th>
                        <th>Barcode</th>
                        <th>PLU</th>
                        <th>Nama Produk</th>
                        <th>UOM Base</th>
                        <th>Harga per UOM</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr class="fade-in">
                            <td class="fw-bold">{{ $products->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary">{{ $product->barcode }}</span></td>
                            <td>{{ $product->sku }}</td>
                            <td class="fw-semibold text-start">{{ $product->name }}</td>
                            <td><span class="badge bg-dark">{{ $product->uom_name }}</span></td>

                            <!-- Harga per UOM -->
                            <td class="text-start">
                                <div class="d-flex flex-column gap-1">
                                    @forelse($product->uomPrices as $uomPrice)
                                        <div class="d-flex align-items-center gap-2 p-1 rounded bg-light shadow-sm">
                                            <!-- UOM -->
                                            <span class="badge bg-warning text-dark">
                                                {{ optional($uomPrice->uom)->uomName ?? '-' }}
                                            </span>

                                            <!-- Harga -->
                                            <span class="badge bg-success">
                                                Rp {{ number_format($uomPrice->price_cents, 0, ',', '.') }}
                                            </span>

                                            <!-- Konversi -->
                                            @if($uomPrice->konv_to_base != 1)
                                                <span class="badge bg-info text-dark">
                                                    = {{ $uomPrice->konv_to_base }} {{ $product->uom_name }}
                                                </span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="badge bg-light text-muted">Belum ada harga</span>
                                    @endforelse
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-success">{{ $product->stock_warehouse }}</span>
                            </td>

                            <td>
                                <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning btnEdit"
                                            data-bs-toggle="modal" data-bs-target="#productModal"
                                            title="Edit Produk"
                                            data-id="{{ $product->id }}"
                                            data-barcode="{{ $product->barcode }}"
                                            data-sku="{{ $product->sku }}"
                                            data-name="{{ $product->name }}"
                                            data-uomid="{{ $product->uomId }}"
                                            data-stock="{{ $product->stock_warehouse }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" 
                                          method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger btn-delete"
                                                title="Hapus Produk">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-muted">
                                <i class="bi bi-inbox fs-3"></i><br>
                                Tidak ada data produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="productForm" method="POST" action="" novalidate>
            @csrf
            <input type="hidden" name="_method" id="form_method">
            <input type="hidden" name="id" id="product_id">
            
            <div class="modal-content shadow-lg border-0 rounded-3 animate__animated animate__fadeInDown">
                <div class="modal-header bg-gradient text-white rounded-top-3" 
                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <h5 class="modal-title fw-bold" id="productModalLabel">
                        <i class="bi bi-box-seam me-2"></i>Tambah Produk
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-upc-scan me-1"></i> Barcode</label>
                                <input type="text" class="form-control rounded-2" name="barcode" id="barcode" required>
                                <div class="invalid-feedback">Barcode harus diisi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-tag me-1"></i> PLU</label>
                                <input type="text" class="form-control rounded-2" name="sku" id="sku" required>
                                <div class="invalid-feedback">PLU harus diisi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-box me-1"></i> Nama Produk</label>
                                <input type="text" class="form-control rounded-2" name="name" id="name" required>
                                <div class="invalid-feedback">Nama produk harus diisi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-diagram-2 me-1"></i> UOM Base</label>
                                <select class="form-select rounded-2" name="uomId" id="uomId" required>
                                    <option value="">Pilih UOM</option>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->uomId }}">{{ $uom->uomName }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">UOM harus dipilih</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-boxes me-1"></i> Stok Gudang</label>
                                <input type="number" class="form-control rounded-2" name="stock_warehouse" 
                                       id="stock_warehouse" min="0" required>
                                <div class="invalid-feedback">Stok harus diisi dengan angka positif</div>
                            </div>
                        </div>

                        <!-- Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-cash-stack me-1"></i> Harga per UOM</label>
                                <div id="uomPricesContainer">
                                    <div class="row mb-2 uom-price-row align-items-center">
                                        <div class="col-4">
                                            <select class="form-select rounded-2 uom-select" name="uom_prices[0][uom_id]" required>
                                                <option value="">Pilih UOM</option>
                                                @foreach($uoms as $uom)
                                                    <option value="{{ $uom->uomId }}">{{ $uom->uomName }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">UOM harus dipilih</div>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control rounded-2 price-input" 
                                                   name="uom_prices[0][price_cents]"
                                                   placeholder="Harga" min="1" required>
                                            <div class="invalid-feedback">Harga harus lebih dari 0</div>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" class="form-control rounded-2 conversion-input" 
                                                   name="uom_prices[0][konv_to_base]"
                                                   placeholder="Konversi" value="1" 
                                                   min="0.001" step="0.001" required>
                                            <div class="invalid-feedback">Konversi harus lebih dari 0</div>
                                        </div>
                                        <div class="col-1 text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-uom-price">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addUomPrice">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Harga UOM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save2 me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tambahkan library icon Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    const methodInput = document.getElementById('form_method');
    const uomPricesContainer = document.getElementById('uomPricesContainer');
    let uomPriceIndex = 0;

    // Reset form
    function clearForm() {
        form.reset();
        document.getElementById('product_id').value = '';
        methodInput.value = '';
        methodInput.removeAttribute('name');
        document.getElementById('productModalLabel').innerText = 'Tambah Produk';
        form.setAttribute('action', "{{ route('products.store') }}");

        // Reset UOM prices
        [...uomPricesContainer.children].slice(1).forEach(row => row.remove());
        resetUomPriceIndexes();

        // Reset validation
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // Tambah UOM row
    function addUomPriceRow(data = null) {
        uomPriceIndex++;
        const template = uomPricesContainer.children[0].cloneNode(true);

        template.querySelectorAll('input, select').forEach(input => {
            input.value = '';
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${uomPriceIndex}]`);
            }
            input.classList.remove('is-invalid');
        });

        if (data) {
            template.querySelector('.uom-select').value = data.uom_id;
            template.querySelector('.price-input').value = data.price_cents;
            template.querySelector('.conversion-input').value = data.konv_to_base;
        } else {
            template.querySelector('.conversion-input').value = '1';
        }

        uomPricesContainer.appendChild(template);
    }

    // Reset index semua row
    function resetUomPriceIndexes() {
        [...uomPricesContainer.children].forEach((row, index) => {
            row.querySelectorAll('[name*="uom_prices"]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            });
        });
        uomPriceIndex = uomPricesContainer.children.length - 1;
    }

    // Edit produk
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btnEdit');
        if (!btn) return;

        try {
            const data = btn.dataset;
            showLoading();

            // Set form action untuk update
            form.setAttribute('action', `/products/${data.id}`);
            methodInput.setAttribute('name', '_method');
            methodInput.value = 'PUT';

            // Isi data produk
            document.getElementById('productModalLabel').innerText = 'Edit Produk';
            document.getElementById('product_id').value = data.id;
            document.getElementById('barcode').value = data.barcode;
            document.getElementById('sku').value = data.sku;
            document.getElementById('name').value = data.name;
            document.getElementById('uomId').value = data.uomid;
            document.getElementById('stock_warehouse').value = data.stock;

            // Ambil harga UOM
            const response = await fetch(`/products/${data.id}/uom-prices`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const prices = await response.json();

            // Reset container
            [...uomPricesContainer.children].slice(1).forEach(row => row.remove());
            const firstRow = uomPricesContainer.children[0];
            firstRow.querySelector('.uom-select').value = '';
            firstRow.querySelector('.price-input').value = '';
            firstRow.querySelector('.conversion-input').value = '1';

            // Isi harga
            prices.forEach((price, index) => {
                if (index === 0) {
                    firstRow.querySelector('.uom-select').value = price.uom_id;
                    firstRow.querySelector('.price-input').value = price.price_cents;
                    firstRow.querySelector('.conversion-input').value = price.konv_to_base;
                } else {
                    addUomPriceRow(price);
                }
            });

            Swal.close();
            form.classList.remove('was-validated');
        } catch (error) {
            console.error(error);
            showError('Gagal mengambil data harga UOM. Silakan coba lagi.');
        }
    });

    // Event tombol
    document.getElementById('btnAddProduct').addEventListener('click', clearForm);
    document.getElementById('addUomPrice').addEventListener('click', () => addUomPriceRow());

    // Hapus row UOM
    uomPricesContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-uom-price');
        if (btn && uomPricesContainer.children.length > 1) {
            btn.closest('.uom-price-row').remove();
            resetUomPriceIndexes();
        }
    });

    // Validasi form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) this.submit();
    });

    function validateForm() {
        let isValid = true;
        form.classList.add('was-validated');

        // Cek required
        form.querySelectorAll('[required]').forEach(input => {
            if (!input.value) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Pastikan ada base UOM
        const baseUomId = document.getElementById('uomId').value;
        const uomSelects = form.querySelectorAll('.uom-select');
        const hasBaseUom = [...uomSelects].some(select => select.value === baseUomId);
        if (!hasBaseUom) {
            showError('Harus ada harga untuk UOM Base yang dipilih!');
            isValid = false;
        }

        // Cek duplikat
        const selectedUoms = new Set();
        for (const select of uomSelects) {
            if (selectedUoms.has(select.value)) {
                showError('UOM tidak boleh duplikat!');
                select.classList.add('is-invalid');
                isValid = false;
            }
            selectedUoms.add(select.value);
        }

        return isValid;
    }

    // Konfirmasi hapus
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        e.preventDefault();
        const formDelete = btn.closest('form');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data produk akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) formDelete.submit();
        });
    });

    // Notifikasi flash
    @if(session('success'))
        showSuccess("{{ session('success') }}");
    @endif

    @if($errors->any())
        showError('{!! implode("<br>", $errors->all()) !!}');
    @endif
});

// Helper SweetAlert
const showLoading = () => {
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
};

const showSuccess = (message) => {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
};

const showError = (message) => {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });
};
</script>
@endsection
