<?php

use App\Http\Controllers\PegawaiController;
use App\Livewire\GradeLive;
use App\Livewire\PositionLive;
use App\Livewire\RankLive;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    // admin
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::prefix("admin")->group(function () {
        Route::get('/golongan', GradeLive::class)->name('golongan.index');
        Route::get('/jabatan', PositionLive::class)->name('jabatan.index');
        Route::get('/pangkat', RankLive::class)->name('pangkat.index');

        Route::resources([
            // 'jabatan' => PositionController::class,
            // 'pangkat' => RankController::class,
            // 'golongan' => GradeController::class
        ]);
    });

    Route::get('/homepage', [PegawaiController::class, 'index'])->name('pegawai.homepage');
    Route::get('/pengaturan', [PegawaiController::class, 'profile'])->name('pegawai.pengaturan');
});

require __DIR__.'/settings.php';
