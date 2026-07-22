@extends('layouts.admin')

@section('title', 'Edit Data Staff')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="panel-card hotel-form-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="action-icon"><i class="fa fa-user-edit"></i></div>
                    <div>
                        <h4 class="fw-bold mb-1">Edit Staff</h4>
                        <div class="text-muted small">Perbarui profil, departemen, jabatan, atau password staff.</div>
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

                <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Masuk Kerja</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('Y-m-d') : optional($pegawai->created_at)->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="form-control" required>
                        <div class="form-text">Ubah tanggal ini jika tanggal mulai kerja staff berbeda dari tanggal akun dibuat.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($pegawai->tanggal_lahir)->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role / Posisi</label>
                        <input type="hidden" name="position" value="{{ old('position', $pegawai->position_label ?: 'Staff') }}">
                        <input type="text" class="form-control bg-light" value="{{ old('position', $pegawai->position_label ?: 'Staff') }}" disabled>
                        <div class="form-text">Role/posisi staff dikunci. Akses sistem tetap sebagai staff.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Departemen</label>
                        <select name="department" class="form-select" required>
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ old('department', $pegawai->department) === $department ? 'selected' : '' }}>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Contoh: Marketing Executive, Front Office Agent">
                        <div class="form-text">Jabatan staff dapat diedit oleh HRD.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
