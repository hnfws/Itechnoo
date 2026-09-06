<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController; // <-- Tambahkan ini

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
Route::get('/artikel/{artikel}', [ArtikelController::class, 'show'])->name('articles.show');

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
// 🏛️ KHUSUS ADMIN
// ==========================================

Route::prefix('admin')->group(function () {

    // --- A. UNTUK TAMU / ADMIN BELUM LOGIN ---
    Route::middleware('guest:admin')->group(function () {
        // Halaman Login Admin
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        
        // Proses Submit Form Login (Memproses form dari view kamu)
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.attempt');
    });

    // --- B. UNTUK ADMIN YANG SUDAH LOGIN (PROTEKSI) ---
    Route::middleware('auth:admin')->group(function () {
        
        // Halaman Utama Admin
        Route::view('/', 'admin.welcome')->name('admin.welcome');

        // Dashboard & Laporan Admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/laporan', [ReportController::class, 'adminDashboard'])->name('admin.reports');

        // Detail Laporan Admin
        Route::get('/laporan/{id}', [ReportController::class, 'adminShow'])
            ->whereNumber('id')
            ->name('admin.reports.show');

        // Update Status Laporan
        Route::patch('/laporan/{id}/status', [ReportController::class, 'updateStatus'])
            ->whereNumber('id')
            ->name('admin.reports.status');

        // Hapus Laporan
        Route::delete('/laporan/{id}', [ReportController::class, 'destroy'])
            ->whereNumber('id')
            ->name('admin.reports.destroy');

        // Kelola Artikel Admin
        Route::get('/artikel', [ArtikelController::class, 'index'])->name('admin.articles');
        Route::get('/artikel/buat', [ArtikelController::class, 'create'])->name('admin.articles.create');
        Route::post('/artikel/simpan', [ArtikelController::class, 'store'])->name('admin.articles.store');
        Route::get('/artikel/{artikel}/edit', [ArtikelController::class, 'edit'])->name('admin.articles.edit');
        Route::patch('/artikel/{artikel}', [ArtikelController::class, 'update'])->name('admin.articles.update');
        Route::delete('/artikel/{artikel}', [ArtikelController::class, 'destroy'])->name('admin.articles.destroy');

        // Logout Admin
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });

});