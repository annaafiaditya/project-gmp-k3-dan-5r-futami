@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-bell"></i> Notifikasi</h4>
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">Tandai semua dibaca</button>
        </form>
    </div>
    <div class="list-group">
        @forelse ($notifications as $n)
            <div class="list-group-item d-flex justify-content-between align-items-start {{ $n->is_read ? '' : 'list-group-item-warning' }}">
                <div class="ms-2 me-auto">
                    <a href="{{ route('notifications.markRead', $n->id) }}" class="fw-bold text-decoration-none d-block">{{ $n->title }}</a>
                    <small class="text-muted d-block">{{ $n->message }}</small>
                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($n->is_read)
                        <form method="POST" action="{{ route('notifications.markUnread', $n->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">Tandai belum dibaca</button>
                        </form>
                    @else
                        <a class="btn btn-sm btn-success" href="{{ route('notifications.markRead', $n->id) }}">Tandai dibaca</a>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $n->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-muted">Tidak ada notifikasi.</div>
        @endforelse
    </div>
</div>
@endsection


