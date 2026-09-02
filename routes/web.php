<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// <<<<<<< HEAD
// // ==========================================
// // 📢 PUBLIK / WARGA (TANPA LOGIN)
// // ==========================================

// // Feed Laporan
// Route::get('/', [ReportController::class, 'index'])->name('reports.index');

// // Form & Kirim Laporan (Siapa saja bisa akses)
// Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
// Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

// // Upvote Laporan oleh Warga
// Route::post('/reports/{id}/upvote', [ReportController::class, 'toggleUpvote'])->name('reports.upvote');


// // ==========================================
// // 🏛️ KHUSUS ADMIN (WAJIB LOGIN)
// // ==========================================

// // Halaman & Proses Login Admin
// Route::get('/admin/login', [ReportController::class, 'showAdminLoginForm'])->name('login');
// Route::post('/admin/login', [ReportController::class, 'adminLogin'])->name('admin.login.submit');

// // Dashboard & Fitur Admin (Dilindungi Auth)
// Route::prefix('admin')->middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
//     Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.status');
//     Route::post('/logout', [ReportController::class, 'adminLogout'])->name('admin.logout');
// });
// =======
Route::view('/', 'home')->name('home');
// --- Area admin ---
Route::view('/admin/login', 'admin.login')->name('admin.login');

// PLACEHOLDER — proses login (cek user/password) dikerjakan backend.
// Backend mengganti ini dengan Auth::attempt(...) lalu redirect ke /admin.
Route::post('/admin/login', function () {
    return redirect()->route('admin.welcome');
})->name('admin.login.attempt');

// Halaman-halaman setelah login. Nanti backend melindungi rute ini dengan
// middleware ['auth', 'can:admin'] agar hanya admin yang bisa mengakses.
Route::view('/admin', 'admin.welcome')->name('admin.welcome');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::view('/admin/laporan', 'admin.reports')->name('admin.reports');
Route::get('/admin/laporan/{id}', function (string $id) {
    return view('admin.report-detail', ['reportId' => $id]);
})->whereNumber('id')->name('admin.reports.show');

// PLACEHOLDER — logout dikerjakan backend (Auth::logout() + invalidate session).
Route::post('/admin/logout', function () {
    return redirect()->route('admin.login');
})->name('admin.logout');

Route::view('/artikel', 'articles')->name('articles.index');

Route::view('/laporan', 'reports')->name('reports.index');
Route::view('/laporan/buat', 'report-create')->name('reports.create');

// PLACEHOLDER — supaya tombol "Kirim Laporan" berfungsi saat demo.
// Backend mengganti ini dengan proses simpan ke database + validasi asli.
Route::post('/laporan', function () {
    return redirect()
        ->route('reports.index')
        ->with('submitted', true);
})->name('reports.store');

Route::get('/laporan/{id}', function (string $id) {
    return view('report-detail', ['reportId' => $id]);
})->whereNumber('id')->name('reports.show');
// >>>>>>> 0642a3f41e1b8df3251dfa95f19f46a16e4a6442
