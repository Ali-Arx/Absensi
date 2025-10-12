<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggunaController;

// ROUTE UTAMA / LOGIN (guest)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login'); // tampilkan halaman login
    })->name('login');
});

// ROUTE SETELAH LOGIN (auth)
Route::middleware('auth')->group(function () {

    // Redirect otomatis sesuai role
    Route::get('/dashboard', function () {
        $user = auth('')->user();
        if ($user->role === 'hr') {
            return redirect()->route('dashboard.hr');
        } elseif ($user->role === 'direktur') {
            return redirect()->route('dashboard.direktur');
        } elseif ($user->role === 'atasan') {
            return redirect()->route('dashboard.atasan');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('dashboard.karyawan');
        }
        abort(403, 'Role tidak dikenal.');
    })->name('dashboard');

    // HR
    Route::middleware('role:hr')->group(function () {
        Route::get('/dashboard/hr', [DashboardController::class, 'index'])->name('dashboard.hr');
    });

    // Direktur
    Route::middleware('role:direktur')->group(function () {
        Route::get('/dashboard/direktur', fn() => view('dashboard.direktur'))->name('dashboard.direktur');
    });

    // Atasan
    Route::middleware('role:atasan')->group(function () {
        Route::get('/dashboard/atasan', [DashboardController::class, 'atasan'])->name('dashboard.atasan');
    });

    // Karyawan
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/dashboard/karyawan', fn() => view('dashboard.karyawan'))->name('dashboard.karyawan');
    });

    // Routes Cuti
    Route::middleware('auth')->group(function () {
    Route::get('/cuti/create', [CutiController::class, 'create'])->name('cuti.create');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
    
    Route::get('/cuti/riwayat', [CutiController::class, 'riwayat'])->name('cuti.riwayat');
    Route::get('/cuti/data', [CutiController::class, 'data'])->name('cuti.data');
    Route::get(uri: '/cuti/data/export', action: [CutiController::class, 'exportData'])->name(name: 'cuti.data.export');
    Route::get(uri: '/cuti/data/filter', action: [CutiController::class, 'filterData'])->name(name: 'cuti.data.filter');
    Route::get(uri: '/cuti/show', action: [CutiController::class, 'show'])->name(name: 'cuti.show');
    
    // Approval Routes
    Route::get('/cuti/approval', [CutiController::class, 'approvalIndex'])->name('cuti.approval');
    Route::post('/cuti/{cuti}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
    Route::post('/cuti/{cuti}/reject', [CutiController::class, 'reject'])->name('cuti.reject');
    Route::get('/cuti/riwayat/export', [CutiController::class, 'export'])->name('cuti.riwayat.export');
});

    // Fitur Absensi 
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    Route::get('/absensi/riwayat/export', [AbsensiController::class, 'export'])->name('absensi.riwayat.export');
    Route::get('/absensi/data', [AbsensiController::class, 'data'])->name('absensi.data');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Lembur 
    Route::middleware(['auth'])->group(function () {
    Route::get('/lembur/create', [LemburController::class, 'create'])->name('lembur.create');
    Route::post('/lembur/store', [LemburController::class, 'store'])->name('lembur.store');
    Route::get('/lembur/data', [LemburController::class, 'data'])->name('lembur.data');
    Route::get('/lembur/approval', [LemburController::class, 'approvalIndex'])->name('lembur.approval');
    Route::post('/lembur/{lembur}/approve', [LemburController::class, 'approve'])->name('lembur.approve');
    Route::post('/lembur/{lembur}/reject', [LemburController::class, 'reject'])->name('lembur.reject');
    Route::get('/lembur/riwayat', [LemburController::class, 'riwayat'])->name('lembur.riwayat');
});


    // Fitur Jam Kerja 
    Route::middleware('role:hr')->group(function () {
        Route::get('/jam-kerja', [DashboardController::class, 'jamKerja'] )->name('jam-kerja.index');
        Route::get('/jam-kerja/create', [DashboardController::class, 'createJamKerja'] )->name('jam-kerja.create');
        Route::post('/jam-kerja', [DashboardController::class, 'storeJamKerja'] )->name('jam-kerja.store');
        Route::get('/jam-kerja/{id}/edit', [DashboardController::class, 'editJamKerja'] )->name('jam-kerja.edit');
        Route::put('/jam-kerja/{id}', [DashboardController::class, 'updateJamKerja'] )->name('jam-kerja.update');
        Route::delete('/jam-kerja/{id}', [DashboardController::class, 'deleteJamKerja'] )->name('jam-kerja.delete');
    });

    // Fitur User Management 
    Route::middleware('role:hr')->group(function () {
        Route::get('/pengguna', [PenggunaController::class, 'index'] )->name('pengguna.index');
        Route::get('/pengguna/create', [PenggunaController::class, 'create'] )->name('pengguna.create');
        Route::post('/pengguna', [PenggunaController::class, 'store'] )->name('pengguna.store');
        Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'] )->name('pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'] )->name('pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'delete'] )->name('pengguna.delete');
    });
});

// route default Laravel Breeze / Fortify
require __DIR__.'/auth.php';
