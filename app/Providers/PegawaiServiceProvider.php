<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class PegawaiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Cek jika nama view-nya mengandung kata 'header_pegawai'
            // if (str_contains($view->getName(), 'header_pegawai')) {
            //     // Hentikan aplikasi dan cetak nama aslinya!
            //     dd('Nama asli yang dibaca Laravel adalah: ' . $view);
            // }
            $unreadCount = 0;
            if (Auth::check()) {
                $unreadCount = Notification::where('employee_id', Auth::user()->employee_id)
                    ->where('is_read', false)
                    ->where(function ($q) {
                        $q->where('type', '!=', 'pangkat')
                        ->orWhere(function ($q2) {
                            $q2->where('type', 'pangkat')
                                ->whereNotNull('status');
                        });
                    })
                    ->count();
            }
            $view->with('globalUnreadCount', $unreadCount);
        });
    }
}
