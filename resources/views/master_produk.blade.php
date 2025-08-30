{{-- resources/views/master_produk.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Master Products</h2>

    <!-- Tombol Tambah -->
    <button id="btnAddProduct" type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
        + Tambah Produk
    </button>

    <!-- Pencarian -->
    <form method="GET" action="{{ route('products.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2"
               placeholder="Cari nama, SKU, barcode, atau UOM..."
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    <!-- Tabel Produk -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>UOM</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td>{{ $product->barcode }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->uom->uomName ?? '-' }}</td>
                                <td>Rp {{ number_format($product->price_cents, 0, ',', '.') }}</td>
                                <td>{{ $product->stock_warehouse }}</td>
                                <td>
                                    <!-- Tombol Edit & Hapus rapi sejajar -->
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <!-- Edit -->
                                        <button type="button"
                                                class="btn btn-sm btn-warning btnEdit"
                                                data-bs-toggle="modal" data-bs-target="#productModal"
                                                data-id="{{ $product->id }}"
                                                data-barcode="{{ $product->barcode }}"
                                                data-sku="{{ $product->sku }}"
                                                data-name="{{ $product->name }}"
                                                data-uomid="{{ $product->uomId }}"
                                                data-price="{{ $product->price_cents }}"
                                                data-stock="{{ $product->stock_warehouse }}">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>

                                        <!-- Delete -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="m-0 p-0 d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                <div class="overflow-auto pagination-wrapper">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="productForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" id="form_method">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="productModalLabel" class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="product_id">

                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" id="sku" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">UOM (Satuan)</label>
                        <select name="uomId" id="uomId" class="form-select" required>
                            <option value="">-- Pilih UOM --</option>
                            @foreach($uoms as $uom)
                                <option value="{{ $uom->uomId }}">{{ $uom->uomName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price_cents" id="price_cents" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Gudang</label>
                        <input type="number" name="stock_warehouse" id="stock_warehouse" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const qs = s => document.querySelector(s);
    const form = qs('#productForm');
    const methodInput = qs('#form_method');

    // Reset modal
    function clearForm() {
        form.reset();
        qs('#product_id').value = '';
        methodInput.value = '';
        methodInput.removeAttribute('name');
        qs('#productModalLabel').innerText = 'Tambah Produk';
        form.setAttribute('action', "{{ route('products.store') }}");
    }

    // Isi modal saat edit
    function fillForm(data) {
        qs('#product_id').value = data.id || '';
        qs('#barcode').value = data.barcode || '';
        qs('#sku').value = data.sku || '';
        qs('#name').value = data.name || '';
        qs('#uomId').value = data.uomid || '';
        qs('#price_cents').value = data.price || '';
        qs('#stock_warehouse').value = data.stock || '';

        qs('#productModalLabel').innerText = 'Edit Produk';
        form.setAttribute('action', `/products/${data.id}`);
        methodInput.setAttribute('name', '_method');
        methodInput.value = 'PUT';
    }

    qs('#btnAddProduct')?.addEventListener('click', clearForm);

    // Tombol edit
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btnEdit');
        if (!btn) return;
        fillForm(btn.dataset);
    });

    // Tombol hapus pakai SweetAlert
    document.addEventListener('click', function (e) {
        const delBtn = e.target.closest('.btn-delete');
        if (!delBtn) return;

        e.preventDefault();
        const formEl = delBtn.closest('form');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data produk akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                formEl.submit();
            }
        });
    });

    // Notifikasi sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1800
        });
    @endif

    // Error validasi
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi kesalahan',
            html: `{!! implode('<br>', $errors->all()) !!}`,
        });
    @endif
});
</script>
@endsection
