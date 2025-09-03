@extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 fw-bold"><i class="bi bi-box-seam"></i> Master UOM</h4>

    <div class="row">
        {{-- Form Tambah / Edit --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <strong><i class="bi bi-pencil-square"></i> {{ isset($uom) ? 'Edit UOM' : 'Tambah UOM' }}</strong>
                </div>
                <div class="card-body">
                    <form action="{{ isset($uom) ? route('uoms.update', $uom->uomId) : route('uoms.store') }}" method="POST">
                        @csrf
                        @if(isset($uom)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode UOM</label>
                            <input type="text" name="uomKode" class="form-control" 
                                   placeholder="Contoh: PCS, BOX" 
                                   value="{{ old('uomKode', $uom->uomKode ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama UOM</label>
                            <input type="text" name="uomName" class="form-control" 
                                   placeholder="Contoh: Pack, Karton" 
                                   value="{{ old('uomName', $uom->uomName ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konversi </label>
                            <input type="number" name="konvPcs" class="form-control" 
                                   placeholder="Masukkan nilai konversi" 
                                   value="{{ old('konvPcs', $uom->konvPcs ?? '') }}" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> {{ isset($uom) ? 'Update' : 'Simpan' }}
                            </button>
                            @if(isset($uom))
                                <a href="{{ route('uoms.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar UOM --}}
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-list-ul"></i> Daftar UOM</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Konversi</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uoms as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $uoms->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $item->uomKode }}</td>
                                    <td>{{ $item->uomName }}</td>
                                    <td class="text-center">{{ $item->konvPcs }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('uoms.edit', $item->uomId) }}" 
                                               class="btn btn-sm btn-warning" 
                                               data-bs-toggle="tooltip" title="Edit Data">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('uoms.destroy', $item->uomId) }}" method="POST" class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        data-bs-toggle="tooltip" title="Hapus Data">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data UOM</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $uoms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Enable Bootstrap tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Notifikasi sukses
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif

    // Notifikasi error
    @if($errors->any())
        Swal.fire({
            title: 'Gagal!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Tutup'
        });
    @endif

    // Konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: 'Yakin hapus data?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
