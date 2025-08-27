@extends('layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-calendar"></i> Manajemen Tahun</h4>
            <a href="{{ route('admin.years.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Tahun Baru
            </a>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Tahun yang Tersedia</h5>
            </div>
            <div class="card-body">
                @if($years->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Jumlah Data</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($years as $index => $year)
                                    @php
                                        $dataCount = $year->data_count;
                                        $hasRealData = $dataCount > 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $year->year }}</strong>
                                            @if($year->year == now()->year)
                                                <span class="badge bg-success ms-2">Tahun Ini</span>
                                            @endif
                                        </td>
                                        <td>{{ $dataCount }} data</td>
                                        <td>
                                            @if($hasRealData)
                                                <span class="badge bg-success">{{ $dataCount }} Data</span>
                                            @else
                                                <span class="badge bg-secondary">Belum Ada Data</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('findings.index', ['year' => $year->year]) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                @if($year->year != now()->year)
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $year->year }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="deleteModal{{ $year->year }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus tahun <strong>{{ $year->year }}</strong>?</p>
                                                            <p class="text-danger">Semua data untuk tahun ini akan dihapus permanen!</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('admin.years.destroy', $year->year) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada tahun yang ditambahkan</h5>
                        <p class="text-muted">Klik tombol "Tambah Tahun Baru" untuk menambahkan tahun pertama.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 