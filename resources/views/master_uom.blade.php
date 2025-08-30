@extends('layouts.app')

@section('content')
<div class="container mt-4"> {{-- kasih margin-top agar tidak mepet --}}
    <h4 class="mb-4">Master UOM</h4>

    <div class="row">
        {{-- Form Tambah / Edit --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>{{ isset($uom) ? 'Edit UOM' : 'Tambah UOM' }}</strong>
                </div>
                <div class="card-body">
                    <form action="{{ isset($uom) ? route('uoms.update', $uom->uomId) : route('uoms.store') }}" method="POST">
                        @csrf
                        @if(isset($uom))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Kode UOM</label>
                            <input type="text" name="uomKode" class="form-control" 
                                   value="{{ old('uomKode', $uom->uomKode ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama UOM</label>
                            <input type="text" name="uomName" class="form-control" 
                                   value="{{ old('uomName', $uom->uomName ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konversi PCS</label>
                            <input type="number" name="konvPcs" class="form-control" 
                                   value="{{ old('konvPcs', $uom->konvPcs ?? '') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ isset($uom) ? 'Update' : 'Simpan' }}
                        </button>
                        @if(isset($uom))
                            <a href="{{ route('uoms.index') }}" class="btn btn-secondary">Batal</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar UOM --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Daftar UOM</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Konv PCS</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uoms as $index => $item)
                                <tr>
                                    <td>{{ $uoms->firstItem() + $index }}</td>
                                    <td>{{ $item->uomKode }}</td>
                                    <td>{{ $item->uomName }}</td>
                                    <td>{{ $item->konvPcs }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('uoms.edit', $item->uomId) }}" 
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                            <form action="{{ route('uoms.destroy', $item->uomId) }}" 
                                                  method="POST" class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data UOM</td>
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
{{-- Tambah SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Notifikasi sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Notifikasi error
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            html: `{!! implode('<br>', $errors->all()) !!}`,
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
