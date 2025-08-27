@extends('layout')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between flex-wrap">
            <h4 class="mb-3 mb-md-0"><i class="fas fa-list"></i> Daftar Akun Belum Terverifikasi</h4>
        </div>

        @if (session('success'))
            <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-3" style="z-index: 1100">
                <div class="toast show align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm mt-3 verification-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Departemen</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-medium">{{ $user->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                {{ strtoupper(substr($user->email, 0, 2)) }}
                                            </div>
                                            <span class="text-break">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $user->department }}</span></td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.verifikasi.user', $user->id) }}" method="POST"
                                            class="d-inline verify-form">
                                            @csrf
                                            <button type="button" class="btn btn-success btn-sm btn-verify">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                                        <p class="mb-0">Semua akun sudah diverifikasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memverifikasi akun ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmBtn">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Optimized styles for verification page */
        .verification-card {
            border-radius: 12px;
            transition: box-shadow 0.3s ease;
        }
        
        .verification-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        
        .table td {
            vertical-align: middle;
            border-color: #f8f9fa;
        }
        
        .btn-verify {
            transition: all 0.3s ease;
        }
        
        .btn-verify:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .user-avatar {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.querySelector('.toast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 3000,
                    autohide: true
                });
                toast.show();
            }

            let formToSubmit = null;

            document.querySelectorAll('.btn-verify').forEach(button => {
                button.addEventListener('click', function() {
                    formToSubmit = this.closest('form');
                    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                    confirmModal.show();
                });
            });

            document.getElementById('confirmBtn').addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });
        });
    </script>
@endsection
