@extends('layouts.admin')

@section('title', 'Dashboard Hotel')

@section('content')
@php
    $user = Auth::user();
    $pendingCount = $cutiPending ?? 0;
    $approvalRate = ($totalCuti ?? 0) > 0 ? round((($cutiDisetujui ?? 0) / ($totalCuti ?? 1)) * 100) : 0;
@endphp

<div class="hero-card mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="hero-eyebrow"><i class="fa fa-hotel me-2"></i>Hotel Leave Management</div>
            <h1 class="hero-title mt-2 mb-2">Selamat datang, {{ $user->name }}</h1>
            <p class="hero-subtitle mb-0">
                Pantau pengajuan cuti staff hotel lintas departemen, proses approval lebih cepat, dan pastikan operasional tetap stabil.
            </p>
            <div class="hero-meta">
                <span class="hero-pill"><i class="fa fa-user-tie"></i>{{ $user->position_label }}</span>
                <span class="hero-pill"><i class="fa fa-building"></i>{{ $user->department ?: 'Departemen belum diisi' }}</span>
                <span class="hero-pill"><i class="fa fa-calendar"></i>{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('admin.cuti.index') }}" class="btn btn-gold btn-lg mb-2">
                <i class="fa fa-list-check me-1"></i> Proses Approval
            </a>
            <div class="small text-white-50">{{ $pendingCount }} pengajuan masih menunggu keputusan</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Staff</div>
                    <div class="stat-value">{{ $totalPegawai ?? 0 }}</div>
                    <div class="stat-caption">Aktif sebagai pengaju cuti</div>
                </div>
                <div class="stat-icon"><i class="fa fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Pengajuan</div>
                    <div class="stat-value">{{ $totalCuti ?? 0 }}</div>
                    <div class="stat-caption">Seluruh riwayat cuti</div>
                </div>
                <div class="stat-icon" ><i class="fa fa-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Menunggu</div>
                    <div class="stat-value text-warning">{{ $cutiPending ?? 0 }}</div>
                    <div class="stat-caption">Perlu approval</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f7d27b)"><i class="fa fa-clock"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Approval Rate</div>
                    <div class="stat-value text-success">{{ $approvalRate }}%</div>
                    <div class="stat-caption">Dari total pengajuan</div>
                </div>
                <div class="stat-icon" ><i class="fa fa-chart-line"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel-card h-100">
            <div class="panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Pengajuan Terbaru</h5>
                    <div class="text-muted small">Data terbaru yang masuk ke sistem approval hotel.</div>
                </div>
                <a href="{{ route('admin.cuti.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
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
                                            <div class="fw-bold">{{ $cuti->pegawai->name ?? '-' }}</div>
                                            <div class="text-muted small">
                                                {{ $cuti->jenis_cuti ?? 'Cuti' }} · {{ $cuti->department ?: ($cuti->pegawai->department ?? '-') }}
                                            </div>
                                        </div>
                                        @include('partials.status-badge', ['status' => $cuti->status])
                                    </div>
                                    <div class="small text-muted mt-2">
                                        {{ $cuti->tanggal_mulai ? \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') : '-' }}
                                        sampai
                                        {{ $cuti->tanggal_selesai ? \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state"><i class="fa fa-inbox"></i><div>Belum ada pengajuan cuti.</div></div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="row g-4">
            <div class="col-md-6 col-xl-12">
                <a href="{{ route('admin.cuti.index') }}" class="action-card d-block">
                    <div class="action-icon mb-3"><i class="fa fa-list-check"></i></div>
                    <h5 class="fw-bold">Approval Cuti</h5>
                    <p>Proses pengajuan cuti staff dengan aksi cepat tanpa keluar dari halaman approval.</p>
                    <span class="btn btn-soft-primary btn-sm">Buka Approval <i class="fa fa-arrow-right ms-1"></i></span>
                </a>
            </div>
            <div class="col-md-6 col-xl-12">
                <a href="{{ route('admin.cuti.laporan') }}" class="action-card d-block">
                    <div class="action-icon mb-3 action-icon-gold"><i class="fa fa-file-export"></i></div>
                    <h5 class="fw-bold">Laporan Cuti</h5>
                    <p>Monitoring sisa slot, alasan cuti, dan export CSV/Excel.</p>
                    <span class="btn btn-soft-primary btn-sm">Buka Laporan <i class="fa fa-arrow-right ms-1"></i></span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
