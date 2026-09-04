<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Fitur Pelaporan Warga
|--------------------------------------------------------------------------
*/

// ==========================================
// 📢 PUBLIK / WARGA (TANPA LOGIN)
// ==========================================

// Landing Page & Halaman Artikel
Route::get('/', HomeController::class)->name('home');
Route::get('/artikel', [ArtikelController::class, 'publicIndex'])->name('articles.index');

// Feed Laporan
Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

// Form & Simpan Laporan
Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');

// Detail Laporan Publik
Route::get('/laporan/{id}', [ReportController::class, 'show'])
    ->whereNumber('id')
    ->name('reports.show');

Route::post('/laporan/{id}/analisis-ulang', [ReportController::class, 'reanalyze'])
    ->whereNumber('id')
    ->name('reports.reanalyze');

// Upvote Laporan oleh Warga
Route::post('/laporan/{id}/upvote', [ReportController::class, 'toggleUpvote'])->name('reports.upvote');


// ==========================================
// 🏛️ KHUSUS ADMIN (WAJIB LOGIN)
// ==========================================

Route::prefix('admin')->group(function () {
    // Halaman Login (Diperbaiki: Tidak ada double name)
    Route::view('/login', 'admin.login')->name('admin.login');

    // Halaman Utama Admin
    Route::view('/', 'admin.welcome')->name('admin.welcome');

    // Dashboard & Laporan Admin
    Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/laporan', [ReportController::class, 'adminDashboard'])->name('admin.reports');

    // Detail Laporan Admin (Diperbaiki: Menggunakan Controller)
    Route::get('/laporan/{id}', [ReportController::class, 'adminShow'])
        ->whereNumber('id')
        ->name('admin.reports.show');

    // Kelola Artikel Admin
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('admin.articles');
    Route::get('/artikel/buat', [ArtikelController::class, 'create'])->name('admin.articles.create');
    Route::post('/artikel/simpan', [ArtikelController::class, 'store'])->name('admin.articles.store');

    // Logout
    Route::any('/logout', function () {
        return redirect()->route('admin.login');
    })->name('admin.logout');
});