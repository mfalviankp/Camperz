@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="page-header">
    <div class="container"><h1>🔔 Notifikasi</h1></div>
</div>
<div class="container section" style="padding-top:1.5rem;max-width:700px">
    @if($notifikasis->count() > 0)
    <div style="display:flex;flex-direction:column;gap:.75rem">
        @foreach($notifikasis as $n)
        <div class="card" style="{{ !$n->dibaca ? 'border-left:4px solid var(--hijau)' : '' }}">
            <div class="card-body" style="padding:1rem 1.5rem">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem">
                    <div>
                        <div class="fw-bold" style="{{ !$n->dibaca ? 'color:var(--hijau)' : '' }}">{{ $n->judul }}</div>
                        <div style="font-size:.9rem;color:var(--teks-abu);margin-top:4px">{{ $n->pesan }}</div>
                    </div>
                    <div style="font-size:.78rem;color:var(--teks-abu);white-space:nowrap">
                        {{ $n->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="icon">🔔</div>
        <h3>Tidak ada notifikasi</h3>
        <p class="text-abu">Notifikasi akan muncul di sini.</p>
    </div>
    @endif
</div>
@endsection
