@extends('layout')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Tambah Tahun Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.years.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year', now()->year + 1) }}" 
                                       min="2025" max="2100" required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tahun yang akan ditambahkan (minimal 2025)</small>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Informasi:</h6>
                                <ul class="mb-0">
                                    <li>Tahun baru akan ditambahkan tanpa data apapun</li>
                                    <li>Data finding dapat ditambahkan sesuai kebutuhan</li>
                                    <li>Tahun yang sudah ada tidak dapat ditambahkan lagi</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.years.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Tambah Tahun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 