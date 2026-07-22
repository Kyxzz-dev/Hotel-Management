@extends('layouts.admin')

@section('title', 'Dashboard HRD')

@section('content')
@php
    $approvalRate = ($totalCuti ?? 0) > 0 ? round((($cutiDisetujui ?? 0) / ($totalCuti ?? 1)) * 100) : 0;
    $user = Auth::user();
@endphp

<div class="hrd-welcome-card mb-4">
    <div class="hrd-welcome-pattern"></div>
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="hero-eyebrow"><i class="fa fa-user-tie me-2"></i>HRD Control Center</div>
            <h1 class="hero-title mt-2 mb-2">Selamat datang, {{ $user->name }}</h1>
            <p class="hero-subtitle mb-0">
                Pantau data staff, departemen, dan ringkasan cuti hotel dari satu dashboard profesional. HRD berfokus pada administrasi staff, master departemen, serta laporan operasional cuti.
            </p>
            <div class="hero-meta">
                <span class="hero-pill"><i class="fa fa-users"></i>{{ $totalPegawai ?? 0 }} staff aktif</span>
                <span class="hero-pill"><i class="fa fa-building"></i>{{ $departemenAktif ?? 0 }} departemen</span>
                <span class="hero-pill"><i class="fa fa-calendar"></i>{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('admin.pegawai.create') }}" class="btn btn-gold btn-lg mb-2">
                <i class="fa fa-user-plus me-1"></i> Tambah Staff
            </a>
            <div class="small text-white-50">Kelola akun staff hotel dari menu HRD</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-premium">
            <div class="stat-icon mb-3"><i class="fa fa-users"></i></div>
            <div class="stat-label">Total Staff</div>
            <div class="stat-value">{{ $totalPegawai ?? 0 }}</div>
            <div class="stat-caption">Akun staff aktif</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-premium">
            <div class="stat-icon mb-3"><i class="fa fa-user-plus"></i></div>
            <div class="stat-label">Staff Baru Bulan Ini</div>
            <div class="stat-value text-success">{{ $staffBaruBulanIni ?? 0 }}</div>
            <div class="stat-caption">Akun dibuat bulan berjalan</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-premium">
            <div class="stat-icon mb-3"><i class="fa fa-calendar-days"></i></div>
            <div class="stat-label">Total Pengajuan</div>
            <div class="stat-value">{{ $totalCuti ?? 0 }}</div>
            <div class="stat-caption">Untuk laporan HRD</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-premium">
            <div class="stat-icon mb-3"><i class="fa fa-chart-line"></i></div>
            <div class="stat-label">Approval Rate</div>
            <div class="stat-value text-primary">{{ $approvalRate }}%</div>
            <div class="stat-caption">Berdasarkan cuti disetujui</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="panel-card h-100">
            <div class="panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Distribusi Staff per Departemen</h5>
                    <div class="text-muted small">Ringkasan jumlah staff berdasarkan departemen hotel.</div>
                </div>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-sm btn-outline-primary">Kelola Staff</a>
            </div>
            <div class="card-body">
                @forelse($staffPerDepartemen as $row)
                    @php($percent = ($totalPegawai ?? 0) > 0 ? min(100, round(($row->total / $totalPegawai) * 100)) : 0)
                    <div class="department-progress-item">
                        <div class="d-flex justify-content-between small fw-bold mb-2">
                            <span><i class="fa fa-building-user me-1 text-maroon"></i>{{ $row->department ?: 'Belum diisi' }}</span>
                            <span>{{ $row->total }} staff</span>
                        </div>
                        <div class="progress soft-progress">
                            <div class="progress-bar hrd-progress-bar" data-progress="{{ $percent }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state"><i class="fa fa-users-slash"></i><div>Belum ada data staff.</div></div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="row g-4">
            <div class="col-md-6 col-xl-12">
                <a href="{{ route('admin.master-data.index') }}" class="action-card action-card-premium d-block">
                    <div class="action-icon mb-3"><i class="fa fa-layer-group"></i></div>
                    <h5 class="fw-bold">Master Departemen</h5>
                    <p>Tambahkan dan rapikan daftar departemen hotel untuk kebutuhan data staff dan laporan cuti.</p>
                    <span class="btn btn-soft-primary btn-sm">Buka Master Data <i class="fa fa-arrow-right ms-1"></i></span>
                </a>
            </div>
            <div class="col-md-6 col-xl-12">
                <a href="{{ route('admin.cuti.laporan') }}" class="action-card action-card-premium d-block">
                    <div class="action-icon mb-3 action-icon-gold"><i class="fa fa-file-export"></i></div>
                    <h5 class="fw-bold">Laporan Cuti</h5>
                    <p>Lihat ringkasan cuti staff, alasan pengajuan, departemen, dan export CSV/Excel.</p>
                    <span class="btn btn-soft-primary btn-sm">Buka Laporan <i class="fa fa-arrow-right ms-1"></i></span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.hrd-progress-bar').forEach(function (bar) {
        const progress = bar.dataset.progress || 0;
        bar.style.width = progress + '%';
    });
</script>
@endpush
