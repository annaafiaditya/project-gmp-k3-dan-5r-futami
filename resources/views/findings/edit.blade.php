@extends('layout')

@section('content')
<div class="container mt-4 pb-4">
    <h4 class="mb-4"><i class="fas fa-edit"></i> Edit Finding</h4>
    @if(Session::has('success'))
        <div class="alert alert-success mt-2"> <i class="fas fa-check-circle"></i> {{ Session::get('success') }} </div>
    @endif
    <div class="card p-4 shadow-sm mx-auto edit-form-card">
        <form action="{{ route('findings.update', $finding->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Foto :</label>
                        <div class="mb-2">
                            <img id="image-preview" src="{{ $finding->image ? asset('storage/' . $finding->image) : '#' }}" alt="Preview Gambar" class="img-thumbnail preview-image" {{ $finding->image ? '' : 'style="display: none;"' }}>
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen :</label>
                        <select name="department" class="form-select" required>
                            <option value="">Pilih Departemen</option>
                            <option value="AG" {{ $finding->department == 'AG' ? 'selected' : '' }}>AG</option>
                            <option value="Engineering" {{ $finding->department == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="HR" {{ $finding->department == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="IT" {{ $finding->department == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Produksi" {{ $finding->department == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="QA" {{ $finding->department == 'QA' ? 'selected' : '' }}>QA</option>
                            <option value="Warehouse" {{ $finding->department == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auditor :</label>
                        <select name="auditor" class="form-select" required>
                            <option value="">Pilih Auditor</option>
                            <option value="AG" {{ $finding->auditor == 'AG' ? 'selected' : '' }}>AG</option>
                            <option value="Engineering" {{ $finding->auditor == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="HR" {{ $finding->auditor == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="IT" {{ $finding->auditor == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Produksi" {{ $finding->auditor == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="QA" {{ $finding->auditor == 'QA' ? 'selected' : '' }}>QA</option>
                            <option value="Warehouse" {{ $finding->auditor == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jenis Audit :</label>
                        <select name="jenis_audit" id="jenis_audit" class="form-select" required onchange="updateKriteria()">
                            <option value="">Pilih Jenis Audit</option>
                            <option value="GMP" {{ $finding->jenis_audit == 'GMP' ? 'selected' : '' }}>GMP</option>
                            <option value="K3" {{ $finding->jenis_audit == 'K3' ? 'selected' : '' }}>K3</option>
                            <option value="5R" {{ $finding->jenis_audit == '5R' ? 'selected' : '' }}>5R</option>
                        </select>
                    </div>
                    <div class="mb-3" id="kriteria-container">
                        <!-- Kriteria akan diisi dinamis oleh JS -->
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Week ke - :</label>
                        <select name="week" class="form-select" required>
                            @for ($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}" {{ $finding->week == $i ? 'selected' : '' }}>Week {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi :</label>
                        <textarea name="description" class="form-control" rows="3" required>{{ $finding->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('findings.index') }}" class="btn btn-primary">
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
        /* Optimized styles for edit form */
        .edit-form-card {
            border-radius: 12px;
            transition: box-shadow 0.3s ease;
        }
        
        .edit-form-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        
        .preview-image {
            max-height: 200px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .edit-form-card {
                padding: 1.5rem !important;
            }
            
            .preview-image {
                max-height: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .edit-form-card {
                padding: 1rem !important;
            }
            
            .preview-image {
                max-height: 120px;
            }
        }
    </style>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        
        function updateKriteria() {
            const jenis = document.getElementById('jenis_audit').value;
            const container = document.getElementById('kriteria-container');
            let html = '';
            
            if (jenis === 'GMP') {
                html = `<label class="form-label">Kriteria GMP :</label>
                    <select name="kriteria" class="form-select" required>
                        <option value="Pest" {{ $finding->kriteria == 'Pest' ? 'selected' : '' }}>Pest</option>
                        <option value="Infrastruktur" {{ $finding->kriteria == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                        <option value="Lingkungan" {{ $finding->kriteria == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                        <option value="Personal Behavior" {{ $finding->kriteria == 'Personal Behavior' ? 'selected' : '' }}>Personal Behavior</option>
                        <option value="Cleaning" {{ $finding->kriteria == 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                    </select>`;
            } else if (jenis === 'K3') {
                html = `<label class="form-label">Kriteria K3 :</label>
                    <select name="kriteria" class="form-select" required>
                        <option value="Kondisi - Tidak Aman" {{ $finding->kriteria == 'Kondisi - Tidak Aman' ? 'selected' : '' }}>Kondisi - Tidak Aman</option>
                        <option value="Prilaku - Tidak Aman" {{ $finding->kriteria == 'Prilaku - Tidak Aman' ? 'selected' : '' }}>Prilaku - Tidak Aman</option>
                    </select>`;
            } else if (jenis === '5R') {
                html = `<label class="form-label">Kriteria 5R :</label>
                    <select name="kriteria" class="form-select" required>
                        <option value="Ringkas" {{ $finding->kriteria == 'Ringkas' ? 'selected' : '' }}>Ringkas</option>
                        <option value="Rapi" {{ $finding->kriteria == 'Rapi' ? 'selected' : '' }}>Rapi</option>
                        <option value="Resik" {{ $finding->kriteria == 'Resik' ? 'selected' : '' }}>Resik</option>
                        <option value="Rawat" {{ $finding->kriteria == 'Rawat' ? 'selected' : '' }}>Rawat</option>
                        <option value="Rajin" {{ $finding->kriteria == 'Rajin' ? 'selected' : '' }}>Rajin</option>
                    </select>`;
            } else {
                html = '';
            }
            container.innerHTML = html;
            
            // Set selected value jika ada
            if ('{{ $finding->kriteria }}') {
                const select = container.querySelector('select');
                if (select) select.value = '{{ $finding->kriteria }}';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updateKriteria();
        });
    </script>
@endsection
