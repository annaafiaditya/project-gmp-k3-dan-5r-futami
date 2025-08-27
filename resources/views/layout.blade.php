<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>GMP K3 5R - App</title>
    <style>
        /* Optimized layout styles */
        html,
        body {
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            flex: 1;
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .nav-link {
            font-size: 16px;
            font-weight: 500;
            color: #333 !important;
            transition: all 0.3s ease;
            padding: 8px 12px !important;
            border-radius: 6px;
        }

        .nav-link:hover {
            color: #28a745 !important;
            background-color: rgba(40, 167, 69, 0.1);
            transform: translateY(-1px);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 8px 0;
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-top: auto;
            border-top: 1px solid #ddd;
        }

        .footer a {
            color: #28a745;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #1e7e34;
            text-decoration: underline;
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .nav-link {
                font-size: 14px;
                padding: 6px 10px !important;
            }
            
            .navbar-brand {
                font-size: 18px;
            }
        }
        
        @media (max-width: 576px) {
            .nav-link {
                font-size: 13px;
                padding: 5px 8px !important;
            }
            
            .navbar-brand {
                font-size: 16px;
            }
        }
    </style>
</head>

<body data-user-role="{{ auth()->user()->role ?? 'guest' }}">
    <nav class="navbar navbar-expand-lg align-items-center">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" style="font-size: 26px;">
                <i class="fas fa-seedling me-2" style="color: #28a745;"></i> GMP K3 5R App
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('findings.index') }}"><i class="fas fa-list"></i> Data
                            Finding & Closing</a>
                    </li>
                    <li class="nav-item mx-1 dropdown">
                        @php
                            $notifCount = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                            $recentNotifs = \App\Models\Notification::where('user_id', auth()->id())
                                ->orderBy('is_read')
                                ->orderByDesc('created_at')
                                ->limit(5)
                                ->get();
                        @endphp
                        <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i> Notifikasi
                            @if($notifCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $notifCount }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:320px;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Terbaru</span>
                                <form method="POST" action="{{ route('notifications.markAllRead') }}" class="m-0 p-0">
                                    @csrf
                                    <button class="btn btn-link btn-sm p-0">Tandai dibaca</button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @forelse($recentNotifs as $n)
                                <li>
                                    <a class="dropdown-item d-flex gap-2 {{ $n->is_read ? '' : 'fw-semibold' }}" href="{{ route('notifications.markRead', $n->id) }}">
                                        <span class="badge {{ $n->type === 'closing_uploaded' ? 'bg-success' : 'bg-primary' }}">{{ $n->type }}</span>
                                        <div>
                                            <div>{{ $n->title }}</div>
                                            <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">Tidak ada notifikasi</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat semua</a></li>
                        </ul>
                    </li>
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="{{ route('admin.verifikasi') }}">
                                <i class="fas fa-user-check"></i> Verifikasi Akun
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="{{ route('admin.years.index') }}">
                                <i class="fas fa-calendar"></i> Manajemen Tahun
                            </a>
                        </li>
                    @endif
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-user"></i> Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} GMP K3 5R App. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
