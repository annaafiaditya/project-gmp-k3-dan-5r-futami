<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GMP K3 5R - App</title>
    <link rel="icon" href="/image/futami.jpg" type="image/jpg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Optimized landing page styles */
        body {
            font-family: 'Poppins', sans-serif;
            color: #1e1e1e;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: #198754;
            font-size: 1.5rem;
        }

        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/image/landing.gif');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            border-radius: 20px;
            margin: 30px auto;
            max-width: 95%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hero h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        @media (min-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }
        }

        .btn-main {
            background-color: #198754;
            color: #fff;
            padding: 10px 25px;
            font-size: 1rem;
            border: none;
            border-radius: 25px;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
            transition: all 0.3s ease;
        }

        .btn-main:hover {
            background-color: #157347;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(25, 135, 84, 0.4);
        }

        .section-title {
            text-align: center;
            margin: 40px 0 25px;
            font-weight: 700;
            color: #198754;
        }

        .footer {
            background: linear-gradient(45deg, #157347, #198754);
            padding: 12px 0;
            text-align: center;
            color: #ffffff;
            border-top: none;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .btn-outline-green {
            border-color: #198754;
            color: #198754;
            transition: all 0.3s ease;
        }

        .btn-outline-green:hover {
            background-color: #198754;
            color: white;
            transform: translateY(-1px);
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
                margin: 20px auto;
            }
            
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero {
                padding: 40px 0;
                border-radius: 15px;
            }
            
            .hero h1 {
                font-size: 1.5rem;
            }
            
            .btn-main {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand">
                <img src="/image/futami.jpg" alt="Futami" height="30" class="me-2 rounded">
                GMP K3 5R App
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-md-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-green rounded-pill px-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-person-plus-fill me-1"></i> Bergabung
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-white">
        <div class="container">
            <h1 class="mb-3">MONITORING <span class="text-warning">GMP K3 5R</span></h1>
            <h5 class="mb-4">
                Platform digital untuk memantau, mencatat, dan menindaklanjuti temuan Good Manufacturing Practices, Kesehatan dan Keselamatan Kerja, serta 5R<br>
                secara akurat, cepat, dan terintegrasi dalam satu sistem.
            </h5>
            <a href="{{ route('login') }}" class="btn btn-main mt-3">
                <i class="bi bi-arrow-right-square"></i> Masuk Sekarang
            </a>
        </div>
    </section>


    <!-- Fitur Utama -->
    <div class="container my-5 pb-5">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="text-center mb-5 text-muted">GMP K3 5R App hadir dengan fitur-fitur penting untuk mendukung monitoring dan
            perbaikan secara efisien.</p>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Pantau Temuan</h5>
                        <p class="card-text">Lacak seluruh temuan langsung dari dashboard real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Kelola Perbaikan</h5>
                        <p class="card-text">Sederhanakan proses perbaikan dan dokumentasi closing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-pie fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Lihat Statistik</h5>
                        <p class="card-text">Analisa tren dan kinerja langsung dari tampilan visual yang jelas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- What Client Say -->
    <div class="container my-5">
        <h2 class="section-title">Apa Kata Pengguna</h2>
        <p class="text-center mb-5 text-muted">Pendapat mereka yang telah menggunakan GMP, K3 dan 5R App untuk kegiatan monitoring
            dan audit harian.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100 p-4">
                    <div class="d-flex align-items-start mb-3">
                        <img src="https://i.pravatar.cc/80?img=1" alt="User 1" class="rounded-circle me-3"
                            width="60" height="60">
                        <div>
                            <h6 class="mb-0">Rina A.</h6>
                            <small class="text-muted">QA Supervisor</small>
                        </div>
                    </div>
                    <p class="card-text">“GMP K3 5R App sangat membantu kami dalam memonitor dan menindaklanjuti temuan harian
                        GMP, K3, dan 5R dengan lebih cepat dan terstruktur.”</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100 p-4">
                    <div class="d-flex align-items-start mb-3">
                        <img src="https://i.pravatar.cc/80?img=2" alt="User 2" class="rounded-circle me-3"
                            width="60" height="60">
                        <div>
                            <h6 class="mb-0">Budi S.</h6>
                            <small class="text-muted">Produksi</small>
                        </div>
                    </div>
                    <p class="card-text">“Tampilan sederhana, mudah digunakan, dan sangat efisien. Saya bisa langsung
                        melihat apa yang harus ditindaklanjuti.”</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100 p-4">
                    <div class="d-flex align-items-start mb-3">
                        <img src="https://i.pravatar.cc/80?img=3" alt="User 3" class="rounded-circle me-3"
                            width="60" height="60">
                        <div>
                            <h6 class="mb-0">Sari M.</h6>
                            <small class="text-muted">Engineering</small>
                        </div>
                    </div>
                    <p class="card-text">“Laporan otomatis dan visualisasi tren membuat pekerjaan kami lebih mudah dan
                        terukur. Sangat direkomendasikan!”</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="container my-5">
        <div class="bg-success text-white text-center rounded-4 p-5 shadow-sm">
            <h2 class="fw-bold mb-3">Siap Memulai Monitoring GMP Lebih Baik?</h2>
            <p class="mb-4">Gabung sekarang dan jadikan proses pemantauan dan perbaikan lebih efisien, akurat, dan
                terintegrasi!</p>
            <a href="{{ route('login') }}" class="btn btn-light text-success fw-semibold px-4 py-2 rounded-pill me-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-light fw-semibold px-4 py-2 rounded-pill">
                <i class="bi bi-person-plus-fill me-1"></i> Daftar Gratis
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 GMP K3 5R App - All Rights Reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
