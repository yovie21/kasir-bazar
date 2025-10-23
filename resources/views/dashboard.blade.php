@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    {{--@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    {{-- HEADER WITH FILTER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="mb-0 flex-grow-1 text-center text-md-start">Dashboard</h2>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex flex-wrap gap-2" id="filterForm">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                <button type="submit" class="btn btn-primary w-100 w-md-auto">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100 w-md-auto">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
            <a href="{{ route('dashboard.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn btn-success w-100 w-md-auto">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    <div id="notification-container"
         style="position: fixed; top: 80px; right: 10px; z-index: 9999; max-width: 95%; width: 360px;"></div>

    {{-- ROW 1 --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
        {{-- Cards --}}
        @php
            $stats = [
                ['title' => 'Penjualan Hari Ini', 'value' => $salesToday, 'note' => "$transactionsToday transaksi", 'icon' => 'calendar', 'color' => 'primary'],
                ['title' => 'Total Penjualan', 'value' => $totalSales, 'note' => 'All time', 'icon' => 'dollar-sign', 'color' => 'success'],
                ['title' => 'Rata-rata Transaksi', 'value' => $avgTransactionValue, 'note' => 'Per transaksi hari ini', 'icon' => 'chart-line', 'color' => 'info'],
                ['title' => 'Total Stok', 'value' => $totalStock, 'note' => "$totalProducts produk", 'icon' => 'boxes', 'color' => 'warning']
            ];
        @endphp
        @foreach($stats as $s)
        <div class="col">
            <div class="card border-left-{{ $s['color'] }} shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="text-xs fw-bold text-{{ $s['color'] }} text-uppercase mb-1">
                                {{ $s['title'] }}
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                Rp {{ number_format($s['value'], 0, ',', '.') }}
                            </div>
                            <small class="text-muted">{{ $s['note'] }}</small>
                        </div>
                        <i class="fas fa-{{ $s['icon'] }} fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ROW 2 --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6 col-xl-4">
            {{-- Perbandingan Bulan --}}
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Perbandingan Penjualan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <div><small>Bulan Ini</small><div class="h5">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</div></div>
                        <div><small>Bulan Lalu</small><div class="h5">Rp {{ number_format($salesLastMonth, 0, ',', '.') }}</div></div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center">
                        @if($percentageChange > 0)
                            <i class="fas fa-arrow-up text-success me-2"></i>
                            <span class="text-success fw-bold">{{ number_format(abs($percentageChange), 1) }}%</span>
                            <span class="ms-2 small text-muted">Meningkat</span>
                        @elseif($percentageChange < 0)
                            <i class="fas fa-arrow-down text-danger me-2"></i>
                            <span class="text-danger fw-bold">{{ number_format(abs($percentageChange), 1) }}%</span>
                            <span class="ms-2 small text-muted">Menurun</span>
                        @else
                            <span class="text-secondary">Tidak ada perubahan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Peringatan Stok --}}
        <div class="col-lg-6 col-xl-8">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-warning"><i class="fas fa-exclamation-triangle"></i> Peringatan Stok</h6>
                    <span class="badge bg-danger">{{ $outOfStockCount }} habis</span>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Produk</th><th class="text-end">Stok</th></tr></thead>
                            <tbody>
                            @foreach($lowStockProducts as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td class="text-end"><span class="badge bg-warning text-dark">{{ $p->stock_warehouse }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p>Semua produk stok aman!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3 --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-area"></i> Grafik Penjualan</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="fas fa-trophy"></i> Top 5 Produk Terlaris</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 4 --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-info"><i class="fas fa-user-tie"></i> Kasir Terbaik</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="cashierChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-danger"><i class="fas fa-clock"></i> Jam Sibuk</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 5 --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-purple"><i class="fas fa-credit-card"></i> Metode Pembayaran</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STYLE RESPONSIVE --}}
<style>
.border-left-primary{border-left:.25rem solid #4e73df!important}
.border-left-success{border-left:.25rem solid #1cc88a!important}
.border-left-info{border-left:.25rem solid #36b9cc!important}
.border-left-warning{border-left:.25rem solid #f6c23e!important}
.card{transition:.2s all}
.card:hover{transform:translateY(-3px)}
.chart-container{position:relative;min-height:280px;height:auto;aspect-ratio:16/9}
.text-purple{color:#6f42c1!important}
@media (max-width:768px){
  .card-body{padding:1rem}
  h2{font-size:1.5rem}
  .btn{font-size:.9rem}
  .chart-container{aspect-ratio:4/3;min-height:240px}
}
@media (max-width:480px){
  .chart-container{aspect-ratio:1/1;min-height:220px}
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