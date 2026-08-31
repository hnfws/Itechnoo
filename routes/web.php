<?php

use Illuminate\Support\Facades\Route;

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
