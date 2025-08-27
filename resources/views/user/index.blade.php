@extends('layout')

@section('content')
    <style>
        /* Optimized styles for user profile */
        .profile-card,
        .edit-form {
            min-height: 100%;
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-card:hover,
        .edit-form:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .form-label i {
            margin-right: 6px;
            color: #007bff;
        }

        .badge-role {
            font-size: 0.9rem;
            background: linear-gradient(45deg, #00b09b, #96c93d);
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            text-align: center;
            margin-top: 10px;
            font-weight: 500;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-card-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .logout-btn {
            border-radius: 20px;
            color: #dc3545;
            background: transparent;
            border: 2px solid #dc3545;
            padding: 8px 20px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            color: white;
            background-color: #dc3545;
            transform: translateY(-2px);
        }

        .profile-avatar-container {
            position: relative;
            display: inline-block;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        /* Responsive optimizations */
        @media (max-width: 768px) {
            .profile-avatar {
                width: 100px;
                height: 100px;
            }
            
            .profile-card-content h3 {
                font-size: 1.5rem;
            }
            
            .profile-card-content p {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
            
            .badge-role {
                font-size: 0.8rem;
                padding: 6px 16px;
            }
        }
    </style>

    <div class="container">
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
    </div>

    <div class="container mt-5 pb-5">
        <div class="row justify-content-center align-items-stretch g-4">
            {{-- Profil Card --}}
            <div class="col-lg-5 d-flex">
                <div class="card p-4 profile-card w-100 d-flex flex-column">
                    {{-- Konten Profil --}}
                    <div class="profile-card-content">
                        <div class="profile-avatar-container mb-4 mt-1">
                            <!-- Foto Profil -->
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ffc107&color=000&size=128"
                                alt="Avatar" class="profile-avatar">
                        </div>

                        <h3 class="fw-bold mb-1">{{ auth()->user()->name }}</h3>
                        <p class="text-muted mb-2" style="font-size: 18px;">{{ auth()->user()->email }}</p>

                        {{-- Role --}}
                        <span class="badge badge-role mx-auto mt-1">
                            {{ ucfirst(auth()->user()->department) }}
                        </span>
                    </div>

                    {{-- Tombol Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-auto text-center">
                        @csrf
                        <button type="submit" class="logout-btn" style="margin-bottom: 65px;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Form Edit --}}
            <div class="col-lg-7 d-flex">
                <div class="card p-4 shadow-sm edit-form w-100 form-section">
                    <div>
                        <form action="{{ route('user.update', auth()->user()->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user"></i> Nama</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }} "
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control border-end-0"
                                        placeholder="biarkan kosong jika tidak diubah">
                                    <span class="input-group-text bg-white border-start-0"
                                        style="border-left: none; cursor: pointer;" id="togglePassword">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-building"></i> Departemen</label>
                                <select name="department" class="form-control" required>
                                    @php
                                        $departments = ['AG', 'Engineering', 'HR', 'IT', 'Produksi', 'QA', 'Warehouse'];
                                    @endphp
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}" {{ auth()->user()->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-user-tag"></i> Role</label>
                                <select name="role" class="form-control" required>
                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <option value="admin" {{ auth()->user()->role == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    @endif
                                    @if (auth()->check() && auth()->user()->role === 'user')
                                        <option value="user" {{ auth()->user()->role == 'user' ? 'selected' : '' }}>User
                                        </option>
                                    @endif
                                    @if (auth()->check() && auth()->user()->role === 'guest')
                                        <option value="guest" {{ auth()->user()->role == 'guest' ? 'selected' : '' }}>
                                            Guest
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success px-4">
                                    <i class="fas fa-save "></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        //toast notification
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen toast
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            // Aktifkan dan atur timeout
            toastElList.forEach(function(toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 3000,
                    autohide: true
                });
                toast.show();
            });
        });


        // toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
@endsection
