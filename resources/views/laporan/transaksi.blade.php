@extends('layouts.app')  

@section('title', 'Laporan Transaksi')

@section('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .table th {
            background-color: #f8f9fa;
        }
        .badge-success {
            background-color: #198754;
        }
        .badge-danger {
            background-color: #dc3545;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold">📊 Laporan Transaksi</h2>
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('laporan.transaksi') }}" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" 
                    class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" 
                    class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('laporan.transaksi') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table id="salesTable" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Dibayar</th>
                        <th>Kembalian</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold text-primary">{{ $sale->no_trans }}</span></td>
                            <td>{{ $sale->cashier->name ?? 'N/A' }}</td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($sale->total_cents, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($sale->paid_cents, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($sale->change_cents, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $sale->id }}">
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Semua modal dipisah agar tabel tetap rapi --}}
@foreach($sales as $sale)
<div class="modal fade" id="detailModal{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi {{ $sale->no_trans }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
               <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>UOM</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->uom->uomName ?? ($item->product->uom->uomName ?? '-') }}</td>
                            <td>Rp {{ number_format($item->price_cents, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->price_cents * $item->qty, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

                <div class="text-end mt-3">
                    <h5>Total: <span class="fw-bold text-success">Rp {{ number_format($sale->total_cents, 0, ',', '.') }}</span></h5>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#salesTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ transaksi",
                    paginate: {
                        next: "➡️",
                        previous: "⬅️"
                    }
                }
            });
        });
    </script>
@endsection