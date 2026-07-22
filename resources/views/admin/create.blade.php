@extends('layouts.admin')

@section('title', 'Tambah Akun Manajemen')

@section('content')
<div class="page-header">
    <div>
        <div class="page-kicker"><i class="fa fa-user-plus"></i> Akun Baru</div>
        <h1 class="page-title">Tambah Akun Manajemen</h1>
        <p class="page-subtitle">Pilih role HRD, Head Department, atau GM sesuai hak akses pengguna.</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-outline-primary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="panel-card">
    <div class="card-body">
        <form action="{{ route('admin.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="hrd" {{ old('role') === 'hrd' ? 'selected' : '' }}>HRD - hanya laporan</option>
                        <option value="head_department" {{ old('role') === 'head_department' ? 'selected' : '' }}>Head Department - akses penuh</option>
                        <option value="gm" {{ old('role') === 'gm' ? 'selected' : '' }}>GM - akses penuh</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" required>
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan Akun</button>
        </form>
    </div>
</div>
@endsection
