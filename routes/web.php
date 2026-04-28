<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TamuController;
use App\Livewire\PositionLive;
use App\Livewire\RankGradeLive;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/pengaduan', [TamuController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan', [TamuController::class, 'store'])->name('pengaduan.store')->middleware('throttle:pengaduan_sampah');
Route::get('/alur-lapor', [TamuController::class, 'alurLapor'])->name('alur-lapor');
// Route::get('/masuk', [TamuController::class, 'masukForm'])->name('tamu.masukForm');
// Route::post('/masuk', [TamuController::class, 'masuk'])->name('tamu.masuk');

// Route::middleware(['tamu.cek'])->group(function () {
//     Route::get('/tamu', [TamuController::class, 'index'])->name('tamu.index');
// });

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'admin', 'isAdminSimpeg'])->group(function () {
    Route::prefix("admin")->group(function () {
        Route::get('/pangkat', RankGradeLive::class)->name('pangkat.index');
        Route::get('/jabatan', PositionLive::class)->name('jabatan.index');
        Route::resource('pegawai', EmployeeController::class);
        Route::resource('notifikasi', NotificationController::class);
    });
    Route::get('/pegawai/export', [EmployeeController::class, 'export'])->name('pegawai.export');
});

Route::middleware(['auth', 'admin', 'isAdminSampah'])->group(function () {
    Route::prefix("admin")->group(function () {
        Route::get('/pengaduan', [ReportController::class, 'index'])->name('admin.pengaduan.index');
        Route::patch('/pengaduan/{pengaduan}/status', [ReportController::class, 'updateStatus'])->name('admin.pengaduan.status');
    });
});
    
Route::middleware(['auth', 'isPegawai'])->group(function () {
    Route::get('/homepage', [PegawaiController::class, 'index'])->name('pegawai.homepage');
    Route::get('/profil', [PegawaiController::class, 'profile'])->name('pegawai.profil');
    Route::get('/password', [PegawaiController::class, 'password'])->name('pegawai.password');
    Route::get('/duafaktor', [PegawaiController::class, 'duafaktor'])->name('pegawai.duafaktor')->middleware('password.confirm');
    Route::get('/tampilan', [PegawaiController::class, 'tampilan'])->name('pegawai.tampilan');
    Route::patch('/profil/email', [PegawaiController::class, 'updateEmail'])->name('profile.email.update');
    Route::patch('/profil/password', [PegawaiController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/notifikasi', [PegawaiController::class, 'notification'])->name('pegawai.notifikasi');
    Route::get('/notifikasi/{notification}', [PegawaiController::class, 'notificationShow'])->name('pegawai.notifikasi.show');
    Route::patch('/notifikasi/{notification}', [PegawaiController::class, 'notificationUpdate'])->name('pegawai.notifikasi.update')->middleware('throttle:pengaduan_sampah');

    Route::post('/pegawai/2fa/enable', [PegawaiController::class, 'enable2fa'])->name('pegawai.2fa.enable');
    Route::delete('/pegawai/2fa/disable', [PegawaiController::class, 'disable2fa'])->name('pegawai.2fa.disable');
    Route::post('/2fa/confirm', [PegawaiController::class, 'confirm2fa'])->name('pegawai.2fa.confirm');
});

require __DIR__.'/settings.php';
