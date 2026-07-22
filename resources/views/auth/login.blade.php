@extends('layouts.app')

@section('content')
<div class="hotel-auth-page hotel-auth-page-single">
    <div class="hotel-auth-bg hotel-auth-bg-lobby"></div>
    <div class="hotel-auth-overlay"></div>

    <div class="hotel-auth-single-shell">
        <div class="hotel-auth-copy">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="hotel-brand-mark"><i class="fa fa-hotel"></i></div>
                <div>
                    <div class="hotel-brand-title">Mövenpick Jakarta City Centre</div>
                    <div class="hotel-brand-subtitle">Internal Leave Management System</div>
                </div>
            </div>
            <div class="hotel-auth-kicker mb-3"><i class="fa fa-sparkles me-2"></i>Hospitality Staff Portal</div>
            <h1 class="hotel-auth-title mb-3">Sistem cuti internal untuk operasional hotel yang lebih rapi.</h1>
            <p class="hotel-auth-desc mb-0">
                Akses khusus HRD, Head Department, General Manager, dan Staff untuk pengajuan cuti, laporan, dan data karyawan lintas departemen hotel.
            </p>
        </div>

        <div class="hotel-auth-login-panel">
            <div class="mb-4">
                <span class="hotel-auth-chip"><i class="fa fa-lock me-2"></i>Akses Internal Hotel</span>
                <h3 class="fw-bold mt-3 mb-2 text-dark">Login Akun</h3>
                <p class="text-muted mb-0">Masuk menggunakan akun yang dibuat oleh HRD atau pimpinan terkait.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success"><i class="fa fa-circle-check me-1"></i>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fa fa-triangle-exclamation me-1"></i>{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger"><i class="fa fa-triangle-exclamation me-1"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="fa fa-envelope me-1 text-warning"></i>Email Akun</label>
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="contoh@hotel.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark"><i class="fa fa-key me-1 text-warning"></i>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required placeholder="Masukkan password">
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 hotel-auth-submit hotel-auth-next-btn">
                    <i class="fa fa-right-to-bracket me-2"></i>Login
                </button>
            </form>

            <div class="hotel-auth-note mt-4">
                <div class="d-flex gap-3 align-items-start">
                    <div class="hotel-auth-note-icon"><i class="fa fa-circle-info"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Registrasi publik dinonaktifkan</div>
                        <small class="text-muted">Akun dibuat melalui HRD atau akun pimpinan yang memiliki akses kelola staff.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
