@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-2 px-md-3">

    {{-- HEADER WITH FILTER --}}
    <div class="dashboard-header mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
            <div class="header-title">
                <h2 class="mb-1">Dashboard</h2>
                <p class="text-muted mb-0 small">
                    <i class="fas fa-calendar-alt"></i> {{ date('d M Y, H:i') }} WIB
                </p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('dashboard') }}" class="d-flex flex-column flex-sm-row gap-2" id="filterForm">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> <span class="d-none d-sm-inline">Filter</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> <span class="d-none d-sm-inline">Reset</span>
                    </a>
                </form>
                <a href="{{ route('dashboard.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">Export Excel</span>
                </a>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    <div id="notification-container"></div>

    {{-- ROW 1: STATS CARDS WITH ANIMATION --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        @php
            $stats = [
                [
                    'title' => 'Penjualan Hari Ini', 
                    'value' => $salesToday, 
                    'note' => "$transactionsToday transaksi", 
                    'icon' => 'calendar-check', 
                    'color' => 'primary',
                    'trend' => '+12%',
                    'trendIcon' => 'arrow-up',
                    'trendColor' => 'success'
                ],
                [
                    'title' => 'Total Penjualan', 
                    'value' => $totalSales, 
                    'note' => 'All time', 
                    'icon' => 'dollar-sign', 
                    'color' => 'success',
                    'trend' => 'Rp',
                    'trendIcon' => 'chart-line',
                    'trendColor' => 'success'
                ],
                [
                    'title' => 'Rata-rata Transaksi', 
                    'value' => $avgTransactionValue, 
                    'note' => 'Per transaksi hari ini', 
                    'icon' => 'receipt', 
                    'color' => 'info',
                    'trend' => '+5%',
                    'trendIcon' => 'arrow-up',
                    'trendColor' => 'info'
                ],
                [
                    'title' => 'Total Stok', 
                    'value' => $totalStock, 
                    'note' => "$totalProducts produk", 
                    'icon' => 'boxes', 
                    'color' => 'warning',
                    'trend' => $outOfStockCount > 0 ? "$outOfStockCount habis" : 'Aman',
                    'trendIcon' => $outOfStockCount > 0 ? 'exclamation-triangle' : 'check-circle',
                    'trendColor' => $outOfStockCount > 0 ? 'danger' : 'success'
                ]
            ];
        @endphp
        @foreach($stats as $index => $s)
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
            <div class="stat-card card border-0 shadow-sm h-100 card-{{ $s['color'] }}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon icon-{{ $s['color'] }}">
                            <i class="fas fa-{{ $s['icon'] }}"></i>
                        </div>
                        <span class="badge badge-trend badge-{{ $s['trendColor'] }}">
                            <i class="fas fa-{{ $s['trendIcon'] }}"></i> {{ $s['trend'] }}
                        </span>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-title text-muted mb-1">{{ $s['title'] }}</h6>
                        <h3 class="stat-value mb-1" data-value="{{ $s['value'] }}">
                            {{ number_format($s['value'], 0, ',', '.') }}
                        </h3>
                        <small class="text-muted d-flex align-items-center">
                            <i class="fas fa-info-circle me-1"></i> {{ $s['note'] }}
                        </small>
                    </div>
                </div>
                <div class="card-hover-effect"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ROW 2: QUICK INSIGHTS --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6 col-lg-4" data-aos="fade-right">
            {{-- Perbandingan Bulan dengan Progress Bar --}}
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold">
                            <i class="fas fa-chart-line me-2"></i>Perbandingan Penjualan
                        </h6>
                        <i class="fas fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="comparison-item mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Bulan Ini</span>
                            <span class="fw-bold text-primary">100%</span>
                        </div>
                        <div class="progress mb-1" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                        <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</h5>
                    </div>
                    
                    <div class="comparison-item">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Bulan Lalu</span>
                            @php
                                $progressPercent = $salesThisMonth > 0 ? ($salesLastMonth / $salesThisMonth * 100) : 0;
                            @endphp
                            <span class="fw-bold text-secondary">{{ number_format($progressPercent, 0) }}%</span>
                        </div>
                        <div class="progress mb-1" style="height: 8px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <h5 class="fw-bold text-secondary mb-0">Rp {{ number_format($salesLastMonth, 0, ',', '.') }}</h5>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="performance-badge text-center p-2 rounded">
                        @if($percentageChange > 0)
                            <div class="badge-success-custom">
                                <i class="fas fa-arrow-up fa-2x mb-2"></i>
                                <h4 class="fw-bold mb-0">+{{ number_format(abs($percentageChange), 1) }}%</h4>
                                <small>Pertumbuhan Positif</small>
                            </div>
                        @elseif($percentageChange < 0)
                            <div class="badge-danger-custom">
                                <i class="fas fa-arrow-down fa-2x mb-2"></i>
                                <h4 class="fw-bold mb-0">{{ number_format($percentageChange, 1) }}%</h4>
                                <small>Perlu Ditingkatkan</small>
                            </div>
                        @else
                            <div class="badge-secondary-custom">
                                <i class="fas fa-minus fa-2x mb-2"></i>
                                <h4 class="fw-bold mb-0">0%</h4>
                                <small>Tidak Ada Perubahan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Peringatan Stok dengan Search & Sort --}}
        <div class="col-12 col-md-6 col-lg-8" data-aos="fade-left">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-header bg-gradient-warning text-dark border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="m-0 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok
                        </h6>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-danger pulse-animation">{{ $outOfStockCount }} habis</span>
                            <span class="badge bg-warning text-dark">{{ $lowStockProducts->count() }} rendah</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($lowStockProducts->count() > 0)
                    <div class="p-3 border-bottom">
                        <input type="text" class="form-control form-control-sm" id="searchStock" placeholder="🔍 Cari produk...">
                    </div>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover mb-0" id="stockTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="font-size: 0.85rem;">
                                        <i class="fas fa-box me-1"></i>Produk
                                    </th>
                                    <th class="text-center" style="font-size: 0.85rem;">
                                        <i class="fas fa-layer-group me-1"></i>Stok
                                    </th>
                                    <th class="text-center" style="font-size: 0.85rem;">
                                        <i class="fas fa-signal me-1"></i>Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($lowStockProducts as $p)
                                <tr class="stock-row">
                                    <td style="font-size: 0.85rem;" class="product-name">
                                        <i class="fas fa-cube text-muted me-2"></i>{{ $p->name }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $p->stock_warehouse == 0 ? 'bg-danger' : 'bg-warning' }} text-dark">
                                            {{ $p->stock_warehouse }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($p->stock_warehouse == 0)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Habis
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-circle"></i> Rendah
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center text-success py-5">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5 class="mb-0">Semua Produk Stok Aman!</h5>
                            <p class="text-muted">Tidak ada produk dengan stok rendah</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: CHARTS WITH TABS --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-lg-8" data-aos="zoom-in">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <ul class="nav nav-pills card-header-pills" id="salesChartTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="line-tab" data-bs-toggle="tab" data-bs-target="#lineChart" type="button">
                                <i class="fas fa-chart-line"></i> Line Chart
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bar-tab" data-bs-toggle="tab" data-bs-target="#barChart" type="button">
                                <i class="fas fa-chart-bar"></i> Bar Chart
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="area-tab" data-bs-toggle="tab" data-bs-target="#areaChart" type="button">
                                <i class="fas fa-chart-area"></i> Area Chart
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="tab-content" id="salesChartContent">
                        <div class="tab-pane fade show active" id="lineChart">
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="barChart">
                            <div class="chart-container">
                                <canvas id="salesChartBar"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="areaChart">
                            <div class="chart-container">
                                <canvas id="salesChartArea"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white border-0">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-trophy me-2"></i>Top 10 Produk Terlaris
                    </h6>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="chart-container">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 4: DUAL CHARTS --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6" data-aos="flip-left">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white border-0">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-user-tie me-2"></i>Kasir Terbaik
                    </h6>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="chart-container">
                        <canvas id="cashierChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6" data-aos="flip-right">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-danger text-white border-0">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>Jam Sibuk Transaksi
                    </h6>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="chart-container">
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 5: PAYMENT & ADDITIONAL INFO --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6" data-aos="fade-up">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-purple text-white border-0">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-credit-card me-2"></i>Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="chart-container">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white border-0">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Ringkasan Sistem
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-grid-enhanced">
                        <div class="info-card info-card-primary">
                            <div class="info-card-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="info-card-body">
                                <p class="info-card-label">Total Transaksi</p>
                                <h4 class="info-card-value">{{ number_format($transactionsToday, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        
                        <div class="info-card info-card-success">
                            <div class="info-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="info-card-body">
                                <p class="info-card-label">Kasir Aktif</p>
                                <h4 class="info-card-value">{{ count($cashierNames) }}</h4>
                            </div>
                        </div>
                        
                        <div class="info-card info-card-warning">
                            <div class="info-card-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div class="info-card-body">
                                <p class="info-card-label">Total Produk</p>
                                <h4 class="info-card-value">{{ $totalProducts }}</h4>
                            </div>
                        </div>
                        
                        <div class="info-card info-card-info">
                            <div class="info-card-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="info-card-body">
                                <p class="info-card-label">Periode Filter</p>
                                <h6 class="info-card-value-small">{{ date('d M', strtotime($startDate)) }} - {{ date('d M', strtotime($endDate)) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ADVANCED STYLES --}}
<style>
/* ========== VARIABLES ========== */
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --info: #36b9cc;
    --warning: #f6c23e;
    --danger: #e74a3b;
    --purple: #6f42c1;
    --dark: #5a5c69;
    --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    --shadow-lg: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

/* ========== GLOBAL ========== */
body {
    background-color: #f8f9fc;
}

/* ========== HEADER ========== */
.dashboard-header {
    background: linear-gradient(135deg, #1b3ac5 0%, #01f19d 100%);
    padding: 1.5rem;
    border-radius: 1rem;
    color: white;
    margin-bottom: 1.5rem !important;
    box-shadow: var(--shadow-lg);
}

.dashboard-header h2 {
    color: white;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

/* ========== STAT CARDS ========== */
.stat-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 1rem !important;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-lg) !important;
}

.stat-card.card-primary { border-left: 4px solid var(--primary); }
.stat-card.card-success { border-left: 4px solid var(--success); }
.stat-card.card-info { border-left: 4px solid var(--info); }
.stat-card.card-warning { border-left: 4px solid var(--warning); }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon.icon-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.stat-icon.icon-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
.stat-icon.icon-info { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); color: white; }
.stat-icon.icon-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }

.stat-title {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3748;
}

.badge-trend {
    font-size: 0.7rem;
    padding: 0.35rem 0.6rem;
    border-radius: 20px;
    font-weight: 600;
}

.card-hover-effect {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--info));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-card:hover .card-hover-effect {
    transform: scaleX(1);
}

/* ========== GRADIENT HEADERS ========== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%);
}

/* ========== HOVER LIFT ========== */
.hover-lift {
    transition: all 0.3s ease;
    border-radius: 1rem !important;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
}

/* ========== COMPARISON CARDS ========== */
.comparison-item {
    padding: 1rem;
    background: #f8f9fc;
    border-radius: 0.5rem;
}

.performance-badge {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.badge-success-custom {
    color: var(--success);
}

.badge-danger-custom {
    color: var(--danger);
}

.badge-secondary-custom {
    color: var(--dark);
}

/* ========== PULSE ANIMATION ========== */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.pulse-animation {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* ========== CHART TABS ========== */
.nav-pills .nav-link {
    border-radius: 0.5rem;
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.4);
}

.nav-pills .nav-link:hover:not(.active) {
    background: #f8f9fc;
}

/* ========== CHARTS ========== */
.chart-container {
    position: relative;
    width: 100%;
    height: 280px;
}

/* ========== NOTIFICATION ========== */
#notification-container {
    position: fixed;
    top: 70px;
    right: 10px;
    left: auto;
    z-index: 9999;
    max-width: 400px;
}

.notification {
    border-radius: 0.75rem;
    margin-bottom: 0.5rem;
    border-left: 4px solid #1cc88a;
    animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* ========== INFO GRID ========== */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fc;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e7f3ff;
    transform: scale(1.05);
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.info-content small {
    font-size: 0.75rem;
}

/* ========== TABLE ENHANCEMENTS ========== */
.table-responsive::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.stock-row {
    transition: all 0.2s ease;
}

.stock-row:hover {
    background: #f8f9fc;
    transform: scale(1.01);
}

/* ========== RESPONSIVE BREAKPOINTS ========== */

/* Mobile Small (< 400px) */
@media (max-width: 399.98px) {
    .dashboard-header {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .chart-container {
        height: 200px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}

/* Mobile (400px - 575.98px) */
@media (min-width: 400px) and (max-width: 575.98px) {
    .stat-value {
        font-size: 1.5rem;
    }
    
    .chart-container {
        height: 220px;
    }
}

/* Mobile Large & Tablet Small (576px - 767.98px) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .chart-container {
        height: 250px;
    }
}

/* Tablet (768px - 991.98px) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .chart-container {
        height: 270px;
    }
    
    .info-grid {
        grid-template-columns: 1fr 1fr;
    }
}

/* Desktop (992px - 1199.98px) */
@media (min-width: 992px) and (max-width: 1199.98px) {
    .chart-container {
        height: 280px;
    }
}

/* Large Desktop (> 1200px) */
@media (min-width: 1200px) {
    .chart-container {
        height: 300px;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}

/* ========== DARK MODE SUPPORT ========== */
@media (prefers-color-scheme: dark) {
    body {
        background-color: #1a202c;
    }
    
    .card {
        background: #2d3748;
        color: #ffffff;
    }
    
    .text-muted {
        color: #a0aec0 !important;
    }
    
    .stat-value {
        color: #e2e8f0;
    }
}

/* ========== PRINT STYLES ========== */
@media print {
    .dashboard-header form,
    .btn,
    #notification-container {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        page-break-inside: avoid;
        border: 1px solid #ddd;
    }
    
    .stat-card:hover {
        transform: none;
    }
}

/* ========== LOADING ANIMATION ========== */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.skeleton {
    animation: shimmer 2s infinite;
    background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
    background-size: 1000px 100%;
}

/* ========== ACCESSIBILITY ========== */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* Focus visible for keyboard navigation */
*:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

/* ========== UTILITIES ========== */
.text-shadow {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.backdrop-blur {
    backdrop-filter: blur(10px);
}

.bg-pattern {
    background-image: 
        repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.05) 10px, rgba(255,255,255,.05) 20px);
}
</style>

{{-- JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    offset: 50
});

// ========== UTILITY FUNCTIONS ==========
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

// Responsive settings
const isMobile = window.innerWidth < 576;
const isTablet = window.innerWidth >= 576 && window.innerWidth < 992;
const chartFontSize = isMobile ? 10 : (isTablet ? 11 : 12);
const chartPadding = isMobile ? 5 : 10;

// ========== CHART CONFIGURATIONS ==========
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    layout: { padding: chartPadding },
    plugins: {
        legend: {
            labels: { 
                font: { size: chartFontSize },
                padding: isMobile ? 8 : 10,
                usePointStyle: true
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            cornerRadius: 8,
            titleFont: { size: chartFontSize + 1, weight: 'bold' },
            bodyFont: { size: chartFontSize }
        }
    }
};

// ========== DATA PREPARATION ==========
const salesLabels = {!! json_encode($chartLabels->toArray()) !!};
const salesValues = {!! json_encode($chartValues->toArray()) !!}.map(v => parseFloat(v) || 0);
const maxSalesVal = salesValues.length ? Math.max(...salesValues) : 0;
const suggestedMaxSales = maxSalesVal > 0 ? Math.ceil(maxSalesVal * 1.2) : 5000000;

const productNames = {!! json_encode($topProductNames->toArray()) !!};
const productQuantities = {!! json_encode($topProductQuantities->toArray()) !!}.map(v => parseInt(v) || 0);

const cashierNames = {!! json_encode($cashierNames->toArray()) !!};
const cashierTransactions = {!! json_encode($cashierTransactions->toArray()) !!}.map(v => parseInt(v) || 0);

const hourLabels = {!! json_encode($hourLabels->toArray()) !!};
const hourValues = {!! json_encode($hourValues->toArray()) !!}.map(v => parseInt(v) || 0);

const paymentLabels = {!! json_encode($paymentLabels->toArray()) !!};
const paymentCounts = {!! json_encode($paymentCounts->toArray()) !!}.map(v => parseInt(v) || 0);

// ========== GRADIENTS ==========
const createGradient = (ctx, color1, color2) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
};

// ========== SALES CHART (LINE) ==========
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesGradient = createGradient(salesCtx, 'rgba(102, 126, 234, 0.4)', 'rgba(118, 75, 162, 0.05)');

new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Total Penjualan',
            data: salesValues,
            fill: true,
            backgroundColor: salesGradient,
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: isMobile ? 2 : 3,
            pointBackgroundColor: 'rgba(102, 126, 234, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: isMobile ? 3 : 5,
            pointHoverRadius: isMobile ? 5 : 7,
            tension: 0.4
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: suggestedMaxSales,
                ticks: { 
                    callback: function(value) { return formatRupiahCompact(value); },
                    font: { size: chartFontSize }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: chartFontSize } },
                grid: { display: false }
            }
        },
        plugins: {
            ...commonOptions.plugins,
            legend: { display: true, position: 'top' },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return 'Total: ' + formatRupiahFull(ctx.parsed.y); } 
                }
            }
        }
    }
});

