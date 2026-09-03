<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
Route::view('/artikel', 'articles')->name('articles.index');

// Feed Laporan
Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

// Form & Simpan Laporan
Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');

// Route Detail Laporan (Membuka report-detail.blade.php)
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

// Login Form & Process

// Dashboard & Fitur Admin (Dilindungi Auth)
Route::prefix('admin')->group(function () {
    // Halaman Login
    Route::view('/login', 'admin.login')->name('admin.login')->name('login');

    // Halaman Utama Admin (Welcome)
    Route::view('/', 'admin.welcome')->name('admin.welcome');

    // Dashboard Admin
    // Memanggil method adminDashboard dari ReportController
    Route::get('/laporan', [ReportController::class, 'adminDashboard'])->name('admin.reports');
    Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/laporan/{id}', function (string $id) {
        return view('admin.report-detail', ['reportId' => $id]);
    })->whereNumber('id')->name('admin.reports.show');

  Route::view('/artikel', 'admin.articles')->name('admin.articles');
    Route::view('/artikel/buat', 'admin.article-create')->name('admin.articles.create');
    Route::any('/logout', function () {
        return redirect()->route('admin.login');
    })->name('admin.logout');
});