@extends('layouts.pegawai')

@section('title', 'Riwayat Cuti Saya')

@section('content')
@php
    $total = $cutis->count();
    $pending = $cutis->where('status', 'pending')->count();
    $disetujui = $cutis->where('status', 'disetujui')->count();
    $ditolak = $cutis->where('status', 'ditolak')->count();
@endphp

<div class="page-header">
    <div>
        <div class="page-kicker">
            <i class="fa fa-list-check"></i> Riwayat Cuti
        </div>
        <h1 class="page-title">Pengajuan Cuti Saya</h1>
        <p class="page-subtitle">
            Pantau semua pengajuan cuti beserta detail data formulir dan status persetujuannya.
        </p>
    </div>

    <a href="{{ route('pegawai.cuti.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-1"></i> Ajukan Cuti Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-triangle-exclamation me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $total }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Menunggu</div>
            <div class="stat-value text-warning">{{ $pending }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value text-success">{{ $disetujui }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value text-danger">{{ $ditolak }}</div>
        </div>
    </div>
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <h5 class="fw-bold mb-1">Tabel Riwayat Pengajuan</h5>
        <div class="text-muted small">
            Data berikut mengikuti isi formulir pengajuan cuti yang telah kamu kirim.
        </div>
    </div>

    <div class="card-body">
        @if($cutis->count())
            <div class="d-flex flex-wrap gap-2 mb-3" data-status-filters>
                <button type="button" class="btn btn-soft-primary btn-sm active" data-filter-status="">Semua</button>
                <button type="button" class="btn btn-outline-warning btn-sm" data-filter-status="pending">Menunggu</button>
                <button type="button" class="btn btn-outline-success btn-sm" data-filter-status="disetujui">Disetujui</button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-filter-status="ditolak">Ditolak</button>
            </div>
            <div class="table-responsive">
                <table id="cutiTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Cuti</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Jumlah Hari</th>
                            <th>Last Day of Work</th>
                            <th>First Day of Work</th>
                            <th>Person In Charge</th>
                            <th>Entitlement</th>
                            <th>Balance Before</th>
                            <th>Request</th>
                            <th>Balance After</th>
                            <th>Alasan</th>
                            <th>Remarks</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Diajukan</th>
                            <th>Disetujui Pada</th>
                            <th>Ditolak Pada</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cutis as $index => $cuti)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $cuti->jenis_cuti ?? '-' }}
                                </td>

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

                                <td>
                                    {{ $cuti->person_in_charge ?? '-' }}
                                </td>

                                <td>
                                    {{ $cuti->entitlement ?? 0 }}
                                </td>

                                <td>
                                    {{ $cuti->balance_before ?? 0 }}
                                </td>

                                <td>
                                    {{ $cuti->request_day ?? $cuti->jumlah_hari ?? 0 }}
                                </td>

                                <td>
                                    {{ $cuti->balance_after ?? 0 }}
                                </td>

                                <td class="reason-cell">
                                    {{ $cuti->alasan ?? '-' }}
                                </td>

                                <td class="remarks-cell">
                                    {{ $cuti->remarks ?? '-' }}
                                </td>

                                <td class="text-center">
                                    @if($cuti->attachment)
                                        <a href="{{ asset('storage/' . $cuti->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download Lampiran">
                                            <i class="fa fa-file me-1"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td data-search="{{ $cuti->status }}" data-order="{{ $cuti->status }}">
                                    @include('partials.status-badge', ['status' => $cuti->status])
                                </td>

                                <td>
                                    {{ $cuti->created_at ? \Carbon\Carbon::parse($cuti->created_at)->format('d M Y, H:i') : '-' }}
                                </td>

                                <td>
                                    {{ $cuti->approved_at ? \Carbon\Carbon::parse($cuti->approved_at)->format('d M Y, H:i') : '-' }}
                                </td>

                                <td>
                                    {{ $cuti->rejected_at ? \Carbon\Carbon::parse($cuti->rejected_at)->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <h5 class="fw-bold text-dark">Belum ada pengajuan</h5>
                <p>Mulai ajukan cuti pertama kamu melalui tombol di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .reason-cell {
        min-width: 260px;
    }

    .remarks-cell {
        min-width: 220px;
    }

    .attachment-cell {
        min-width: 100px;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        const table = $('#cutiTable').DataTable({
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

        document.querySelectorAll('[data-filter-status]').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('[data-filter-status]').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const value = button.dataset.filterStatus;
                table.column(15).search(value ? '^' + value + '$' : '', true, false).draw();
            });
        });
    });
</script>
@endpush