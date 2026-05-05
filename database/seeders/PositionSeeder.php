<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // ========================
            // STRUKTURAL
            // ========================
            ['name' => 'Sekretaris', 'type' => 'struktural'],
            ['name' => 'Kabid Penaatan Lingkungan Hidup', 'type' => 'struktural'],
            ['name' => 'Kabid Pelayanan Tata Lingkungan', 'type' => 'struktural'],
            ['name' => 'Kabid P2Kl Dan Pl', 'type' => 'struktural'],
            ['name' => 'Kabid Pengelolaan Sampah Dan Limbah B3', 'type' => 'struktural'],
            ['name' => 'Ka. Subbag Keuangan', 'type' => 'struktural'],
            ['name' => 'Ka.Subbag Perencanaan Dan Evaluasi', 'type' => 'struktural'],
            ['name' => 'Kasubag Umum Dan Kepegawaian', 'type' => 'struktural'],
            ['name' => 'Kepala Upt Pengelolaan Sampah', 'type' => 'struktural'],
            ['name' => 'Kepala Dinas Lingkungan Hidup', 'type' => 'struktural'],

            // ========================
            // FUNGSIONAL
            // ========================
            ['name' => 'Perencana Ahli Pertama', 'type' => 'fungsional'],
            ['name' => 'Penyuluh Lingkungan Ahli Pertama', 'type' => 'fungsional'],
            ['name' => 'Pengendali Dampak Lingkungan Ahli Pertama', 'type' => 'fungsional'],
            ['name' => 'Pengendali Dampak Lingkungan Ahli Muda', 'type' => 'fungsional'],
            ['name' => 'Pengawas Lingkungan Hidup Ahli Pertama', 'type' => 'fungsional'],
            ['name' => 'Penyuluh Lingkungan Hidup Ahli Pertama', 'type' => 'fungsional'],
            ['name' => 'Penelaah Teknis Kebijakan', 'type' => 'fungsional'],

            // ========================
            // UMUM (NON FUNGSIONAL)
            // ========================
            ['name' => 'Pengolah Data Dan Informasi', 'type' => 'non-fungsional'],
            ['name' => 'Pengawas Lapangan Petugas Kebersihan, Jalan, Dan Selokan', 'type' => 'non-fungsional'],
            ['name' => 'Analis Pajak Dan Retribusi Daerah', 'type' => 'non-fungsional'],
            ['name' => 'Penata Laporan Keuangan', 'type' => 'non-fungsional'],
            ['name' => 'Penata Kelola Sistem Dan Teknologi Informasi', 'type' => 'non-fungsional'],
            ['name' => 'Pemelihara Tumbuhan', 'type' => 'non-fungsional'],
            ['name' => 'Pranata Taman', 'type' => 'non-fungsional'],
            ['name' => 'Penata Layanan Operasional', 'type' => 'non-fungsional'],
            ['name' => 'Operator Layanan Operasional', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Perkantoran', 'type' => 'non-fungsional'],
            ['name' => 'Pengelola Umum Operasional', 'type' => 'non-fungsional'],
            ['name' => 'Analis Perencana Program Dan Kegiatan', 'type' => 'non-fungsional'],
            ['name' => 'Penyuluh Lingkungan Hidup', 'type' => 'non-fungsional'],
            ['name' => 'Analis Taman', 'type' => 'non-fungsional'],
            ['name' => 'Analis Lingkungan Hidup', 'type' => 'non-fungsional'],
            ['name' => 'Pengelola Data', 'type' => 'non-fungsional'],
            ['name' => 'Analis Data Dan Informasi', 'type' => 'non-fungsional'],
            ['name' => 'Pengawas Lapangan Petugas Pertamanan', 'type' => 'non-fungsional'],
            ['name' => 'Penyusun Kebutuhan Barang Inventaris', 'type' => 'non-fungsional'],
            ['name' => 'Pemelihara Jalan', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Hukum', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Keuangan', 'type' => 'non-fungsional'],
            ['name' => 'Pranata Pengambil Sampel', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministasi Umum', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Rapat', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Sarana Dan Prasarana', 'type' => 'non-fungsional'],
            ['name' => 'Petugas Keamanan', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Tempat Pembuangan Akhir', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Persuratan', 'type' => 'non-fungsional'],
            ['name' => 'Pengadministrasi Kepegawaian', 'type' => 'non-fungsional'],
            ['name' => 'Pramu Kebersihan', 'type' => 'non-fungsional'],
            ['name' => 'Pramu Taman', 'type' => 'non-fungsional'],
        ];
        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['position_name' => $position['name']],
                ['type' => $position['type']]
            );
        }
    }
}
