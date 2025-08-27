@extends('layout')

@section('content')
    <div class="container mt-5">
        <!-- Toast Notifications -->
        @if (Session::get('success'))
            <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Login berhasil, Selamat datang di app GMP, K3 dan 5R
                    </div>
                </div>
            </div>
        @endif
        @if (Session::get('error'))
            <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                <div class="toast show bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Error</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }} <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        @endif
        @if (Session::get('canAccess'))
            <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                <div class="toast show bg-warning text-dark" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Peringatan</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ Session::get('canAccess') }}<i class="bi bi-exclamation-triangle-fill ms-2"></i>
                    </div>
                </div>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-section text-center bg-success text-white p-4 p-md-5 rounded shadow">
            <h1 class="display-5 fw-bold mb-3">
                Selamat Datang <span class="text-warning">{{ Auth::user()->name }}</span>
            </h1>
            <p class="lead mb-3">Kelola data GMP, K3, dan 5R dengan mudah, cepat, dan efisien.</p>
            <hr class="my-4 border-light">
            <p class="fs-5 mb-4">Pantau dan kelola temuan serta penutupan dengan sistem yang terintegrasi untuk semua jenis audit.</p>
            <a href="{{ route('findings.index') }}" class="btn btn-warning btn-lg shadow">Lihat Temuan dan Closing</a>
        </div>
    </div>

    

    <!-- Main Features -->
    <div class="container mt-5 pb-5">
        <div class="row text-center">
            <div class="col-12 col-md-4 mb-3">
                <div class="feature-card card shadow border-0">
                    <div class="card-body">
                        <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                        <h5 class="card-title">GMP Audit</h5>
                        <p class="card-text">Pantau temuan GMP secara real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="feature-card card shadow border-0">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">K3 Audit</h5>
                        <p class="card-text">Kelola audit Kesehatan dan Keselamatan Kerja.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="feature-card card shadow border-0">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">5R Audit</h5>
                        <p class="card-text">Atur dan evaluasi audit 5R dengan mudah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="container mt-2 mb-5">
        <div class="text-center">
            <h2 class="fw-bold text-success">Mengapa Memilih GMP K3 5R App?</h2>
            <p class="fs-5 text-muted">Aplikasi kami dirancang untuk mempermudah pengelolaan GMP, K3, dan 5R dengan fitur-fitur unggulan.</p>
        </div>
        <div class="row mt-4 align-items-center">
            <div class="col-12 col-md-6 d-none d-md-block">
                <img src="{{ asset('image/animasi1.gif') }}" class="img-fluid illustration-img" alt="GMP App Illustration">
            </div>
            <div class="col-12 col-md-6">
                <ul class="list-group list-group-flush fs-5">
                    <li class="list-group-item"><i class="fas fa-check text-success"></i> Antarmuka yang intuitif dan mudah digunakan</li>
                    <li class="list-group-item"><i class="fas fa-check text-success"></i> Data tersimpan dengan aman dan terenkripsi</li>
                    <li class="list-group-item"><i class="fas fa-check text-success"></i> Akses kapan saja dan di mana saja</li>
                    <li class="list-group-item"><i class="fas fa-check text-success"></i> Notifikasi dan pengingat otomatis</li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('findings.index') }}" class="btn btn-lg btn-success shadow px-4">Mulai Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Optimized styles for home page */
        .welcome-section { 
            transition: transform 0.2s ease; 
            border-radius: 12px;
        }
        .welcome-section:hover { 
            transform: translateY(-2px); 
        }
        
        .feature-card { 
            transition: all 0.3s ease; 
            border-radius: 12px;
        }
        .feature-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .illustration-img { 
            margin-left: 60px; 
            max-width: 100%;
            height: auto;
        }
        
        .list-group-item { 
            border: none; 
            padding: 0.75rem 0;
            background: transparent;
        }
        
        .list-group-item i { 
            margin-right: 0.5rem; 
            width: 20px;
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .welcome-section { 
                padding: 2rem !important; 
            }
            .display-5 { 
                font-size: 2rem; 
            }
            .illustration-img { 
                margin-left: 0; 
            }
        }
        
        @media (max-width: 576px) {
            .welcome-section { 
                padding: 1.5rem !important; 
            }
            .display-5 { 
                font-size: 1.75rem; 
            }
            .lead { 
                font-size: 1rem; 
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000,
                    autohide: true
                });
                toast.show();
            });
        });
    </script>
@endsection
