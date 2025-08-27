@extends('layout')

@section('content')

    <div class="d-flex flex-wrap mt-4 container align-items-center justify-content-between">
        <h4 class="mt-1"><i class="fas fa-list"></i> Data Finding & Closing</h4>
    </div>

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

    @auth
        @if (auth()->user()->role === 'admin')
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $countFindings }}</h3>
                                <p>Total Finding</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $countStatus }}</h3>
                                <p>Total Closing</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $countUsers }}</h3>
                                <p>Total User</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <div class="container">
        <div class="row mb-3">
            <!-- Filter Section -->
            <div class="col-12 col-lg-8">
                <form method="GET" action="{{ route('findings.index') }}" class="filter-form" id="searchForm">
                    <div class="row g-2">
                        <div class="col-6 col-sm-3 col-md-2">
                            <select name="year" class="form-select form-select-sm">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                        </div>
                        <div class="col-6 col-sm-3 col-md-2">
                            <select name="week" class="form-select form-select-sm">
                        <option value="">Semua Minggu</option>
                        @for ($i = 1; $i <= 52; $i++)
                                    <option value="{{ $i }}" {{ request('week') == $i ? 'selected' : '' }}>Week {{ $i }}</option>
                        @endfor
                    </select>
                        </div>
                        <div class="col-6 col-sm-3 col-md-2">
                            <select name="jenis_audit" class="form-select form-select-sm">
                        <option value="">Semua Audit</option>
                        <option value="GMP" {{ request('jenis_audit') == 'GMP' ? 'selected' : '' }}>GMP</option>
                        <option value="K3" {{ request('jenis_audit') == 'K3' ? 'selected' : '' }}>K3</option>
                        <option value="5R" {{ request('jenis_audit') == '5R' ? 'selected' : '' }}>5R</option>
                    </select>
                        </div>
                        <div class="col-6 col-sm-3 col-md-2">
                            <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Belum Closing (Open)</option>
                        <option value="Close" {{ request('status') == 'Close' ? 'selected' : '' }}>Sudah Closing (Close)</option>
                    </select>
                        </div>
                        <div class="col-6 col-sm-3 col-md-3">
                            <select name="department" id="department" class="form-select form-select-sm">
                        <option value="">Semua Departemen</option>
                        @foreach (['AG', 'Engineering', 'HR', 'IT', 'Produksi', 'QA', 'Warehouse'] as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3">
                            <button type="submit" id="searchBtn" class="btn btn-dark btn-sm w-100">
                                <span id="searchText"><i class="fas fa-search"></i> Cari</span>
                                <span id="searchSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 col-lg-4">
                <div class="d-flex gap-2 flex-wrap justify-content-start justify-content-lg-end mt-3 mt-lg-0">
            @auth
                @if (auth()->user()->role === 'user' || auth()->user()->role === 'admin')
                    <a href="{{ route('findings.exportPDF', ['week' => request('week'), 'department' => request('department'), 'jenis_audit' => request('jenis_audit'), 'year' => request('year')]) }}"
                                id="exportPdfBtn" class="btn btn-danger btn-sm">
                        <span id="exportPdfText"><i class="fas fa-file-pdf"></i> PDF</span>
                                <span id="exportPdfSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('findings.export', ['week' => request('week'), 'department' => request('department'), 'jenis_audit' => request('jenis_audit'), 'year' => request('year')]) }}"
                                id="exportExcelBtn" class="btn btn-success btn-sm">
                        <span id="exportExcelText"><i class="fas fa-file-excel"></i> Excel</span>
                                <span id="exportExcelSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </a>
                @endif

                @if (auth()->user()->role === 'admin')
                            <a class="btn btn-primary btn-sm" href="{{ route('findings.create') }}">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                @endif
            @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Peringatan untuk export -->
    @auth
        @if (auth()->user()->role === 'user' || auth()->user()->role === 'admin')
            <div class="container mt-2">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Download mungkin akan membutuhkan waktu yang lama karena datanya besar!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endauth

    <div class="container pb-5">
        <form method="GET" action="{{ route('findings.index') }}" class="mb-3 d-flex align-items-center gap-2">
            <label for="per_page" class="form-label mb-0">Tampilkan</label>
            <select name="per_page" id="per_page" class="form-select w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <span class="ms-1">data per halaman</span>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center mx-auto custom-table">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Before</th>
                    <th style="width: 15%;">After</th>
                    <th style="width: 10%;">Departemen</th>
                    <th style="width: 10%;">Auditor</th>
                    <th style="width: 10%;">Jenis Audit</th>
                    <th style="width: 10%;">Kriteria</th>
                    <th style="width: 15%;">Deskripsi</th>
                    <th style="width: 10%;">Status</th>
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <th style="width: 10%;">Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @forelse ($findings as $no => $finding)
                    @php
                        $isHighlighted = request('highlight') == $finding->id;
                        $rowClass = $no % 2 == 0 ? 'table-light' : 'table-white';
                        if ($isHighlighted) {
                            $rowClass .= ' highlighted-row';
                        }
                    @endphp
                    <tr class="{{ $rowClass }} table-row-hover" id="finding-{{ $finding->id }}">
                        <td class="align-middle">{{ ($findings->currentPage() - 1) * $findings->perPage() + $no + 1 }}</td>
                        <td class="align-middle">
                            @php
                                $imagePath = $finding->image && Storage::disk('public')->exists($finding->image) ? asset('storage/' . $finding->image) : asset('images/no-image.png');
                            @endphp
                            <div class="image-container" onclick="window.open('{{ $imagePath }}', '_blank')" data-bs-toggle="tooltip" title="Klik untuk membuka di tab baru" style="cursor: pointer;">
                                <img src="{{ $imagePath }}" width="150" class="img-thumbnail before-after-img" alt="Foto Finding">
                                <div class="image-overlay">
                                    <i class="fas fa-external-link-alt"></i>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @if ($finding->image2)
                                <div class="d-flex flex-column align-items-center">
                                    @php
                                        $imagePath2 = $finding->image2 && Storage::disk('public')->exists($finding->image2) ? asset('storage/' . $finding->image2) : asset('images/no-image.png');
                                    @endphp
                                    <div class="image-container" onclick="window.open('{{ $imagePath2 }}', '_blank')" data-bs-toggle="tooltip" title="Klik untuk membuka di tab baru" style="cursor: pointer;">
                                    <img src="{{ $imagePath2 }}" width="150"
                                            class="img-thumbnail mb-2 before-after-img">
                                        <div class="image-overlay">
                                            <i class="fas fa-external-link-alt"></i>
                                        </div>
                                    </div>
                                    @auth
                                        @if (auth()->user()->role !== 'admin' && auth()->user()->department === $finding->department && $finding->image2)
                                            <a href="{{ route('findings.editPhotoForm', $finding->id) }}?{{ http_build_query(request()->only(['year', 'week', 'jenis_audit', 'department'])) }}"
                                                class="btn btn-sm btn-warning text-white">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                        @if ($finding->closing && $finding->closing->catatan_penyelesaian)
                                            <div class="mt-1 text-start w-100">
                                                <span class="fw-bold small">Catatan:</span><br>
                                                <span class="fst-italic small text-muted" style="font-size: 11px;">{{ $finding->closing->catatan_penyelesaian }}</span>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @else
                                @auth
                                    @if (auth()->user()->role !== 'admin' && auth()->user()->department === $finding->department)
                                        <a href="{{ route('findings.uploadPhotoForm', $finding->id) }}?{{ http_build_query(request()->only(['year', 'week', 'jenis_audit', 'department'])) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-upload"></i> Upload Foto
                                        </a>
                                    @else
                                        <span class="text-muted">Foto belum diupload</span>
                                    @endif
                                @else
                                    <span class="text-muted">Foto belum diupload</span>
                                @endauth
                            @endif
                        </td>
                        <td class="align-middle">{{ $finding->department }}</td>
                        <td class="align-middle">{{ $finding->auditor ?: '-' }}</td>
                        <td class="align-middle">
                            @if($finding->jenis_audit)
                                @php
                                    $auditColors = [
                                        'GMP' => 'bg-success text-white',
                                        'K3' => 'bg-warning text-dark',
                                        '5R' => 'bg-primary text-white'
                                    ];
                                    $colorClass = $auditColors[$finding->jenis_audit] ?? 'bg-info text-dark';
                                @endphp
                                <span class="badge {{ $colorClass }}">{{ $finding->jenis_audit }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle">{{ $finding->kriteria ?: '-' }}</td>
                        <td class="align-top text-center">{{ $finding->description ?: '-' }}</td>
                        <td class="align-middle">
                            @php
                                $statusClass = $finding->status === 'Open' ? 'bg-danger' : 'bg-success';
                            @endphp
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <button type="button" class="badge {{ $statusClass }} border-0" style="font-size:1em;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#confirmStatusModal{{ $finding->id }}">
                                    {{ $finding->status }}
                                </button>
                                <!-- Modal Konfirmasi -->
                                <div class="modal fade" id="confirmStatusModal{{ $finding->id }}" tabindex="-1" aria-labelledby="confirmStatusLabel{{ $finding->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmStatusLabel{{ $finding->id }}">Konfirmasi Ubah Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin mengubah status menjadi <strong>{{ $finding->status === 'Open' ? 'Close' : 'Open' }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('findings.toggleStatus', $finding->id) }}" method="POST" style="display:inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary">Ubah</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="badge {{ $statusClass }}">{{ $finding->status }}</span>
                            @endif
                        </td>
                        @auth
                            @if (auth()->user()->role === 'admin')
                                <td class="align-middle">
                                    <div class="d-flex flex-column flex-md-row justify-content-center">
                                        <a href="{{ route('findings.edit', $finding->id) }}"
                                            class="btn btn-sm me-md-2 mb-2 mb-md-0 p-2"
                                            style="border: 1px solid rgb(255, 149, 0); background-color: white; color: rgb(255, 149, 0);"
                                            onmouseover="this.style.backgroundColor='rgb(255, 149, 0)'; this.style.color='white';"
                                            onmouseout="this.style.backgroundColor='white'; this.style.color='rgb(255, 149, 0)';">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm"
                                            style="border: 1px solid red; background-color: white; color: red;"
                                            onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                                            onmouseout="this.style.backgroundColor='white'; this.style.color='red';"
                                            data-bs-toggle="modal" data-bs-target="#modalDelete{{ $finding->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="modalDelete{{ $finding->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-danger">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat
                                                    dibatalkan.
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('findings.delete', $finding->id) }}"
                                                        method="POST" class="w-100 d-flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary me-auto"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted">Data belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $findings->firstItem() }} - {{ $findings->lastItem() }} dari {{ $findings->total() }} data
            </div>
            <div>
                <nav>
                    <ul class="pagination pagination-sm justify-content-end mb-0 flex-wrap" style="max-width: 220px; overflow-x: auto;">
                        @if ($findings->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $findings->previousPageUrl() }}">&laquo;</a></li>
                        @endif
                        @php
                            $start = max($findings->currentPage() - 1, 1);
                            $end = min($start + 2, $findings->lastPage());
                            if ($end - $start < 2) {
                                $start = max($end - 2, 1);
                            }
                        @endphp
                        @for ($page = $start; $page <= $end; $page++)
                            <li class="page-item {{ $page == $findings->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $findings->url($page) }}">{{ $page }}</a>
                            </li>
                        @endfor
                        @if ($findings->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $findings->nextPageUrl() }}">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>



    <style>
        /* Optimized and lightweight styles */
        .small-box {
            position: relative;
            display: block;
            background: #17a2b8;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .small-box:hover { opacity: 0.9; }
        .small-box .inner h3 { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
        .small-box .inner p { font-size: 16px; margin: 0; }
        .small-box .icon { position: absolute; top: 10px; right: 10px; font-size: 50px; opacity: 0.3; }

        /* Filter form optimization */
        .filter-form .form-select { border-radius: 6px; border: 1px solid #ddd; }
        .filter-form .form-select:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }

        #searchBtn {
            background: linear-gradient(45deg, #000, #0063c6);
            border: none;
            color: white;
            transition: opacity 0.3s ease;
        }
        #searchBtn:hover { opacity: 0.85; }

        /* Table optimization */
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .custom-table {
            margin-bottom: 0;
            table-layout: fixed;
            width: 100%;
        }

        .custom-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .custom-table td {
            padding: 8px 6px;
            vertical-align: top;
            border: 1px solid #dee2e6;
            word-wrap: break-word;
            max-width: 0;
        }

        .table-row-hover:hover { background-color: #f8f9fa !important; }
        .highlighted-row { background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important; border: 2px solid #1a202c !important; }

        /* Image optimization */
        .before-after-img {
            max-width: 120px;
            max-height: 100px;
            cursor: pointer;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .before-after-img:hover {
            border-color: #007bff;
            box-shadow: 0 2px 6px rgba(0,123,255,0.3);
        }

        /* Image container and overlay */
        .image-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .image-container:hover { transform: scale(1.03); }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }

        .image-container:hover .image-overlay { opacity: 1; }
        .image-overlay i { color: white; font-size: 20px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }

        /* Button and badge optimization */
        .btn-sm { padding: 4px 8px; font-size: 12px; border-radius: 4px; }
        .badge { font-size: 11px; padding: 4px 8px; }
        .text-muted { font-size: 11px; }

        /* Badge colors */
        .badge.bg-success { background-color: #28a745 !important; }
        .badge.bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .badge.bg-primary { background-color: #007bff !important; }

        /* Toast optimization */
        .toast-container { max-width: 500px; }
        .toast { width: 100%; }

        /* Responsive design */
        @media (max-width: 1200px) {
            .custom-table th, .custom-table td { padding: 6px 4px; font-size: 12px; }
            .before-after-img { max-width: 100px; max-height: 80px; }
        }

        @media (max-width: 768px) {
            .before-after-img { max-width: 80px; max-height: 60px; }
            .custom-table th, .custom-table td { padding: 4px 2px; font-size: 11px; }
            .btn-sm { padding: 2px 6px; font-size: 10px; }
            .badge { font-size: 10px; padding: 3px 6px; }
            .image-overlay i { font-size: 16px; }
        }

        @media (max-width: 576px) {
            .container { padding: 0 8px; }
            .table-responsive { font-size: 10px; }
            .before-after-img { max-width: 60px; max-height: 45px; }
            .image-overlay i { font-size: 14px; }
        }

        /* Loading optimization */
        .spinner-border-sm { width: 1rem; height: 1rem; }
    </style>

    <script>


        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize toasts
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000,
                    autohide: true
                });
                toast.show();
            });

            // Handle export buttons
            handleExport('exportPdfBtn', 'exportPdfText', 'exportPdfSpinner');
            handleExport('exportExcelBtn', 'exportExcelText', 'exportExcelSpinner');

            // Scroll to highlighted row if exists
            const highlightId = new URLSearchParams(window.location.search).get('highlight');
            if (highlightId) {
                const highlightedRow = document.getElementById('finding-' + highlightId);
                if (highlightedRow) {
                    setTimeout(function() {
                        highlightedRow.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    }, 500);

                    // Remove highlight after 4 seconds
                    setTimeout(function() {
                        highlightedRow.classList.remove('highlighted-row');
                        // Remove highlight parameter from URL
                        const url = new URL(window.location);
                        url.searchParams.delete('highlight');
                        window.history.replaceState({}, '', url);
                    }, 4000);
                }
            }
        });

        function handleExport(buttonId, textId, spinnerId) {
            const button = document.getElementById(buttonId);
            if (!button) return;

            button.addEventListener('click', function() {
                document.getElementById(textId)?.classList.add('d-none');
                document.getElementById(spinnerId)?.classList.remove('d-none');
                button.classList.add('disabled');

                setTimeout(function() {
                    document.getElementById(textId)?.classList.remove('d-none');
                    document.getElementById(spinnerId)?.classList.add('d-none');
                    button.classList.remove('disabled');
                }, 900);
            });
        }
</script>

@endsection
