<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

    <!-- Icons (Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#188150);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.2rem rgb(189 197 209 / 0.2);
            width: 100%;
            max-width: 550px;
            padding: 2.5rem 3rem;
            margin: 1rem;
        }


        h3 {
            font-weight: 700;
            font-size: 2rem;
            color: #212529;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        label {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }

        .input-group .form-control {
            font-size: 1.15rem;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgb(25 135 84 / 0.25);
        }

        .input-group-text {
            background: #fff;
            border-radius: 0 0.5rem 0.5rem 0;
            cursor: pointer;
            border-color: #ced4da;
        }

        button.btn-success {
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 0.5rem;
            width: 100%;
            padding: 0.6rem 0;
            transition: background-color 0.3s ease;
        }

        button.btn-success:hover {
            background-color: #157347;
        }

        @media (max-width: 768px) {
            .card {
                padding: 2rem;
            }

            h3 {
                font-size: 1.6rem;
            }

            label,
            .form-control,
            .input-group-text,
            button.btn-success {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .card {
                padding: 1.8rem;
            }

            h3 {
                font-size: 1.5rem;
            }

            .form-control {
                font-size: 1rem;
            }

            .input-group-text {
                font-size: 1rem;
            }

            button.btn-success {
                font-size: 1rem;
            }
        }

        @media (max-width: 400px) {
            .card {
                padding: 1.5rem;
            }

            h3 {
                font-size: 1.4rem;
            }

            label,
            .form-control,
            .input-group-text,
            button.btn-success {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <div class="card shadow-sm">
        <h3>Reset Password</h3>

        @if (Session::get('success'))
            <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }} <i class="fas fa-check-circle me-2"></i>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <div class="mb-3">
                <label for="passwordInput">Password Baru</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="passwordInput" name="password" required
                        autocomplete="new-password" />
                    <span class="input-group-text" onclick="togglePassword('passwordInput', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label for="passwordConfirmInput">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="passwordConfirmInput" name="password_confirmation"
                        required autocomplete="new-password" />
                    <span class="input-group-text" onclick="togglePassword('passwordConfirmInput', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
            <button class="btn btn-success" type="submit">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId, el) {
            const input = document.getElementById(fieldId);
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        setTimeout(() => {
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.hide();
            }
        }, 3000);
    </script>
</body>

</html>
