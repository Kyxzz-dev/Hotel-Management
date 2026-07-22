@extends('layouts.admin')

@section('title', 'Data Cuti Staff')

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
            <i class="fa fa-calendar-days"></i> Validasi Cuti
        </div>
        <h1 class="page-title">Daftar Pengajuan Cuti</h1>
        <p class="page-subtitle">
            Kelola pengajuan cuti seluruh staff. Data yang tampil mengikuti formulir pengajuan cuti.
        </p>
    </div>

    <a href="{{ route('admin.cuti.laporan') }}" class="btn btn-outline-primary">
        <i class="fa fa-chart-column me-1"></i> Laporan
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
        <h5 class="fw-bold mb-1">Tabel Pengajuan</h5>
        <div class="text-muted small">
            Pengajuan dengan status pending dapat langsung disetujui atau ditolak dari kolom aksi.
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
                            <th>Staff</th>
                            <th>Position</th>
                            <th>Department</th>
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
                            <th>Status</th>
                            <th>Approved At</th>
                            <th>Rejected At</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cutis as $index => $cuti)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td class="fw-bold">
                                    {{ $cuti->pegawai->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $cuti->position ?: ($cuti->pegawai->position_label ?? '-') }}
                                </td>

                                <td>
                                    {{ $cuti->department ?: ($cuti->pegawai->department ?? '-') }}
                                </td>

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

                                <td class="balance-after-cell">
                                    {{ $cuti->balance_after ?? 0 }}
                                </td>

                                <td class="reason-cell">
                                    {{ $cuti->alasan ?? '-' }}
                                </td>

                                <td class="remarks-cell">
                                    {{ $cuti->remarks ?? '-' }}
                                </td>

                                <td class="status-cell" data-search="{{ $cuti->status }}" data-order="{{ $cuti->status }}">
                                    @include('partials.status-badge', ['status' => $cuti->status])
                                </td>

                                <td class="approved-at-cell">
                                    {{ $cuti->approved_at ? \Carbon\Carbon::parse($cuti->approved_at)->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="rejected-at-cell">
                                    {{ $cuti->rejected_at ? \Carbon\Carbon::parse($cuti->rejected_at)->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="text-center action-cell">
                                    @if($cuti->status === 'pending')
                                        <form action="{{ route('admin.cuti.approve', $cuti->id) }}" method="POST" class="d-inline approval-form" data-confirm="Setujui pengajuan cuti ini?">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    >
                                                <i class="fa fa-check me-1"></i> Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.cuti.reject', $cuti->id) }}" method="POST" class="d-inline approval-form" data-confirm="Tolak pengajuan cuti ini?">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm mt-1 mt-xl-0"
                                                    >
                                                <i class="fa fa-xmark me-1"></i> Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">
                                            Sudah diproses
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <div>Belum ada pengajuan cuti.</div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .reason-cell {
        min-width: 240px;
    }

    .remarks-cell {
        min-width: 200px;
    }

    .action-cell {
        min-width: 180px;
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
                table.column(17).search(value ? '^' + value + '$' : '', true, false).draw(false);
            });
        });

        function statusBadge(status) {
            const label = status === 'disetujui' ? 'Disetujui' : (status === 'ditolak' ? 'Ditolak' : 'Menunggu');
            const icon = status === 'disetujui' ? 'fa-check' : (status === 'ditolak' ? 'fa-xmark' : 'fa-clock');
            return `<span class="badge-status badge-${status}"><i class="fa ${icon}"></i>${label}</span>`;
        }

        function showApprovalToast(message, success = true) {
            const wrapper = document.createElement('div');
            wrapper.className = `approval-toast ${success ? 'approval-toast-success' : 'approval-toast-danger'}`;
            wrapper.innerHTML = `<i class="fa ${success ? 'fa-circle-check' : 'fa-triangle-exclamation'}"></i><span>${message}</span>`;
            document.body.appendChild(wrapper);
            setTimeout(() => wrapper.classList.add('show'), 20);
            setTimeout(() => {
                wrapper.classList.remove('show');
                setTimeout(() => wrapper.remove(), 260);
            }, 3200);
        }

        $(document).on('submit', '.approval-form', function (event) {
            event.preventDefault();

            const form = this;
            const confirmText = form.dataset.confirm || 'Proses pengajuan ini?';
            if (!confirm(confirmText)) return;

            const $form = $(form);
            const $rowNode = $form.closest('tr');
            const row = table.row($rowNode);
            const $buttons = $form.closest('.action-cell').find('button');
            $buttons.prop('disabled', true).addClass('disabled');

            $.ajax({
                url: form.action,
                method: 'POST',
                data: $form.serialize(),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (!response.success || !response.cuti) {
                        showApprovalToast(response.message || 'Status gagal diperbarui.', false);
                        $buttons.prop('disabled', false).removeClass('disabled');
                        return;
                    }

                    const cuti = response.cuti;
                    const node = row.node();
                    const $node = $(node);

                    $node.find('.status-cell')
                        .attr('data-search', cuti.status)
                        .attr('data-order', cuti.status)
                        .html(statusBadge(cuti.status));
                    $node.find('.approved-at-cell').text(cuti.approved_at || '-');
                    $node.find('.rejected-at-cell').text(cuti.rejected_at || '-');
                    $node.find('.balance-after-cell').text(cuti.balance_after ?? 0);
                    $node.find('.action-cell').html('<span class="text-muted small">Sudah diproses</span>');

                    row.invalidate('dom').draw(false);
                    showApprovalToast(response.message || 'Status pengajuan berhasil diperbarui.', true);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses pengajuan.';
                    showApprovalToast(message, false);
                    $buttons.prop('disabled', false).removeClass('disabled');
                }
            });
        });
    });
</script>
@endpush