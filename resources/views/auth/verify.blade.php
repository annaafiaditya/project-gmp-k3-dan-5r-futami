<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

    <style>
        body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #188150);
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

        .form-control {
            font-size: 1.15rem;
            border-radius: 0.5rem;
            margin-top: 0.25rem;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 0.25);
        }

        button.btn-primary {
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 0.5rem;
            width: 100%;
            padding: 0.6rem 0;
            transition: background-color 0.3s ease;
        }

        button.btn-primary:hover {
            background-color: #0b5ed7;
        }

        /* Alerts spacing */
        .alert {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
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
            button.btn-primary {
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

            label {
                font-size: 1rem;
            }

            .form-control {
                font-size: 1rem;
            }

            button.btn-primary {
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
            button.btn-primary {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>

    <div class="card shadow-sm">
        <h3>Verifikasi OTP</h3>

        @if (session('otp_code'))
            <div class="alert alert-info text-center">
                Kode OTP Anda : <strong>{{ session('otp_code') }}</strong>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf
            <div class="mb-3">
                <label for="otpInput">Masukkan Kode OTP</label>
                <input type="text" class="form-control" id="otpInput" name="otp" required autofocus />
            </div>
            @if (session('otp_expires_at'))
                @php
                    $expiresIn = \Carbon\Carbon::parse(session('otp_expires_at'))->diffInSeconds(now());
                @endphp
                <div class="alert alert-warning text-center">
                    Kode kedaluwarsa dalam <span id="timer">{{ gmdate('i:s', $expiresIn) }}</span>
                </div>
            @endif

            <button class="btn btn-primary" type="submit">Verifikasi</button>
        </form>
    </div>

    <!-- Modal Kedaluwarsa OTP -->
    <div class="modal fade" id="otpExpiredModal" tabindex="-1" aria-labelledby="otpExpiredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="otpExpiredLabel">OTP Kedaluwarsa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-dark">
                    Kode OTP sudah kedaluwarsa. Silakan minta ulang kode verifikasi.
                </div>
                <div class="modal-footer">
                    <a href="{{ route('password.request') }}" class="btn btn-primary">Minta OTP Ulang</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let timerElement = document.getElementById("timer");
        if (timerElement) {
            let timeParts = timerElement.textContent.split(":");
            let minutes = parseInt(timeParts[0]);
            let seconds = parseInt(timeParts[1]);
            let totalSeconds = minutes * 60 + seconds;

            let countdown = setInterval(() => {
                totalSeconds--;

                let mins = Math.floor(totalSeconds / 60);
                let secs = totalSeconds % 60;
                timerElement.textContent =
                    `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

                if (totalSeconds <= 0) {
                    clearInterval(countdown);
                    timerElement.textContent = "00:00";

                    // Tampilkan modal Bootstrap
                    const otpModal = new bootstrap.Modal(document.getElementById('otpExpiredModal'));
                    otpModal.show();
                }
            }, 1000);
        }
    </script>


</body>

</html>
