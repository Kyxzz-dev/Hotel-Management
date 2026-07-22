@extends('layouts.admin')

@section('title', 'Edit Akun Manajemen')

@section('content')
<div class="page-header">
    <div>
        <div class="page-kicker"><i class="fa fa-user-edit"></i> Edit Akun</div>
        <h1 class="page-title">Edit Akun Manajemen</h1>
        <p class="page-subtitle">Perbarui identitas dan role akses pengguna.</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-outline-primary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="panel-card">
    <div class="card-body">
        <form action="{{ route('admin.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="hrd" {{ old('role', $admin->role) === 'hrd' ? 'selected' : '' }}>HRD - hanya laporan</option>
                        <option value="head_department" {{ old('role', $admin->role) === 'head_department' ? 'selected' : '' }}>Head Department - akses penuh</option>
                        <option value="gm" {{ old('role', $admin->role) === 'gm' ? 'selected' : '' }}>GM - akses penuh</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $admin->tanggal_lahir ? \Carbon\Carbon::parse($admin->tanggal_lahir)->format('Y-m-d') : '') }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" required>
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="L" {{ old('jenis_kelamin', $admin->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $admin->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-warning text-dark"><i class="fa fa-save me-1"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
