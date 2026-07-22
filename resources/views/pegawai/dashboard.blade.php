@extends('layouts.pegawai')

@section('title', 'Dashboard Staff')

@section('content')
@php
    $user = Auth::user();
    $batas = $batasCutiTahunan ?? 12;
    $hak = $hakCutiTerkumpul ?? 0;
    $sisa = $sisaSlotCuti ?? 0;
    $terpakai = $hariAktifTahunIni ?? max(0, $hak - $sisa);
    $progress = $batas > 0 ? min(100, ($hak / $batas) * 100) : 0;
@endphp

<div class="hero-card mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="hero-eyebrow"><i class="fa fa-hotel me-2"></i>Staff Hotel Portal</div>
            <h1 class="hero-title mt-2 mb-2">Halo, {{ $user->name }}</h1>
            <p class="hero-subtitle mb-0">
                Ajukan cuti tanpa ribet, pantau status persetujuan, dan pastikan data departemen serta jabatan selalu terbaru.
            </p>
            <div class="hero-meta">
                <span class="hero-pill"><i class="fa fa-user"></i>{{ $user->position_label }}</span>
                <span class="hero-pill"><i class="fa fa-building"></i>{{ $user->department ?: 'Departemen belum diisi' }}</span>
                <span class="hero-pill"><i class="fa fa-briefcase"></i>Masuk: {{ isset($tanggalMasuk) ? $tanggalMasuk->format('d M Y') : '-' }}</span>
                <span class="hero-pill"><i class="fa fa-calendar-check"></i>{{ $sisa }} hari tersisa</span>
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="balance-ring" style="--progress: {{ $progress }}%;">
                <div class="balance-ring-inner">
                    <div>
                        <div class="fs-3">{{ $sisa }}</div>
                        <small class="text-muted">Sisa Cuti</small>
                    </div>
                </div>
            </div>
            <a href="{{ route('pegawai.cuti.create') }}" class="btn btn-gold mt-3">
                <i class="fa fa-plus me-1"></i> Ajukan Cuti
            </a>
        </div>
    </div>
</div>


@if(isset($nextSlotDate) && $nextSlotDate)
<div class="alert alert-info border-0 shadow-sm mb-4">
    <i class="fa fa-circle-info me-1"></i>
    Saldo cuti bertambah 1 hari setiap genap 1 bulan kerja. Slot berikutnya akan tersedia pada
    <strong>{{ $nextSlotDate->format('d M Y') }}</strong> jika belum mencapai batas {{ $batas }} hari.
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Saldo Cuti</div><div class="stat-value">{{ $sisa }}</div><div class="stat-caption">hak terkumpul {{ $hak }} / {{ $batas }}</div></div>
                <div class="stat-icon"><i class="fa fa-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Menunggu</div><div class="stat-value text-warning">{{ $cutiPending ?? 0 }}</div><div class="stat-caption">Sedang diproses</div></div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f7d27b)"><i class="fa fa-clock"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Disetujui</div><div class="stat-value text-success">{{ $cutiDisetujui ?? 0 }}</div><div class="stat-caption">Pengajuan diterima</div></div>
                <div class="stat-icon"><i class="fa fa-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-label">Ditolak</div><div class="stat-value text-danger">{{ $cutiDitolak ?? 0 }}</div><div class="stat-caption">Tidak mengurangi slot</div></div>
                <div class="stat-icon"><i class="fa fa-xmark"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <a href="{{ route('pegawai.cuti.create') }}" class="action-card d-block">
            <div class="action-icon mb-3"><i class="fa fa-calendar-plus"></i></div>
            <h5 class="fw-bold">Ajukan Cuti</h5>
            <p>Form cuti dengan hitung otomatis jumlah hari dan sisa slot tahunan.</p>
            <span class="btn btn-soft-primary btn-sm">Buat Pengajuan <i class="fa fa-arrow-right ms-1"></i></span>
        </a>
    </div>
    <div class="col-lg-4">
        <a href="{{ route('pegawai.cuti.index') }}" class="action-card d-block">
            <div class="action-icon mb-3"><i class="fa fa-list-check"></i></div>
            <h5 class="fw-bold">Riwayat Cuti</h5>
            <p>Lihat status pending, disetujui, atau ditolak secara cepat.</p>
            <span class="btn btn-soft-primary btn-sm">Lihat Riwayat <i class="fa fa-arrow-right ms-1"></i></span>
        </a>
    </div>
    <div class="col-lg-4">
        <a href="{{ route('pegawai.profile') }}" class="action-card d-block">
            <div class="action-icon mb-3" style="background:linear-gradient(135deg,#c99a2e,#f7d27b)"><i class="fa fa-id-card"></i></div>
            <h5 class="fw-bold">Profil Hotel</h5>
            <p>Lihat departemen dan jabatan agar laporan cuti tetap akurat.</p>
            <span class="btn btn-soft-primary btn-sm">Edit Profil <i class="fa fa-arrow-right ms-1"></i></span>
        </a>
    </div>
</div>

<div class="panel-card mt-4">
    <div class="panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">Riwayat Terbaru</h5>
            <div class="text-muted small">Pengajuan cuti terakhir dari akun kamu.</div>
        </div>
        <a href="{{ route('pegawai.cuti.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body">
        @if(($cutiTerbaru ?? collect())->count())
            <div class="timeline">
                @foreach($cutiTerbaru as $cuti)
                    <div class="timeline-item">
                        <div class="timeline-dot"><i class="fa fa-calendar-day"></i></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                <div>
                                    <div class="fw-bold">{{ $cuti->jenis_cuti ?? 'Cuti' }}</div>
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($cuti->alasan, 80) }}</div>
                                </div>
                                @include('partials.status-badge', ['status' => $cuti->status])
                            </div>
                            <div class="small text-muted mt-2">
                                {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="fa fa-inbox"></i><div>Kamu belum pernah mengajukan cuti.</div></div>
        @endif
    </div>
</div>
@endsection
