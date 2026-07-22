@extends('layouts.admin')

@section('title', 'Detail Staff')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-7">
        <div class="panel-card">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Detail Staff</h3>
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-muted small">Nama</div><div class="fw-bold">{{ $pegawai->name }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Email</div><div class="fw-bold">{{ $pegawai->email }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Role / Posisi</div><div class="fw-bold">{{ $pegawai->position_label }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Tanggal Masuk Kerja</div><div class="fw-bold">{{ $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('d M Y') : ($pegawai->created_at ? \Carbon\Carbon::parse($pegawai->created_at)->format('d M Y') : '-') }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Departemen</div><div class="fw-bold">{{ $pegawai->department ?: '-' }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Jabatan</div><div class="fw-bold">{{ $pegawai->jabatan ?: '-' }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Tanggal Lahir</div><div class="fw-bold">{{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y') : '-' }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Jenis Kelamin</div><div class="fw-bold">{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : ($pegawai->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</div></div>
                </div>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary mt-4"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
