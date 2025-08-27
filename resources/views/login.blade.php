<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GMP - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Optimized login styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #188150, #2e7d32);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            animation: fadeIn 0.8s ease-in;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            animation: slideUp 0.6s ease-out;
        }

        .login-image {
            flex: 1;
            background-image: url('{{ asset('image/login-form-image.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 100%;
        }

        .login-form {
            flex: 1;
            padding: 50px 40px;
            background: #f9fdf9;
        }

        .login-form h2 {
            text-align: center;
            color: #388e3c;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        .form-control,
        .input-group-text {
            padding: 12px 16px;
            font-size: 1rem;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #66bb6a;
            box-shadow: 0 0 0 0.2rem rgba(102, 187, 106, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #66bb6a, #2e7d32);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .btn-primary:hover::before {
            left: 125%;
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(56, 142, 60, 0.5);
            transform: translateY(-2px);
        }


        .toggle-password {
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            font-size: 1.2rem;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .toggle-password:hover {
            color: #2e7d32;
        }

        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 12px;
            margin-bottom: 16px;
        }

        .text-center span a {
            color: #388e3c;
            transition: color 0.3s ease;
        }

        .text-center span a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                border-radius: 16px;
            }

            .login-image {
                display: none;
            }

            .login-form {
                padding: 40px 20px;
            }

            .login-form h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-image d-none d-md-block"></div>

        <div class="login-form">
            <div class="container">
                @if (Session::get('success'))
                    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">Success</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{ session('success') }} <i class="fas fa-check-circle me-2"></i>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                        <div class="toast show bg-danger text-white" role="alert" aria-live="assertive"
                            aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">Error</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{ session('error') }} <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Session::get('canAccess'))
                    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                        <div class="toast show bg-warning text-dark" role="alert" aria-live="assertive"
                            aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">Peringatan</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{ Session::get('canAccess') }}<i class="bi bi-exclamation-triangle-fill ms-2"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <h2>Login</h2>
            <form action="{{ route('login.auth') }}" method="POST">
                @csrf
                <div class="input-group mb-4 position-relative">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input type="email" class="form-control" name="email" placeholder="Masukan email" required />
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="input-group mb-4 position-relative">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" id="password" class="form-control" name="password"
                        placeholder="Masukan password" required />
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>

                  <div class="text-center mt-4">
                    <span><a href="{{ route('password.request') }}" class="text-success fw-semibold">Lupa Password?</a></span>
                </div>

                <div class="text-center mt-2">
                    <span>Belum punya akun? <a href="{{ route('register') }}" class="text-success fw-semibold">Register
                            disini</a></span>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        //toast notification
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen toast
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            // Aktifkan dan atur timeout
            toastElList.forEach(function(toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 3000, // waktu delay dalam milidetik (3 detik)
                    autohide: true // supaya otomatis hide
                });
                toast.show();
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
