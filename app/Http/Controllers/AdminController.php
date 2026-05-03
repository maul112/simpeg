<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function index()
    {
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
}