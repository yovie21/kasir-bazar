@extends('layouts.app')

@section('title', 'Laporan Keuangan')

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
        .summary h5 {
            margin: 0.3rem 0;
        }
        .badge-success { background-color: #198754; }
        .badge-danger { background-color: #dc3545; }
        .badge-primary { background-color: #0d6efd; }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold">💰 Laporan Keuangan</h2>

    {{-- Filter tanggal --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('laporan.keuangan') }}" class="row g-3">
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
                <a href="{{ route('laporan.keuangan') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Ringkasan Keuangan --}}
    <div class="card p-3 mb-4 summary">
        <h5>Total Penjualan: <span class="fw-bold text-success">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</span></h5>
        <h5>Total Modal: <span class="fw-bold text-danger">Rp {{ number_format($total_modal, 0, ',', '.') }}</span></h5>
        <h5>Laba: 
            <span class="fw-bold {{ $laba >= 0 ? 'text-primary' : 'text-danger' }}">
                Rp {{ number_format($laba, 0, ',', '.') }}
            </span>
        </h5>
    </div>

    {{-- Detail transaksi --}}
    <div class="card p-3">
        <div class="table-responsive">
            <table id="keuanganTable" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Modal</th>
                        <th>Laba</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold text-primary">{{ $s->no_trans }}</span></td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($s->total_cents, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($s->modal_cents, 0, ',', '.') }}</td>
                            <td>
                                <span class="fw-bold {{ ($s->total_cents - $s->modal_cents) >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($s->total_cents - $s->modal_cents, 0, ',', '.') }}
                                </span>
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
            $('#keuanganTable').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { targets: 0, orderable: false } // kolom "No" tidak bisa sort
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ transaksi",
                    paginate: {
                        next: "➡️",
                        previous: "⬅️"
                    }
                },
                order: [[2, 'desc']] // default sort by tanggal terbaru
            });
        });
    </script>
@endsection
