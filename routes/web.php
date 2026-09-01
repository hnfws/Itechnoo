<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 📢 PUBLIK / WARGA (TANPA LOGIN)
// ==========================================

// Feed Laporan
Route::get('/', [ReportController::class, 'index'])->name('reports.index');

// Form & Kirim Laporan (Siapa saja bisa akses)
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

// Upvote Laporan oleh Warga
Route::post('/reports/{id}/upvote', [ReportController::class, 'toggleUpvote'])->name('reports.upvote');


// ==========================================
// 🏛️ KHUSUS ADMIN (WAJIB LOGIN)
// ==========================================

// Halaman & Proses Login Admin
Route::get('/admin/login', [ReportController::class, 'showAdminLoginForm'])->name('login');
Route::post('/admin/login', [ReportController::class, 'adminLogin'])->name('admin.login.submit');

// Dashboard & Fitur Admin (Dilindungi Auth)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.status');
    Route::post('/logout', [ReportController::class, 'adminLogout'])->name('admin.logout');
});