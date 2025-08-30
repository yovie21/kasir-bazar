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