// ========== SALES CHART (BAR) ==========
const salesBarCtx = document.getElementById('salesChartBar').getContext('2d');
new Chart(salesBarCtx, {
    type: 'bar',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Total Penjualan',
            data: salesValues,
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: suggestedMaxSales,
                ticks: { 
                    callback: function(value) { return formatRupiahCompact(value); },
                    font: { size: chartFontSize }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: chartFontSize } },
                grid: { display: false }
            }
        },
        plugins: {
            ...commonOptions.plugins,
            legend: { display: false },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return 'Total: ' + formatRupiahFull(ctx.parsed.y); } 
                }
            }
        }
    }
});

// ========== SALES CHART (AREA) ==========
const salesAreaCtx = document.getElementById('salesChartArea').getContext('2d');
const areaGradient = createGradient(salesAreaCtx, 'rgba(17, 153, 142, 0.4)', 'rgba(56, 239, 125, 0.05)');

new Chart(salesAreaCtx, {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Total Penjualan',
            data: salesValues,
            fill: true,
            backgroundColor: areaGradient,
            borderColor: 'rgba(17, 153, 142, 1)',
            borderWidth: isMobile ? 2 : 3,
            pointRadius: 0,
            tension: 0.4
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: suggestedMaxSales,
                ticks: { 
                    callback: function(value) { return formatRupiahCompact(value); },
                    font: { size: chartFontSize }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: chartFontSize } },
                grid: { display: false }
            }
        },
        plugins: {
            ...commonOptions.plugins,
            legend: { display: true, position: 'top' },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return 'Total: ' + formatRupiahFull(ctx.parsed.y); } 
                }
            }
        }
    }
});

