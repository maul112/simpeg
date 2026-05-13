<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;

class TpsController extends Controller
{
    /**
     * Menampilkan daftar TPS di halaman Admin.
     */
    public function index(Request $request)
    {
        $query = Tps::query();

        // Fitur Pencarian
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_tps', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
            });
        }

        // Fitur Filter Kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Ambil data (10 data per halaman)
        $tps_data = $query->latest()->paginate(10);

        return view('admin.tps.index', compact('tps_data'));
    }

    /**
     * Menyimpan data TPS baru ke Database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tps'  => 'required|string|max:255',
            'kecamatan' => 'required|string|in:socah,bangkalan,kamal',
            'alamat'    => 'nullable|string|max:500',
            'jadwal'    => 'nullable|string|max:100',
            'lat'       => 'required|numeric',
            'lng'       => 'required|numeric',
        ]);

        try {
            $validated['lat'] = round($request->lat, 8);
            $validated['lng'] = round($request->lng, 8);

            Tps::create($validated);
            
            return redirect()->back()->with('success', 'Titik TPS berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }

    /**
     * Menampilkan halaman edit TPS.
     */
    public function edit(Tps $tps)
    {
        // Mengarahkan ke view edit (pastikan file ini ada nanti)
        return view('admin.tps.edit', compact('tps'));
    }

    /**
     * Memperbarui data TPS di database.
     */
    public function update(Request $request, Tps $tps)
    {
        $validated = $request->validate([
            'nama_tps'  => 'required|string|max:255',
            'kecamatan' => 'required|string|in:socah,bangkalan,kamal',
            'alamat'    => 'nullable|string|max:500',
            'jadwal'    => 'nullable|string|max:100',
            'lat'       => 'required|numeric',
            'lng'       => 'required|numeric',
        ]);

        try {
            $validated['lat'] = round($request->lat, 8);
            $validated['lng'] = round($request->lng, 8);

            $tps->update($validated);
            
            // Setelah update, balik ke halaman index
            return redirect()->route('admin.tps.index')->with('success', 'Data TPS berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Menghapus data TPS.
     */
    public function destroy(Tps $tps)
    {
        try {
            $tps->delete();
            return redirect()->back()->with('success', 'Data TPS berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * Fungsi untuk Halaman User/Warga (Public).
     */
    public function halamanTamu(Request $request)
    {
        $query = Tps::query();

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        $all_tps = $query->get();

        return view('layout.tamu.halamantps', compact('all_tps'));
    }
}