<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Lupa Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background: #f0f2f5;
        }

        body {
            background: linear-gradient(135deg, #188150);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.2rem rgb(189 197 209 / 0.2);
            width: 550px;
            padding: 2.5rem 3rem;
            background: white;
        }

        h5 {
            font-weight: 700;
            color: #212529;
            text-align: center;
            margin-bottom: 1.75rem;
            font-size: 2rem;
        }

        label {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }

        .form-control {
            font-size: 1.15rem;
            /* input cukup besar */
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgb(25 135 84 / 0.25);
        }

        button.btn-success {
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        button.btn-success:hover {
            background-color: #157347;
        }

        .text-danger {
            font-size: 0.9rem;
        }

        /* Responsive tweaks */
        @media (max-width: 576px) {
            .card {
                max-width: 95%;
                padding: 2rem 2rem;
            }

            h5 {
                font-size: 1.75rem;
            }

            label {
                font-size: 1rem;
            }

            .form-control {
                font-size: 1rem;
            }

            button.btn-success {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="card shadow-sm">
        <h5>Lupa Password</h5>
        <form method="POST" action="{{ route('password.sendOtp') }}">
            @csrf
            <div class="mb-3">
                <label for="emailInput" class="form-label">Email</label>
                <input type="email" class="form-control mt-1" id="emailInput" name="email" required autofocus
                    placeholder="Masukkan email anda" />
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-success w-100 mt-1" type="submit">Kirim OTP</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
