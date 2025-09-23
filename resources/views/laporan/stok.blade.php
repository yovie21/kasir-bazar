@extends('layouts.app')  

@section('title', 'Laporan Stok')

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
        .badge-success { background-color: #198754; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold">📦 Laporan Stok Barang</h2>
    <div class="card p-3">
        <div class="table-responsive">
            <table id="stokTable" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Barcode</th>
                        <th>UOM</th>
                        <th>Stok Tersisa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td>{{ $p->barcode }}</td>
                            <td>{{ $p->uom->uomName ?? '-' }}</td>
                            <td class="text-end fw-bold">{{ $p->stock_warehouse }}</td>
                            <td>
                                @if($p->stock_warehouse <= 10)
                                    <span class="badge bg-danger">Habis!</span>
                                @elseif($p->stock_warehouse <= 30)
                                    <span class="badge bg-warning">Menipis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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
            $('#stokTable').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { targets: 0, orderable: false } // kolom "No" tidak bisa sort
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ produk",
                    paginate: {
                        next: "➡️",
                        previous: "⬅️"
                    }
                },
                order: [[1, 'asc']] // default sort by nama produk
            });
        });
    </script>
@endsection
