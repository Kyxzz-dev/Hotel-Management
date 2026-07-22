@extends('layouts.admin')

@section('title', 'Akun Manajemen')

@section('content')
<div class="page-header">
    <div>
        <div class="page-kicker"><i class="fa fa-user-shield"></i> Role Manajemen</div>
        <h1 class="page-title">Akun Manajemen Hotel</h1>
        <p class="page-subtitle">Kelola akun internal hotel. Menu ini khusus HRD untuk membuat dan memperbarui akun HRD, Head Department, dan General Manager.</p>
    </div>
    <a href="{{ route('admin.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Tambah Akun</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="panel-card">
    <div class="panel-card-header">
        <h5 class="fw-bold mb-1">Tabel Akun Manajemen</h5>
        <div class="text-muted small">Total akun: {{ $admins->count() }}</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="adminTable" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tgl Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $index => $admin)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td><span class="role-badge role-badge-management"><i class="fa fa-user-shield"></i>{{ $admin->role_label }}</span></td>
                            <td>{{ $admin->tanggal_lahir ? \Carbon\Carbon::parse($admin->tanggal_lahir)->format('d M Y') : '-' }}</td>
                            <td>{{ $admin->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="text-center action-cell-sm">
                                <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
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
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .action-cell-sm {
        min-width: 140px;
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
    $('#adminTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', zeroRecords: 'Data tidak ditemukan', infoEmpty: 'Data kosong', paginate: { next: 'Berikutnya', previous: 'Sebelumnya' } }
    });
});
</script>
@endpush
