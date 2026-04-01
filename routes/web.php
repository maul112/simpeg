<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TamuController;
use App\Livewire\GradeLive;
use App\Livewire\PositionLive;
use App\Livewire\RankLive;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/masuk', [TamuController::class, 'masukForm'])->name('tamu.masukForm');
Route::post('/masuk', [TamuController::class, 'masuk'])->name('tamu.masuk');
Route::get('/tamu', [TamuController::class, 'index'])->name('tamu.index');

Route::middleware(['auth', 'admin'])->group(function () {
    // admin
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::prefix("admin")->group(function () {
        Route::get('/golongan', GradeLive::class)->name('golongan.index');
        Route::get('/jabatan', PositionLive::class)->name('jabatan.index');
        Route::get('/pangkat', RankLive::class)->name('pangkat.index');
    });
});
    
Route::middleware(['auth'])->group(function () {
    Route::get('/homepage', [PegawaiController::class, 'index'])->name('pegawai.homepage');
    // Route::get('/pengaturan', [PegawaiController::class, 'setting'])->name('pegawai.pengaturan');
    Route::get('/profil', [PegawaiController::class, 'profile'])->name('pegawai.profil');
    Route::get('/notifikasi', [PegawaiController::class, 'notification'])->name('pegawai.notifikasi');
    Route::patch('/profil/email', [PegawaiController::class, 'updateEmail'])->name('profile.email.update');
});

require __DIR__.'/settings.php';
