@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    {{-- HEADER WITH FILTER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard</h2>
        <div class="d-flex gap-2">
            {{-- Filter Tanggal --}}
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2" id="filterForm">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
            
            {{-- Export Button --}}
            <a href="{{ route('dashboard.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Notifikasi Real-time --}}
    <div id="notification-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 350px;"></div>

    {{-- ROW 1: Statistik Utama --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penjualan Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($salesToday, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">{{ $transactionsToday }} transaksi</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Penjualan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalSales, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">All time</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Rata-rata Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($avgTransactionValue, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Per transaksi hari ini</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Stok
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalStock, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">{{ $totalProducts }} produk</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2: Perbandingan Bulan & Alert --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-6 mb-3">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Perbandingan Penjualan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-muted">Bulan Ini</div>
                        <div class="h5 font-weight-bold">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Bulan Lalu</div>
                        <div class="h5 font-weight-bold">Rp {{ number_format($salesLastMonth, 0, ',', '.') }}</div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center">
                        @if($percentageChange > 0)
                            <i class="fas fa-arrow-up text-success me-2"></i>
                            <span class="text-success font-weight-bold">{{ number_format(abs($percentageChange), 1) }}%</span>
                            <span class="ms-2 small text-muted">Meningkat</span>
                        @elseif($percentageChange < 0)
                            <i class="fas fa-arrow-down text-danger me-2"></i>
                            <span class="text-danger font-weight-bold">{{ number_format(abs($percentageChange), 1) }}%</span>
                            <span class="ms-2 small text-muted">Menurun</span>
                        @else
                            <span class="text-secondary">Tidak ada perubahan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-6 mb-3">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Peringatan Stok
                    </h6>
                    <span class="badge bg-danger">{{ $outOfStockCount }} habis</span>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-warning text-dark">
                                                {{ $product->stock_warehouse }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p class="mb-0">Semua produk stok aman!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: Grafik Penjualan & Top Products --}}
    <div class="row mb-4">
        <div class="col-xl-8 mb-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area"></i> Grafik Penjualan ({{ $startDate }} s/d {{ $endDate }})
                    </h6>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy"></i> Top 5 Produk Terlaris
                    </h6>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 4: Kasir Terbaik & Jam Sibuk --}}
    <div class="row mb-4">
        <div class="col-xl-6 mb-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-user-tie"></i> Kasir Terbaik
                    </h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="cashierChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-clock"></i> Jam Sibuk
                    </h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 5: Metode Pembayaran --}}
    <div class="row mb-4">
        <div class="col-xl-6 mb-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-purple">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body" style="height: 320px;">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.card { transition: transform 0.2s; }
.card:hover { transform: translateY(-5px); }
.text-purple { color: #6f42c1 !important; }
.notification {
    animation: slideIn 0.3s ease-out;
}
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const formatRupiahCompact = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            notation: 'compact'
        }).format(value);
    };
    
    const formatRupiahFull = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(value);
    };

    // ========== GRAFIK PENJUALAN ==========
    const salesLabels = {!! json_encode($chartLabels->toArray()) !!};
    const salesValues = {!! json_encode($chartValues->toArray()) !!}.map(v => parseFloat(v) || 0);
    const maxSalesVal = salesValues.length ? Math.max(...salesValues) : 0;
    const suggestedMaxSales = maxSalesVal > 0 ? Math.ceil(maxSalesVal * 1.2) : 5000000;

    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesGradient = salesCtx.createLinearGradient(0, 0, 0, 300);
    salesGradient.addColorStop(0, 'rgba(78, 115, 223, 0.3)');
    salesGradient.addColorStop(1, 'rgba(78, 115, 223, 0.05)');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Total Penjualan',
                data: salesValues,
                fill: true,
                backgroundColor: salesGradient,
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: suggestedMaxSales,
                    ticks: { callback: function(value) { return formatRupiahCompact(value); } }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: { callbacks: { label: function(ctx) { return 'Total: ' + formatRupiahFull(ctx.parsed.y); } } }
            }
        }
    });

    // ========== TOP 5 PRODUK ==========
    const productNames = {!! json_encode($topProductNames->toArray()) !!};
    const productQuantities = {!! json_encode($topProductQuantities->toArray()) !!}.map(v => parseInt(v) || 0);

    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Jumlah Terjual',
                data: productQuantities,
                backgroundColor: ['rgba(28, 200, 138, 0.8)', 'rgba(54, 185, 204, 0.8)', 'rgba(246, 194, 62, 0.8)', 'rgba(231, 74, 59, 0.8)', 'rgba(133, 135, 150, 0.8)'],
                borderColor: ['rgba(28, 200, 138, 1)', 'rgba(54, 185, 204, 1)', 'rgba(246, 194, 62, 1)', 'rgba(231, 74, 59, 1)', 'rgba(133, 135, 150, 1)'],
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: function(ctx) { return 'Terjual: ' + ctx.parsed.x + ' unit'; } } }
            }
        }
    });

    // ========== KASIR TERBAIK ==========
    const cashierNames = {!! json_encode($cashierNames->toArray()) !!};
    const cashierTransactions = {!! json_encode($cashierTransactions->toArray()) !!}.map(v => parseInt(v) || 0);

    new Chart(document.getElementById('cashierChart'), {
        type: 'doughnut',
        data: {
            labels: cashierNames,
            datasets: [{
                data: cashierTransactions,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' transaksi'; } } }
            }
        }
    });

    // ========== JAM SIBUK ==========
    const hourLabels = {!! json_encode($hourLabels->toArray()) !!};
    const hourValues = {!! json_encode($hourValues->toArray()) !!}.map(v => parseInt(v) || 0);

    new Chart(document.getElementById('peakHoursChart'), {
        type: 'line',
        data: {
            labels: hourLabels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: hourValues,
                fill: true,
                backgroundColor: 'rgba(231, 74, 59, 0.2)',
                borderColor: 'rgba(231, 74, 59, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(231, 74, 59, 1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: { callbacks: { label: function(ctx) { return ctx.parsed.y + ' transaksi'; } } }
            }
        }
    });

    // ========== METODE PEMBAYARAN ==========
    const paymentLabels = {!! json_encode($paymentLabels->toArray()) !!};
    const paymentCounts = {!! json_encode($paymentCounts->toArray()) !!}.map(v => parseInt(v) || 0);

    new Chart(document.getElementById('paymentMethodChart'), {
        type: 'pie',
        data: {
            labels: paymentLabels,
            datasets: [{
                data: paymentCounts,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' transaksi'; } } }
            }
        }
    });

    // ========== NOTIFIKASI REAL-TIME ==========
    let lastCheckTime = new Date().toISOString();
    
    function checkNewTransactions() {
        fetch('{{ route("dashboard.notifications") }}?last_check=' + lastCheckTime)
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    data.transactions.forEach(trans => {
                        showNotification(trans);
                    });
                    lastCheckTime = new Date().toISOString();
                    
                    // Refresh statistik jika ada transaksi baru
                    if (data.count > 0) {
                        setTimeout(() => location.reload(), 5000);
                    }
                }
            })
            .catch(error => console.error('Error checking notifications:', error));
    }

    function showNotification(transaction) {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show notification shadow-lg';
        notification.innerHTML = `
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong><i class="fas fa-shopping-cart"></i> Transaksi Baru!</strong><br>
            <small>${transaction.no_trans}</small><br>
            <strong>${formatRupiahFull(transaction.total)}</strong><br>
            <small class="text-muted">Kasir: ${transaction.cashier} • ${transaction.time}</small>
        `;
        
        container.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Check setiap 10 detik
    setInterval(checkNewTransactions, 10000);
</script>
@endsection 