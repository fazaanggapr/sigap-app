<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

// --- PERBAIKAN UX --- 
// Redirect Otomatis: Buka web -> Langsung lempar ke Login 
Route::get('/', function () {
    return redirect()->route('login');
});

// Jalur Tamu (Belum Login) 
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    // Jalur Registrasi 
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');
});

// Jalur Khusus Member (Sudah Login) 
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Dashboard Admin 
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    // Dashboard Warga (Sementara) 
    Route::get('/warga/dashboard', function () {
        return "Halo Warga! Ini halaman kamu.";
    })->name('user.dashboard');
    // Rute untuk Warga 
    Route::get('/lapor', [ReportController::class, 'index'])->name('user.lapor');
    Route::post('/lapor', [ReportController::class, 'store'])
        ->name('user.lapor.store');

    Route::get('/report/export/pdf', [
        ReportController::class,
        'exportPdf'
    ])->name('report.export');
    
    // Route dengan Parameter {id} (Wildcard) 
// Artinya: URL-nya dinamis, misal /report/1, /report/5, dst. 
// 1. Jalur Detail Laporan (Membawa ID Laporan) 
    Route::get('/report/{report}', [ReportController::class, 'show'])
        ->name('report.show');
    // 2. Jalur Update Status (Mengubah Data) 
    Route::put('/report/{report}', [ReportController::class, 'update'])
        ->name('report.update');

    Route::post('/response', [ResponseController::class, 'store'])
        ->name('response.store');
});
