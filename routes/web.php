<?php

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
Route::view('/', 'home')->name('home');
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
Route::get('/admin/login', [ReportController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [ReportController::class, 'adminLogin'])->name('admin.login.attempt');

// Dashboard & Fitur Admin (Dilindungi Auth)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [ReportController::class, 'adminDashboard'])->name('admin.welcome');
    Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/laporan', [ReportController::class, 'adminDashboard'])->name('admin.reports');
    
    Route::get('/laporan/{id}', function (string $id) {
        return view('admin.report-detail', ['reportId' => $id]);
    })->whereNumber('id')->name('admin.reports.show');

    Route::patch('/laporan/{id}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.status');
    Route::post('/logout', [ReportController::class, 'adminLogout'])->name('admin.logout');
});