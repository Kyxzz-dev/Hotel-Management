<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Registrasi publik dinonaktifkan. Akun dibuat oleh HRD melalui panel internal.
    Route::get('/register', function () {
        return redirect()->route('login')->with('error', 'Registrasi publik dinonaktifkan. Akun dibuat oleh HRD hotel.');
    })->name('register');
    Route::post('/register', function () {
        return redirect()->route('login')->with('error', 'Registrasi publik dinonaktifkan. Akun dibuat oleh HRD hotel.');
    })->name('register.post');

    Route::redirect('/', '/login');
});

// Profil dapat diakses dan diedit oleh semua role.
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [PegawaiController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [PegawaiController::class, 'updateProfile'])->name('profile.update');
});

// Dashboard dan laporan bisa dibuka oleh role manajemen.
Route::middleware(['auth', 'isManagement'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/cuti/laporan', [CutiController::class, 'laporanCuti'])->name('admin.cuti.laporan');
    Route::get('/cuti/laporan/export/{format}', [CutiController::class, 'exportLaporanCuti'])->name('admin.cuti.laporan.export');
});

// Approval cuti hanya untuk Head Department dan General Manager.
Route::middleware(['auth', 'isApprover'])->prefix('admin')->group(function () {
    Route::get('/cuti', [CutiController::class, 'index'])->name('admin.cuti.index');
    Route::patch('/cuti/{id}/status', [CutiController::class, 'updateStatus'])->name('admin.cuti.status');
    Route::patch('/cuti/{id}/approve', [CutiController::class, 'approve'])->name('admin.cuti.approve');
    Route::patch('/cuti/{id}/reject', [CutiController::class, 'reject'])->name('admin.cuti.reject');
});

// Kelola akun staff, akun manajemen, dan master departemen dipindahkan khusus ke HRD.
Route::middleware(['auth', 'isHrd'])->prefix('admin')->group(function () {
    Route::get('/pegawai', [PegawaiController::class, 'indexPegawai'])->name('admin.pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'createPegawai'])->name('admin.pegawai.create');
    Route::post('/pegawai/store', [PegawaiController::class, 'storePegawai'])->name('admin.pegawai.store');
    Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'editPegawai'])->name('admin.pegawai.edit');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'updatePegawai'])->name('admin.pegawai.update');
    Route::get('/pegawai/{id}/show', [PegawaiController::class, 'showPegawai'])->name('admin.pegawai.show');
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroyPegawai'])->name('admin.pegawai.destroy');

    Route::get('/master-data', [MasterDataController::class, 'index'])->name('admin.master-data.index');
    Route::post('/master-data/departments', [MasterDataController::class, 'storeDepartment'])->name('admin.master-data.departments.store');
    Route::put('/master-data/departments/{department}', [MasterDataController::class, 'updateDepartment'])->name('admin.master-data.departments.update');
    Route::delete('/master-data/departments/{department}', [MasterDataController::class, 'destroyDepartment'])->name('admin.master-data.departments.destroy');

    Route::get('/list', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// Staff sebagai pengaju cuti.
Route::middleware(['auth', 'isStaff'])->prefix('pegawai')->group(function () {
    Route::get('/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');

    Route::get('/cuti', [CutiController::class, 'indexPegawai'])->name('pegawai.cuti.index');
    Route::get('/cuti/create', [CutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/cuti/store', [CutiController::class, 'store'])->name('pegawai.cuti.store');

    // Alias lama supaya link lama tetap aman.
    Route::get('/profile', [PegawaiController::class, 'profile'])->name('pegawai.profile');
    Route::put('/profile/update', [PegawaiController::class, 'updateProfile'])->name('pegawai.profile.update');
});
