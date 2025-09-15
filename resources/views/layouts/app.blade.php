<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Add these lines to your existing head section -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
       /* Mode HP: tabel berubah jadi card */
        @media (max-width: 768px) {
            table thead {
                display: none;
            }
            table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: .5rem;
                background: #fff;
            }
            table tbody td {
                display: flex;
                justify-content: space-between;
                padding: .5rem;
                border: none !important;
                font-size: 0.9rem;
            }
            table tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #555;
            }
        }

        /* Biar tabel lebih rapat */
        .table-sm td,
        .table-sm th {
            padding: 0.35rem 0.5rem !important; 
            font-size: 0.85rem !important;     
            vertical-align: middle !important; 
        }

        /* Tombol Edit & Hapus lebih kecil */
        .btn-icon {
            padding: 0.15rem 0.35rem !important;
            font-size: 0.75rem !important;
            line-height: 1 !important;
        }
        .btn-icon i {
            font-size: 0.9rem !important;
        }

        /* Supaya konten tidak ketimpa header/footer */
        main {
            padding-top: 15px;    /* sesuaikan dengan tinggi header */
            padding-bottom: 120px; /* lebih besar dari tinggi footer (biar pagination aman) */
        }

        header.fixed-top {
            z-index: 1030;
        }

        footer.fixed-bottom {
            z-index: 1030;
        }

        /* Style tombol aksi di mode HP */
        @media (max-width: 768px) {
            td[data-label="Aksi"] {
                display: flex !important;
                justify-content: center;
                gap: 0.5rem;
            }

            td[data-label="Aksi"] .btn {
                flex: 1; 
                min-width: 80px;
                font-size: 0.85rem;
            }
        }

        /* Biar menu bisa discroll di layar kecil */
        .offcanvas-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Supaya pagination tidak ketimpa di HP */
        @media (max-width: 768px) {
            .pagination {
                display: flex;
                overflow-x: auto;
                white-space: nowrap;
                padding: .5rem;
                margin-bottom: 1rem; /* jarak tambahan agar aman dari footer */
            }
        }
        .pagination {
            margin: 0;
            flex-wrap: wrap;
        }
        .pagination li {
            margin: 2px;
        }
        /* Tambahin jarak biar konten gak ketutup header */
        body {
            padding-top: 70px; /* sesuaikan tinggi navbar kamu */
        }

        /* Area pembayaran fix di bawah layar */
        #paymentBox {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1050; /* lebih tinggi dari konten */
        }


    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        {{-- Navigation fixed top --}}
        <header class="bg-white shadow-sm fixed-top">
            @include('layouts.navigation')
        </header>

        <!-- Page Heading (optional, jika ada $header) -->
        @isset($header)
            <div class="bg-white shadow mt-5">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    @yield('scripts')

    <!-- Footer -->
    <footer class="bg-white border-top py-2 fixed-bottom shadow-sm">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <!-- Info -->
                <div class="col-md-9 small">
                    <p class="mb-1 fw-bold text-success">ASSALAAM <span class="text-warning">HYPERMARKET</span></p>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        Jl. Ahmad Yani 308, Pabelan, Kec. Kartasura, Kabupaten Sukoharjo
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-telephone-fill text-primary me-2"></i> 0271 – 743333
                        <span class="mx-2">|</span>
                        <i class="bi bi-whatsapp text-success me-2"></i> 0812 2604 8447
                        <span class="mx-2">|</span>
                        <i class="bi bi-envelope-fill text-danger me-2"></i> assalaam.hypermarket@gmail.com
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
