@extends('layouts.admin')

@section('title', 'Laporan Cuti Staff')

@section('content')
@php
    $batas = $batasCutiTahunan ?? 12;

    $totalDisetujuiSemua = $pegawais->sum(function ($pegawai) {
        return $pegawai->cutis->where('status', 'disetujui')->sum(function ($cuti) {
            return $cuti->request_day
                ?? $cuti->jumlah_hari
                ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
        });
    });

    $totalPendingSemua = $pegawais->sum(function ($pegawai) {
        return $pegawai->cutis->where('status', 'pending')->sum(function ($cuti) {
            return $cuti->request_day
                ?? $cuti->jumlah_hari
                ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
        });
    });

    $totalDitolakSemua = $pegawais->sum(function ($pegawai) {
        return $pegawai->cutis->where('status', 'ditolak')->sum(function ($cuti) {
            return $cuti->request_day
                ?? $cuti->jumlah_hari
                ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
        });
    });
@endphp

<div class="page-header">
    <div>
        <div class="page-kicker">
            <i class="fa fa-chart-column"></i> Monitoring
        </div>
        <h1 class="page-title">Laporan Cuti Staff</h1>
        <p class="page-subtitle">
            Laporan cuti tahun {{ now()->year }} berdasarkan data pengajuan cuti staff.
            Pengajuan ditolak tidak mengurangi sisa slot cuti.
        </p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.cuti.laporan.export', 'csv') }}" class="btn btn-success">
            <i class="fa fa-file-csv me-1"></i> Export CSV
        </a>

        <a href="{{ route('admin.cuti.laporan.export', 'excel') }}" class="btn btn-primary">
            <i class="fa fa-file-excel me-1"></i> Export Excel
        </a>

        @if(in_array(Auth::user()->role, ['head_department', 'gm']))
            <a href="{{ route('admin.cuti.index') }}" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left me-1"></i> Pengajuan Cuti
            </a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Jumlah Staff</div>
            <div class="stat-value">{{ $pegawais->count() }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Hari Disetujui</div>
            <div class="stat-value text-success">{{ $totalDisetujuiSemua }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Hari Menunggu</div>
            <div class="stat-value text-warning">{{ $totalPendingSemua }}</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Hari Ditolak</div>
            <div class="stat-value text-danger">{{ $totalDitolakSemua }}</div>
        </div>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm">
    <i class="fa fa-circle-info me-1"></i>
    Perhitungan sisa slot:
    <strong>{{ $batas }} - hari disetujui - hari menunggu</strong>.
    Pengajuan dengan status <strong>ditolak</strong> hanya menjadi riwayat dan tidak mengurangi sisa slot cuti.
</div>

<div class="panel-card mb-4">
    <div class="panel-card-header">
        <h5 class="fw-bold mb-1">Ringkasan Slot Cuti Staff</h5>
        <div class="text-muted small">
            Ringkasan jumlah cuti aktif, cuti ditolak, dan sisa slot per staff.
        </div>
    </div>

    <div class="card-body">
        @if($pegawais->count())
            <div class="table-responsive">
                <table id="ringkasanCutiTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>Nama Staff</th>
                            <th>Email</th>
                            <th>Posisi</th>
                            <th>Departemen</th>
                            <th>Disetujui</th>
                            <th>Menunggu</th>
                            <th>Ditolak</th>
                            <th>Sisa Slot</th>
                            <th>Progress</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($pegawais as $pegawai)
                            @php
                                $hariDisetujui = $pegawai->cutis->where('status', 'disetujui')->sum(function ($cuti) {
                                    return $cuti->request_day
                                        ?? $cuti->jumlah_hari
                                        ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
                                });

                                $hariPending = $pegawai->cutis->where('status', 'pending')->sum(function ($cuti) {
                                    return $cuti->request_day
                                        ?? $cuti->jumlah_hari
                                        ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
                                });

                                $hariDitolak = $pegawai->cutis->where('status', 'ditolak')->sum(function ($cuti) {
                                    return $cuti->request_day
                                        ?? $cuti->jumlah_hari
                                        ?? (\Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1);
                                });

                                $slotTerpakai = $hariDisetujui + $hariPending;
                                $sisaSlot = max(0, $batas - $slotTerpakai);
                                $persen = $batas > 0 ? min(100, ($slotTerpakai / $batas) * 100) : 0;
                            @endphp

                            <tr>
                                <td class="fw-bold">{{ $pegawai->name }}</td>
                                <td>{{ $pegawai->email }}</td>
                                <td>{{ $pegawai->position_label }}</td>
                                <td>{{ $pegawai->department ?: '-' }}</td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $hariDisetujui }} hari
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $hariPending }} hari
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        {{ $hariDitolak }} hari
                                    </span>
                                </td>

                                <td class="fw-bold">
                                    {{ $sisaSlot }} hari
                                </td>

                                <td class="progress-cell">
                                    <div class="progress progress-custom">
                                        <div class="progress-bar cuti-progress-bar" data-progress="{{ $persen }}"></div>
                                    </div>

                                    <div class="text-muted small mt-1">
                                        {{ $slotTerpakai }} / {{ $batas }} hari aktif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <div>Belum ada data staff ditemukan.</div>
            </div>
        @endif
    </div>
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <h5 class="fw-bold mb-1">Detail Pengajuan Cuti</h5>
        <div class="text-muted small">
            Detail ini mengikuti data formulir pengajuan cuti manual.
        </div>
    </div>

    <div class="card-body">
        @php
            $adaDataCuti = $pegawais->sum(function ($pegawai) {
                return $pegawai->cutis->count();
            });
        @endphp

        @if($adaDataCuti > 0)
            <div class="table-responsive">
                <table id="detailCutiTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Staff</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jumlah Hari</th>
                            <th>Last Day of Work</th>
                            <th>First Day of Work</th>
                            <th>Entitlement</th>
                            <th>Balance Before</th>
                            <th>Request</th>
                            <th>Balance After</th>
                            <th>Reason</th>
                            <th>Person In Charge</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Approved At</th>
                            <th>Rejected At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $no = 1; @endphp

                        @foreach($pegawais as $pegawai)
                            @foreach($pegawai->cutis as $cuti)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="fw-bold">{{ $pegawai->name }}</td>
                                    <td>{{ $pegawai->email }}</td>
                                    <td>{{ $cuti->position ?: ($pegawai->position_label ?? '-') }}</td>
                                    <td>{{ $cuti->department ?: ($pegawai->department ?? '-') }}</td>
                                    <td>{{ $cuti->jenis_cuti ?? '-' }}</td>

                                    <td>
                                        {{ $cuti->tanggal_mulai ? \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->tanggal_selesai ? \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->jumlah_hari ?? $cuti->request_day ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->last_day_of_work ? \Carbon\Carbon::parse($cuti->last_day_of_work)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->first_day_of_work ? \Carbon\Carbon::parse($cuti->first_day_of_work)->format('d M Y') : '-' }}
                                    </td>

                                    <td>{{ $cuti->entitlement ?? 0 }}</td>
                                    <td>{{ $cuti->balance_before ?? 0 }}</td>
                                    <td>{{ $cuti->request_day ?? $cuti->jumlah_hari ?? 0 }}</td>
                                    <td>{{ $cuti->balance_after ?? 0 }}</td>

                                    <td class="reason-cell">
                                        {{ $cuti->alasan ?? '-' }}
                                    </td>

                                    <td>{{ $cuti->person_in_charge ?? '-' }}</td>

                                    <td class="remarks-cell">
                                        {{ $cuti->remarks ?? '-' }}
                                    </td>

                                    <td>
                                        @include('partials.status-badge', ['status' => $cuti->status])
                                    </td>

                                    <td>
                                        {{ $cuti->created_at ? \Carbon\Carbon::parse($cuti->created_at)->format('d M Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->approved_at ? \Carbon\Carbon::parse($cuti->approved_at)->format('d M Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        {{ $cuti->rejected_at ? \Carbon\Carbon::parse($cuti->rejected_at)->format('d M Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <div>Belum ada data pengajuan cuti.</div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .progress-cell {
        min-width: 220px;
    }

    .progress-custom {
        height: 10px;
        border-radius: 999px;
    }

    .cuti-progress-bar {
        width: 0%;
    }

    .reason-cell {
        min-width: 240px;
    }

    .remarks-cell {
        min-width: 200px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        $('#ringkasanCutiTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                zeroRecords: 'Data tidak ditemukan',
                infoEmpty: 'Data kosong',
                paginate: {
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $('#detailCutiTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                zeroRecords: 'Data tidak ditemukan',
                infoEmpty: 'Data kosong',
                paginate: {
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        document.querySelectorAll('.cuti-progress-bar').forEach(function (bar) {
            const progress = bar.dataset.progress || 0;
            bar.style.width = progress + '%';
        });
    });
</script>
@endpush