@extends('layout')

@section('content')

<div class="container mt-4 pb-4">
    <h4 class="mb-3"><i class="fas fa-user-edit"></i> Edit Profile</h4>

    @if(Session::get('success'))
        <div class="alert alert-success mt-2"> {{ session::get('success') }} </div>
    @endif

    <div class="card p-4 shadow-sm edit-user-card">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Nama</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukan password baru">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user-tag"></i> Role</label>
                        <select name="role" class="form-select" required>
                            <option disabled>Pilih role</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="guest" {{ $user->role == 'guest' ? 'selected' : '' }}>Guest</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('user.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-user-card {
        border-radius: 12px;
        transition: box-shadow 0.3s ease;
    }
    
    .edit-user-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    /* Responsive optimizations */
    @media (max-width: 768px) {
        .edit-user-card {
            padding: 1.5rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .edit-user-card {
            padding: 1rem !important;
        }
    }
</style>

@endsection 
