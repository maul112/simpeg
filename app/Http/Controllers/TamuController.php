<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Services\KgbService;
use App\Services\NotificationService;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str; // Import ini untuk membuat ID acak

class TamuController extends Controller
{
    public function index() {
        $name = Cookie::get('guest_name');
        $address = Cookie::get('guest_address');
        return view('tamu.index', compact('name', 'address'));
    }

    public function masukForm(NotificationService $promotionService)
    {
        return view('pages.auth.register');
    }

    public function create(NotificationService $notificationService, PromotionService $promotionService, KgbService $kgbService)
    {
        $users = User::has('employee')->with('employee')->get();
        $notificationService->checkAndGenerateNotifications($users);
        $promotionService->process();
        $kgbService->process();
        
        return view('tamu.pengaduan');
    }

    public function store(Request $request)
    {
        // 1. Pengaturan Validasi
        $rules = [
            'nama_pelapor'  => 'required|string|max:255',
            'kontak'        => 'nullable|string|max:50',
            'deskripsi'     => 'nullable|string',
            'tipe_sampah'   => 'required|in:organik,non_organik',
            'lokasi_manual' => 'required|string',
            'foto_bukti'    => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
        ];

        $messages = [
            'required'           => 'Kolom :attribute wajib diisi.',
            'in'                 => 'Pilihan pada kolom :attribute tidak valid.',
            'image'              => 'Format :attribute harus berupa gambar.',
            'mimes'              => 'Format :attribute harus berupa gambar.',
            'latitude.required'  => 'Titik kordinat peta wajib diisi.',
            'longitude.required' => 'Titik kordinat peta wajib diisi.',
        ];

        $attributes = [
            'nama_pelapor'  => 'Nama Pelapor',
            'kontak'        => 'Kontak',
            'deskripsi'     => 'Deskripsi',
            'tipe_sampah'   => 'Tipe Sampah',
            'lokasi_manual' => 'Lokasi Manual',
            'foto_bukti'    => 'Foto Bukti',
            'latitude'      => 'Titik Kordinat Peta',
            'longitude'     => 'Titik Kordinat Peta',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        // 2. Generate Tracking ID (Contoh: PDAD-F4G2H1)
        $tracking_id = 'PDAD-' . strtoupper(Str::random(6));
        $validated['tracking_id'] = $tracking_id;

        // 3. Proses Upload Foto
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('pengaduan', 'public');
            $validated['foto_bukti'] = $path;
        }

        // 4. Set Status Awal
        $validated['status'] = 'pending';

        // 5. Simpan ke Database
        Report::create($validated);

        // 6. Redirect dengan pesan sukses dan ID Tracking
        return redirect()->route('home')->with([
            'success' => 'Laporan Anda telah berhasil terkirim ke sistem DLHCare.',
            'success_tracking' => $tracking_id
        ]);
    }

    public function alurLapor() {
        return view('tamu.alur-lapor');
    }
    
    // Fungsi baru untuk halaman Cek Status
 
    public function cekStatus(Request $request)
    {
        // Mengambil semua laporan terbaru untuk feed publik, sekaligus menghitung jumlah komentar.
        $allReports = Report::withCount('comments')->latest()->get();

        // Jika user mencari ID tertentu, filter datanya dan tetap hitung komentar.
        if ($request->filled('tracking_id')) {
            $allReports = Report::withCount('comments')
                                ->where('tracking_id', 'LIKE', '%' . $request->tracking_id . '%')
                                ->latest()
                                ->get();
        }

        return view('tamu.cek-status', compact('allReports'));
    }
}