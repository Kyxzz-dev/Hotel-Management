@extends('layouts.admin')

@section('title', 'Master Data HRD')

@section('content')
<div class="page-header">
    <div>
        <div class="page-kicker"><i class="fa fa-layer-group"></i> HRD Master Data</div>
        <h1 class="page-title">Departemen Hotel</h1>
        <p class="page-subtitle">Kelola pilihan departemen yang digunakan saat membuat atau mengedit akun staff.</p>
    </div>
    <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary"><i class="fa fa-user-plus me-1"></i>Tambah Staff</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">Data belum valid:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-12">
        <div class="panel-card h-100 hotel-form-card">
            <div class="panel-card-header">
                <h5 class="fw-bold mb-1">Departemen Hotel</h5>
                <div class="text-muted small">Tambah, ubah, aktif/nonaktifkan, atau hapus departemen sesuai struktur hotel.</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.master-data.departments.store') }}" method="POST" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Banquet" required>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-plus me-1"></i>Tambah</button>
                </form>

                <div class="alert alert-info py-2 small mb-3">
                    <i class="fa fa-circle-info me-1"></i>
                    Data posisi sistem tidak lagi dikelola di halaman ini. Role/posisi tetap digunakan untuk akses, sedangkan departemen dan jabatan digunakan untuk data staff.
                </div>

                <div class="vstack gap-2">
                    @forelse($departments as $department)
                        <div class="master-row department-only-row">
                            <form id="department-update-{{ $department->id }}" action="{{ route('admin.master-data.departments.update', $department->id) }}" method="POST" class="d-contents">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                                <label class="form-check form-switch mb-0 px-10" title="Aktif / Nonaktif">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $department->is_active ? 'checked' : '' }}>
                                </label>
                                <button class="btn btn-sm btn-success" type="submit" title="Simpan perubahan"><i class="fa fa-save"></i></button>
                            </form>
                            <form action="{{ route('admin.master-data.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Hapus departemen {{ addslashes($department->name) }}? Data user lama tidak ikut terhapus, tetapi pilihan ini akan hilang dari master departemen.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit" title="Hapus departemen"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <div class="empty-state"><i class="fa fa-building-circle-xmark"></i><div>Belum ada departemen.</div></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
