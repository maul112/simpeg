<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Services\KgbService;
use App\Services\NotificationService;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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

    public function masuk(Request $request)
    {
        // $name = $request->input('name');
        // $address = $request->input('address');
        // Cookie::queue('guest_name', $name, 1440);
        // Cookie::queue('guest_address', $address, 1440);
        // return redirect()->route('tamu.index');
    }

    public function create(NotificationService $notificationService, PromotionService $promotionService, KgbService $kgbService)
    {
        $users = User::has('employee')->with('employee')->get();
        $notificationService->checkAndGenerateNotifications($users);
        $promotionService->process();
        $kgbService->process();
        // if (count($result) > 0) {
        //     dd($result);
        // }
        return view('tamu.pengaduan');
    }

    public function store(Request $request)
    {
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
            'required'        => 'Kolom :attribute wajib diisi.',
            'unique'          => ':attribute ini sudah dipakai orang lain.',
            'digits'          => ':attribute harus berupa angka dan tepat :digits digit.',
            'in'              => 'Pilihan pada kolom :attribute tidak valid.',
            'image'           => 'Format :attribute harus berupa gambar.',
            'mimes'           => 'Format :attribute harus berupa gambar.',
            'latitude.required' => 'Titik kordinat peta wajib diisi.',
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
        // 1. Validasi Input
        // $validated = $request->validate([
        //     'nama_pelapor'  => 'required|string|max:255',
        //     'kontak'        => 'nullable|string|max:50',
        //     'deskripsi'     => 'nullable|string',
        //     'tipe_sampah'   => 'required|in:organik,non_organik',
        //     'lokasi_manual' => 'required|string',
        //     'foto_bukti'    => 'required|image|mimes:jpeg,png,jpg|max:5120',
        //     'latitude'      => 'required|numeric',
        //     'longitude'     => 'required|numeric',
        // ], [
        //     'latitude.required' => 'Titik kordinat peta wajib diisi.',
        //     'longitude.required' => 'Titik kordinat peta wajib diisi.',
        // ]);

        // 2. Proses Upload Foto (Simpan ke folder storage/app/public/pengaduan)
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('pengaduan', 'public');
            $validated['foto_bukti'] = $path;
        }

        // 3. Simpan ke Database
        Report::create($validated);

        // 4. Kembali dengan Pesan Sukses
        return redirect()->route('home')->with('success', 'Laporan Anda telah berhasil terkirim ke sistem DLHCare.');
    }

    public function alurLapor() {
        return view('tamu.alur-lapor');
    }
}
 