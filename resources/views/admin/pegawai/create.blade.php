@extends('layouts.admin')

@section('title', 'Tambah Staff')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="panel-card hotel-form-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="action-icon"><i class="fa fa-user-plus"></i></div>
                    <div>
                        <h4 class="fw-bold mb-1">Tambah Staff Baru</h4>
                        <div class="text-muted small">HRD dapat membuat dan mengelola akun staff hotel.</div>
                    </div>
                </div>

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

                <form action="{{ route('admin.pegawai.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Masuk Kerja</label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                        <div class="form-text">Saldo cuti pertama muncul setelah staff genap 1 bulan kerja dari tanggal ini.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role / Posisi</label>
                        <input type="hidden" name="position" value="Staff">
                        <input type="text" class="form-control bg-light" value="Staff" disabled>
                        <div class="form-text">Role/posisi akun staff dikunci sebagai Staff untuk menjaga alur akses sistem.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Departemen</label>
                        <select name="department" class="form-select" required>
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ old('department') === $department ? 'selected' : '' }}>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Contoh: Marketing Executive, Front Office Agent">
                        <div class="form-text">Jabatan hanya digunakan untuk akun staff.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan untuk generate otomatis">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Isi jika membuat password manual">
                    </div>
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