// ========== TOP PRODUCTS CHART ==========
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: productNames,
        datasets: [{
            label: 'Jumlah Terjual',
            data: productQuantities,
            backgroundColor: [
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(231, 74, 59, 0.8)',
                'rgba(133, 135, 150, 0.8)'
            ],
            borderColor: [
                'rgba(28, 200, 138, 1)',
                'rgba(54, 185, 204, 1)',
                'rgba(246, 194, 62, 1)',
                'rgba(231, 74, 59, 1)',
                'rgba(133, 135, 150, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        ...commonOptions,
        indexAxis: 'y',
        scales: { 
            x: { 
                beginAtZero: true, 
                ticks: { 
                    precision: 0,
                    font: { size: chartFontSize }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            y: {
                ticks: { font: { size: chartFontSize } },
                grid: { display: false }
            }
        },
        plugins: {
            ...commonOptions.plugins,
            legend: { display: false },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return 'Terjual: ' + ctx.parsed.x + ' unit'; } 
                }
            }
        }
    }
});

// ========== CASHIER CHART ==========
new Chart(document.getElementById('cashierChart'), {
    type: 'doughnut',
    data: {
        labels: cashierNames,
        datasets: [{
            data: cashierTransactions,
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(231, 74, 59, 0.8)'
            ],
            borderColor: '#fff',
            borderWidth: 3
        }]
    },
    options: {
        ...commonOptions,
        cutout: '60%',
        plugins: {
            ...commonOptions.plugins,
            legend: { 
                position: 'bottom',
                labels: { 
                    font: { size: chartFontSize },
                    padding: isMobile ? 8 : 10,
                    usePointStyle: true
                }
            },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' transaksi'; } 
                }
            }
        }
    }
});

