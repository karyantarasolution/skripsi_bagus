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
    // Route lainnya...
    Route::get('/dashboard', [AdminJaringanController::class, 'dashboard'])->name('dashboard');
    Route::get('/live-traffic', [AdminJaringanController::class, 'liveTraffic'])->name('traffic');
    Route::get('/log-intrusi', [AdminJaringanController::class, 'logIntrusi'])->name('log');

    // --- BAGIAN INI YANG HARUS ADA ---
    // Untuk nampilin halaman Action
    Route::get('/action', [AdminJaringanController::class, 'action'])->name('action');
    
    // Untuk memproses form Action (Gunakan POST)
    Route::post('/action/process', [AdminJaringanController::class, 'processAction'])->name('action.process');
    // ---------------------------------

    Route::get('/laporan', [AdminJaringanController::class, 'laporanMenu'])->name('laporan');
Route::get('/laporan/cetak/{tipe}', [AdminJaringanController::class, 'cetakLaporan'])->name('laporan.cetak');
});

Route::prefix('manajemen')->group(function () {
    Route::get('/dashboard', [ManajemenController::class, 'dashboard'])->name('manajemen.dashboard');
    Route::get('/laporan', [ManajemenController::class, 'downloadLaporan'])->name('manajemen.laporan');
    
    // Route cetak PDF khusus manajemen
    Route::get('/laporan/cetak/{tipe}', [ManajemenController::class, 'cetakLaporan'])->name('manajemen.laporan.cetak');
});
require __DIR__.'/auth.php';