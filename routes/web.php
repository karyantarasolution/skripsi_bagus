<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminJaringanController;
use App\Http\Controllers\ManajemenController;
use App\Http\Controllers\RuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'super_admin') return redirect()->route('superadmin.dashboard');
    if ($role === 'admin_jaringan') return redirect()->route('adminjaringan.dashboard');
    if ($role === 'manajemen') return redirect()->route('manajemen.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/users', [SuperAdminController::class, 'manajemenUser'])->name('users');
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
    
    Route::get('/rules', [SuperAdminController::class, 'manajemenRule'])->name('rules');
    Route::get('/rules/create', [SuperAdminController::class, 'createRule'])->name('rules.create');
    Route::post('/rules', [SuperAdminController::class, 'storeRule'])->name('rules.store');
    Route::get('/rules/{rule}/edit', [SuperAdminController::class, 'editRule'])->name('rules.edit');
    Route::put('/rules/{rule}', [SuperAdminController::class, 'updateRule'])->name('rules.update');
    Route::delete('/rules/{rule}', [SuperAdminController::class, 'destroyRule'])->name('rules.destroy');

    Route::get('/setting', [SuperAdminController::class, 'settingJaringan'])->name('setting');
    Route::put('/setting/update', [SuperAdminController::class, 'updateSetting'])->name('setting.update');
});

Route::middleware(['auth', 'role:admin_jaringan'])->prefix('adminjaringan')->name('adminjaringan.')->group(function () {
    Route::get('/dashboard', [AdminJaringanController::class, 'dashboard'])->name('dashboard');
    Route::get('/live-traffic', [AdminJaringanController::class, 'liveTraffic'])->name('traffic');
    Route::get('/log-intrusi', [AdminJaringanController::class, 'logIntrusi'])->name('log');

    Route::get('/action', [AdminJaringanController::class, 'action'])->name('action');
    Route::post('/action/process', [AdminJaringanController::class, 'processAction'])->name('action.process');

    // Laporan utama
    Route::get('/laporan', [AdminJaringanController::class, 'laporanMenu'])->name('laporan');
    Route::get('/laporan/cetak/{tipe}', [AdminJaringanController::class, 'cetakLaporan'])->name('laporan.cetak');

    // Laporan Analitik Statistik Anomali
    Route::get('/laporan-analitik', [AdminJaringanController::class, 'laporanAnalitik'])->name('laporan.analitik');
    Route::get('/laporan-analitik/cetak', [AdminJaringanController::class, 'cetakLaporanAnalitik'])->name('laporan.analitik.cetak');

    // Log Penanganan Insiden
    Route::get('/log-penanganan', [AdminJaringanController::class, 'logPenanganan'])->name('log.penanganan');
    Route::get('/log-penanganan/cetak', [AdminJaringanController::class, 'cetakLogPenanganan'])->name('log.penanganan.cetak');

    // Laporan Ketersediaan Infrastruktur
    Route::get('/laporan-ketersediaan', [AdminJaringanController::class, 'laporanKetersediaan'])->name('laporan.ketersediaan');
    Route::get('/laporan-ketersediaan/cetak', [AdminJaringanController::class, 'cetakLaporanKetersediaan'])->name('laporan.ketersediaan.cetak');
});

Route::middleware(['auth', 'role:manajemen'])->prefix('manajemen')->name('manajemen.')->group(function () {
    Route::get('/dashboard', [ManajemenController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [ManajemenController::class, 'downloadLaporan'])->name('laporan');
    Route::get('/laporan/cetak/{tipe}', [ManajemenController::class, 'cetakLaporan'])->name('laporan.cetak');
});
require __DIR__.'/auth.php';