// ========== PEAK HOURS CHART ==========
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
            borderWidth: isMobile ? 2 : 3,
            pointBackgroundColor: 'rgba(231, 74, 59, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: isMobile ? 3 : 4,
            pointHoverRadius: isMobile ? 5 : 6,
            tension: 0.4
        }]
    },
    options: {
        ...commonOptions,
        scales: { 
            y: { 
                beginAtZero: true, 
                ticks: { 
                    precision: 0,
                    font: { size: chartFontSize }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: chartFontSize } },
                grid: { display: false }
            }
        },
        plugins: {
            ...commonOptions.plugins,
            legend: { display: true, position: 'top' },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return ctx.parsed.y + ' transaksi'; } 
                }
            }
        }
    }
});

// ========== PAYMENT METHOD CHART ==========
new Chart(document.getElementById('paymentMethodChart'), {
    type: 'pie',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentCounts,
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)'
            ],
            borderColor: '#fff',
            borderWidth: 3
        }]
    },
    options: {
        ...commonOptions,
        plugins: {
            ...commonOptions.plugins,
            legend: { 
                position: 'bottom',
                labels: { 
                    font: { size: chartFontSize },
                    padding: isMobile ? 8 : 10,
                    usePointStyle: true
                }
            },
            tooltip: { 
                callbacks: { 
                    label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' transaksi'; } 
                }
            }
        }
    }
});

// ========== STOCK TABLE SEARCH ==========
document.getElementById('searchStock')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.stock-row');
    
    rows.forEach(row => {
        const productName = row.querySelector('.product-name').textContent.toLowerCase();
        if (productName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// ========== ANIMATED COUNTERS ==========
document.querySelectorAll('[data-value]').forEach(el => {
    const target = parseInt(el.dataset.value);
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = Math.floor(current).toLocaleString('id-ID');
    }, 16);
});

// ========== NOTIFICATIONS ==========
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
                
                if (data.count > 0) {
                    setTimeout(() => location.reload(), 5000);
                }
            }
        })
        .catch(error => console.error('Error:', error));
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
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

setInterval(checkNewTransactions, 10000);

// ========== RESPONSIVE HANDLER ==========
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        if ((window.innerWidth < 576 && !isMobile) || 
            (window.innerWidth >= 576 && isMobile)) {
            location.reload();
        }
    }, 500);
});

// ========== PERFORMANCE MONITORING ==========
console.log('Dashboard loaded in', performance.now().toFixed(2), 'ms');
</script>
@endsection