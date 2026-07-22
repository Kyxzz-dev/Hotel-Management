@extends('layouts.admin')

@section('title', 'Kelola Staff')

@section('content')
<div class="page-header">
    <div>
        <div class="page-kicker"><i class="fa fa-users"></i> Data Staff</div>
        <h1 class="page-title">Daftar Staff</h1>
        <p class="page-subtitle">Kelola akun staff yang dapat melakukan pengajuan cuti.</p>
    </div>
    <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Tambah Staff</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="panel-card">
    <div class="panel-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">Tabel Staff</h5>
            <div class="text-muted small">Total staff: {{ $pegawais->count() }}</div>
        </div>
    </div>
    <div class="card-body">
        @if($pegawais->count())
            <div class="table-responsive">
                <table id="staffTable" class="table table-hover align-middle w-100">
                    <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Tgl Masuk</th><th>Role / Posisi</th><th>Departemen</th><th>Jabatan</th><th>Tgl Lahir</th><th>Jenis Kelamin</th><th class="text-center">Aksi</th></tr></thead>
                    <tbody>
                    @foreach($pegawais as $index => $pegawai)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $pegawai->name }}</td>
                            <td>{{ $pegawai->email }}</td>
                            <td>{{ $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('d M Y') : ($pegawai->created_at ? \Carbon\Carbon::parse($pegawai->created_at)->format('d M Y') : '-') }}</td>
                            <td>{{ $pegawai->position_label }}</td>
                            <td>{{ $pegawai->department ?: '-' }}</td>
                            <td>{{ $pegawai->jabatan ?: '-' }}</td>
                            <td>{{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y') : '-' }}</td>
                            <td>{{ $pegawai->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="text-center action-cell">
                                <a href="{{ route('admin.pegawai.show', $pegawai->id) }}" class="btn btn-info btn-sm" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><i class="fa fa-user-slash"></i><div>Belum ada data staff.</div></div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .action-cell {
        min-width: 150px;
        white-space: nowrap;
    }
</style>
@endpush
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){
    $('#staffTable').DataTable({
        responsive: false,
        scrollX: true,
        pageLength: 10,
        language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', zeroRecords: 'Data tidak ditemukan', infoEmpty: 'Data kosong', paginate: { next: 'Berikutnya', previous: 'Sebelumnya' } }
    });
});
</script>
@endpush
