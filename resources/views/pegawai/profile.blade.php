@extends(Auth::user()->role === 'staff' ? 'layouts.pegawai' : 'layouts.admin')

@section('title', 'Profil Saya')

@section('content')
@php($user = Auth::user())

<div class="hotel-profile-hero mb-4">
    <div class="hotel-profile-hero-pattern"></div>
    <div class="hotel-profile-avatar-lg">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>

    <div class="hotel-profile-hero-content">
        <div class="page-kicker mb-2">
            <i class="fa fa-id-badge"></i> Profil Internal Hotel
        </div>
        <h1 class="page-title text-white">{{ $user->name }}</h1>
        <p class="hotel-profile-subtitle mb-0">
            Kelola identitas akun internal dengan tampilan profesional. Role, departemen, dan jabatan staff dikunci agar data operasional hotel tetap konsisten.
        </p>

        <div class="hotel-profile-badges mt-3">
            <span><i class="fa fa-shield-halved"></i>{{ $user->position_label }}</span>
            <span><i class="fa fa-building-user"></i>{{ $user->department ?: 'Departemen belum diisi' }}</span>
            @if($user->role === 'staff')
                <span><i class="fa fa-id-badge"></i>{{ $user->jabatan ?: 'Jabatan belum diisi' }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="panel-card profile-summary-card profile-premium-card h-100">
            <div class="card-body p-4 text-center">
                <div class="profile-big-avatar mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <div class="text-muted mb-3">{{ $user->email }}</div>

                <div class="account-status-pill mx-auto mb-4">
                    <span class="status-dot"></span>
                    Akun Aktif
                </div>

                <div class="profile-info-list text-start">
                    <div class="profile-info-item profile-info-featured">
                        <div>
                            <span>Role / Posisi</span>
                            <strong>{{ $user->position_label }}</strong>
                        </div>
                        <i class="fa fa-shield-halved"></i>
                    </div>

                    <div class="profile-info-item">
                        <div>
                            <span>Departemen</span>
                            <strong>{{ $user->department ?: 'Departemen belum diisi' }}</strong>
                        </div>
                        <i class="fa fa-building-user"></i>
                    </div>

                    @if($user->role === 'staff')
                        <div class="profile-info-item">
                            <div>
                                <span>Jabatan</span>
                                <strong>{{ $user->jabatan ?: 'Jabatan belum diisi' }}</strong>
                            </div>
                            <i class="fa fa-id-badge"></i>
                        </div>
                    @endif
                </div>

                <div class="profile-lock-note mt-4">
                    <i class="fa fa-lock"></i>
                    <span>Data organisasi hanya dapat diperbarui oleh HRD atau akun manajemen terkait.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="panel-card hotel-form-card profile-form-card">
            <div class="panel-card-header profile-form-header">
                <div>
                    <h5 class="fw-bold mb-1">Data Profil</h5>
                    <div class="text-muted small">Perbarui data pribadi dan password akun.</div>
                </div>
                <div class="form-header-icon">
                    <i class="fa fa-pen-to-square"></i>
                </div>
            </div>

            <div class="card-body p-4">
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

                <form action="{{ route('profile.update') }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($user->tanggal_lahir)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role / Posisi</label>
                        @if($user->role === 'staff')
                            <div class="locked-field">
                                <i class="fa fa-lock"></i>
                                <span>{{ $user->position_label }}</span>
                            </div>
                            <div class="form-text">Role/posisi staff dikunci dan hanya dapat diubah oleh HRD.</div>
                        @else
                            <select name="position" class="form-select">
                                <option value="">-- Pilih Role / Posisi --</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position }}" {{ old('position', $user->position_label) === $position ? 'selected' : '' }}>{{ $position }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Departemen</label>
                        @if($user->role === 'staff')
                            <div class="locked-field">
                                <i class="fa fa-lock"></i>
                                <span>{{ $user->department ?: 'Departemen belum diisi' }}</span>
                            </div>
                            <div class="form-text">Departemen staff dikunci dan hanya dapat diubah oleh HRD.</div>
                        @else
                            <select name="department" class="form-select">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" {{ old('department', $user->department) === $department ? 'selected' : '' }}>{{ $department }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    @if($user->role === 'staff')
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jabatan</label>
                            <div class="locked-field">
                                <i class="fa fa-lock"></i>
                                <span>{{ $user->jabatan ?: 'Jabatan belum diisi' }}</span>
                            </div>
                            <div class="form-text">Jabatan staff dikunci dan hanya dapat diubah oleh HRD.</div>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        @if($user->role === 'staff')
                            <a href="{{ route('pegawai.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                        @else
                            <a href="{{ route('admin.cuti.laporan') }}" class="btn btn-outline-secondary">Kembali</a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
