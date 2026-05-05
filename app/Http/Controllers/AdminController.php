<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\Report;
use App\Models\User;
use App\Services\KgbService;
use App\Services\NotificationService;
use App\Services\PensiunService;
use App\Services\PromotionService;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function index(NotificationService $notificationService, PromotionService $promotionService, KgbService $kgbService, PensiunService $pensiunService)
    {
        $users = User::has('employee')->with('employee')->get();
        $notificationService->checkAndGenerateNotifications($users);
        $promotionService->process();
        $kgbService->process();
        $pensiunService->process();
        $user = Auth::user();
        // dd($user->role);

        // ===================== ADMIN SIMPEG =====================
        if ($user->role === 'admin_simpeg') {

            return view('dashboard', [
                'totalPegawai' => Employee::count(),
                'asnCount' => Employee::where('type', 'ASN')->count(),
                'notifCount' => Notification::count(),
                'recentNotifications' => Notification::with('employee')
                    ->latest()
                    ->take(5)
                    ->get(),
            ]);
        }

        // ===================== ADMIN SAMPAH =====================
        if ($user->role === 'admin_sampah') {

            return view('dashboard', [
                'pendingReportsCount' => Report::where('status', 'pending')->count(),
                'allReport' => Report::count(),
                'nonAsnCount' => Employee::where('type', 'Non ASN')->count(),
                'resolvedCount' => Report::where('status', 'selesai')->count(),
                'recentReports' => Report::where('status', 'pending')
                    ->latest()
                    ->take(5)
                    ->get(),
            ]);
        }

        // fallback (optional)
        return view('dashboard');
    }

    public function notificationSend(Notification $notification) {
        if ($notification->type !== 'pangkat' || !is_null($notification->status)) {
            abort(403, 'Tidak valid');
        }
        $notification->update([
            'status' => 'pending'
        ]);
        return back()->with('success', 'Notifikasi berhasil dikirim.');
    }
}