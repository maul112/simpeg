<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->input('tipe_sampah');

        $reports = Report::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama_pelapor', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('lokasi_manual', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tipe_sampah', $type);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengaduan.index', compact('reports', 'search', 'status', 'type'));
    }

    public function show(Report $pengaduan)
    {
        // $pengaduan otomatis ditarik dari database berkat Route Model Binding
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Report $pengaduan)
    {
        $request->validate(['status' => 'required|in:pending,proses,selesai']);        
        $pengaduan->update(['status' => $request->status]);
        
        return back()->with('success', 'Status laporan berhasil diperbarui menjadi ' . strtoupper($request->status));
    }

    /**
     * Menghapus data laporan pengaduan.
     */
    public function destroy(Report $pengaduan)
    {
        try {
            // 1. Cek apakah ada file foto bukti, jika ada hapus dari storage
            if ($pengaduan->foto_bukti && Storage::disk('public')->exists($pengaduan->foto_bukti)) {
                Storage::disk('public')->delete($pengaduan->foto_bukti);
            }

            // 2. Hapus data dari database
            $pengaduan->delete();

            return back()->with('success', 'Laporan #' . str_pad($pengaduan->id, 4, '0', STR_PAD_LEFT) . ' berhasil dihapus secara permanen.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}