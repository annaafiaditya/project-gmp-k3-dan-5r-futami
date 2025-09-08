@extends('layout')

@section('content')

<div class="container mt-4 pb-4">
    <h4 class="mb-4"><i class="fas fa-plus-circle"></i> Tambah Finding</h4>

    <div class="card p-4 shadow-sm mx-auto create-form-card">
        <form action="{{ route('findings.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Foto :</label>
                        <div class="mb-2">
                            <img id="image-preview" src="#" alt="Preview Gambar" class="img-thumbnail preview-image">
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen :</label>
                        <select name="department" class="form-select" required>
                            <option value="">Pilih Departemen</option>
                            <option value="AG">AG</option>
                            <option value="Engineering">Engineering</option>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Produksi">Produksi</option>
                            <option value="QA">QA</option>
                            <option value="Warehouse">Warehouse</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auditor :</label>
                        <select name="auditor" class="form-select" required>
                            <option value="">Pilih Auditor</option>
                            <option value="AG">AG</option>
                            <option value="Engineering">Engineering</option>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Produksi">Produksi</option>
                            <option value="QA">QA</option>
                            <option value="Warehouse">Warehouse</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jenis Audit :</label>
                        <select name="jenis_audit" id="jenis_audit" class="form-select" required onchange="updateKriteria()">
                            <option value="">Pilih Jenis Audit</option>
                            <option value="GMP">GMP</option>
                            <option value="K3">K3</option>
                            <option value="5R">5R</option>
                        </select>
                    </div>
                    <div class="mb-3" id="kriteria-container">
                        <!-- Kriteria akan diisi dinamis oleh JS -->
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun :</label>
                        <select name="year" class="form-select" required>
                            <option value="">Pilih Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year->year }}" {{ $year->is_active ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Week ke - :</label>
                        <select name="week" class="form-select" required>
                            @for ($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}">Week {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi :</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('findings.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>


    <style>
        /* Optimized styles for create form */
        .create-form-card {
            border-radius: 12px;
            transition: box-shadow 0.3s ease;
        }
        
        .create-form-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        
        .preview-image {
            max-height: 200px;
            display: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        /* Responsive optimizations */
        @media (max-width: 768px) {
            .create-form-card {
                padding: 1.5rem !important;
            }
            
            .preview-image {
                max-height: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .create-form-card {
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
                        <option value="Pest">Pest</option>
                        <option value="Infrastruktur">Infrastruktur</option>
                        <option value="Lingkungan">Lingkungan</option>
                        <option value="Personal Behavior">Personal Behavior</option>
                        <option value="Cleaning">Cleaning</option>
                    </select>`;
            } else if (jenis === 'K3') {
                html = `<label class="form-label">Kriteria K3 :</label>
                    <select name="kriteria" class="form-select" required>
                        <option value="Kondisi - Tidak Aman">Kondisi - Tidak Aman</option>
                        <option value="Prilaku - Tidak Aman">Prilaku - Tidak Aman</option>
                    </select>`;
            } else if (jenis === '5R') {
                html = `<label class="form-label">Kriteria 5R :</label>
                    <select name="kriteria" class="form-select" required>
                        <option value="Ringkas">Ringkas</option>
                        <option value="Rapi">Rapi</option>
                        <option value="Resik">Resik</option>
                        <option value="Rawat">Rawat</option>
                        <option value="Rajin">Rajin</option>
                    </select>`;
            } else {
                html = '';
            }
            container.innerHTML = html;
        }
    </script>


@endsection
