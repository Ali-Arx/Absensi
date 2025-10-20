<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\auth\PasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ROUTE UTAMA / LOGIN (guest)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login'); // tampilkan halaman login
    })->name('login');
});

// ROUTE SETELAH LOGIN (auth)
Route::middleware('auth')->group(function () {

    // Redirect otomatis sesuai role
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


    // HR
    Route::middleware('role:hr')->group(function () {
        Route::get('/dashboard/hr', [DashboardController::class, 'index'])->name('dashboard.hr');
    });

    // Direktur
    Route::middleware('role:direktur')->group(function () {
        Route::get('/dashboard/direktur', [DashboardController::class, 'direktur'])->name('dashboard.direktur');
    });

    // Atasan
    Route::middleware('role:atasan')->group(function () {
        Route::get('/dashboard/atasan', [DashboardController::class, 'atasan'])->name('dashboard.atasan');
    });

    // Karyawan
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/dashboard/karyawan', [DashboardController::class, 'karyawan'])->name('dashboard.karyawan');
    });

    // Routes Cuti
    Route::middleware('auth')->group(function () {
        Route::get('/cuti/create', [CutiController::class, 'create'])->name('cuti.create');
        Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');

        Route::get('/cuti/riwayat', [CutiController::class, 'riwayat'])->name('cuti.riwayat');
        Route::get('/cuti/riwayat/export', [CutiController::class, 'export'])->name('cuti.riwayat.export');

        Route::get('/cuti/data', [CutiController::class, 'data'])->name('cuti.data');
        Route::get('/cuti/export-data', [CutiController::class, 'exportCuti'])->name('cuti.export.data');
        Route::post('/cuti/import-data', [CutiController::class, 'importCuti'])->name('cuti.import.data');
        Route::get('/cuti/data/filter', [CutiController::class, 'filterData'])->name('cuti.data.filter');

        // Route approval 
        Route::get('/cuti/approval', [CutiController::class, 'approvalIndex'])->name('cuti.approval');
        Route::put('/cuti/{cuti}/process-approval', [CutiController::class, 'processApproval'])->name('cuti.processApproval');

        // Route dengan parameter {id} 
        Route::get('/cuti/{id}', [CutiController::class, 'show'])->name('cuti.show');
    });

    // Fitur Absensi 
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    Route::get('/absensi/riwayat/export', [AbsensiController::class, 'export'])->name('absensi.riwayat.export');
    Route::get('/absensi/data', [AbsensiController::class, 'data'])->name('absensi.data');
    Route::get(uri: '/absensi/data/export-all', action: [AbsensiController::class, 'exportAll'])->name(name: 'absensi.data.exportAll');
    Route::post(uri: '/absensi/data/import', action: [AbsensiController::class, 'import'])->name(name: 'absensi.data.import');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', action: [PasswordController::class, 'update'])->name('profile.password');

    // Fitur Lembur 
    Route::middleware(['auth'])->group(function () {
        Route::get('/lembur/detail/{id}', [LemburController::class, 'show'])->name('lembur.detail');
        Route::get('/lembur/create', [LemburController::class, 'create'])->name('lembur.create');
        Route::post('/lembur/store', [LemburController::class, 'store'])->name('lembur.store');
        Route::get('/lembur/data', [LemburController::class, 'data'])->name('lembur.data');
        Route::get('/lembur/approval', [LemburController::class, 'approvalIndex'])->name('lembur.approval');
        Route::get('/lembur/riwayat', [LemburController::class, 'riwayat'])->name('lembur.riwayat');
        Route::put('/lembur/{lembur}/process-approval', [LemburController::class, 'processApproval'])->name('lembur.processApproval');
        // (Asumsi di dalam grup controller LemburController Anda)
        Route::get('/lembur/export-data', [LemburController::class, 'exportLembur'])->name('lembur.export.data');
        Route::post('/lembur/import-data', [LemburController::class, 'importLembur'])->name('lembur.import.data');
    });


    // Fitur Jam Kerja 
    Route::middleware('role:hr')->group(function () {
        Route::get('/jam-kerja', [DashboardController::class, 'jamKerja'])->name('jam-kerja.index');
        Route::get('/jam-kerja/create', [DashboardController::class, 'createJamKerja'])->name('jam-kerja.create');
        Route::post('/jam-kerja', [DashboardController::class, 'storeJamKerja'])->name('jam-kerja.store');
        Route::get('/jam-kerja/{id}/edit', [DashboardController::class, 'editJamKerja'])->name('jam-kerja.edit');
        Route::put('/jam-kerja/{id}', [DashboardController::class, 'updateJamKerja'])->name('jam-kerja.update');
        Route::delete('/jam-kerja/{id}', [DashboardController::class, 'deleteJamKerja'])->name('jam-kerja.delete');
    });

    // Fitur User Management 
    Route::middleware('role:hr')->group(function () {
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{user}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.delete');
        Route::post('/pengguna/import', [PenggunaController::class, 'import'])->name('pengguna.data.import');
    });

});

// route default Laravel Breeze / Fortify
require __DIR__ . '/auth.php';
