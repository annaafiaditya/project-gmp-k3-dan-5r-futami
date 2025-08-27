@extends('layout')

@section('content')
    <div class="container mt-4 mb-5">
        <h4 class="mb-3"><i class="fas fa-upload"></i> Upload Foto Closing</h4>
        <div class="card p-4 shadow-sm upload-photo-card">
            <form id="uploadForm" action="{{ route('findings.uploadPhotoSubmit', $finding->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <!-- Hidden inputs to preserve current filters -->
                <input type="hidden" name="year" value="{{ request('year') }}">
                <input type="hidden" name="week" value="{{ request('week') }}">
                <input type="hidden" name="jenis_audit" value="{{ request('jenis_audit') }}">
                <input type="hidden" name="department" value="{{ request('department') }}">
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="image2" class="form-label"><i class="fas fa-image"></i> Foto Closing :</label>
                            <div class="mb-2">
                                <img id="closing-preview" src="#" alt="Preview Gambar" class="img-thumbnail preview-image">
                            </div>
                            <input type="file" name="image2" class="form-control" accept="image/*"
                                onchange="previewClosing(event)" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="catatan_penyelesaian" class="form-label"><i class="fas fa-sticky-note"></i> Catatan Penyelesaian:</label>
                            <textarea name="catatan_penyelesaian" class="form-control" rows="5" placeholder="Tulis catatan penyelesaian di sini..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('findings.index', request()->only(['year', 'week', 'jenis_audit', 'department'])) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Optimized styles for upload photo */
        .upload-photo-card {
            border-radius: 12px;
            transition: box-shadow 0.3s ease;
        }
        
        .upload-photo-card:hover {
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
            .upload-photo-card {
                padding: 1.5rem !important;
            }
            
            .preview-image {
                max-height: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .upload-photo-card {
                padding: 1rem !important;
            }
            
            .preview-image {
                max-height: 120px;
            }
        }
    </style>

    <script>
        function previewClosing(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('closing-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
