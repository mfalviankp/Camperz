@extends('layouts.admin')
@section('title', 'Scan Return')
@section('page-title', 'Scan QR Return')

@section('content')
<div style="max-width:600px">
    <div class="card">
        <div class="card-header">📥 Masukkan Kode QR Return</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.scan.return.proses') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kode QR Return</label>
                    <input type="text" name="qr_code" class="form-control"
                           placeholder="Contoh: RET-12-ABCD"
                           autofocus required
                           style="font-family:monospace;font-size:1.1rem;letter-spacing:2px">
                    <div style="font-size:.82rem;color:var(--teks-abu);margin-top:4px">
                        Kode return diberikan ke user saat pengambilan
                    </div>
                </div>
                <button type="submit" class="btn btn-oranye btn-lg">📥 Proses Pengembalian</button>
            </form>
        </div>
    </div>
</div>
@endsection
