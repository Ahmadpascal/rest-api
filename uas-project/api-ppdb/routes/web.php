<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

// Pastikan Filament routes terdaftar (jika belum otomatis)
// Filament biasanya menangani ini sendiri, tapi jika perlu eksplisit:
use Filament\Http\Controllers\Auth\LoginController; // Jika perlu custom login
// Tambahkan ini jika Filament routes tidak muncul otomatis (jarang diperlukan)

// Guest routes (tidak perlu login)
Route::middleware(['guest'])->group(function () {
    Route::get('/', fn () => view('welcome'))->name('home');
    
    // Opsional: Route untuk form pendaftaran PPDB publik (jika ada view 'ppdb.register')
    // Route::get('/register-ppdb', fn () => view('ppdb.register'))->name('ppdb.register');
    // Route::post('/register-ppdb', [App\Http\Controllers\PpdbController::class, 'store'])->name('ppdb.store');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Admin panel (Filament) - biasanya di /admin
    // Filament menangani ini otomatis, tapi jika perlu custom:
    // Route::prefix('admin')->group(function () {
    //     Route::get('/', [Filament\Http\Controllers\DashboardController::class, 'index'])->name('filament.admin.dashboard');
    //     // Tambahkan routes lain jika diperlukan, tapi sebaiknya biarkan Filament menangani
    // });

    // User dashboard (React SPA)
    Route::middleware(['verified', 'role:user'])->group(function () {
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
        
        // Fallback untuk SPA React: Tangkap semua route di dalam dashboard
        // Ini memungkinkan React Router menangani routing internal (misalnya /dashboard/profile)
        Route::get('/dashboard/{any}', fn () => view('dashboard'))->where('any', '.*')->name('dashboard.fallback');
    });
});