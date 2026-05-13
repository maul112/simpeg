<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama tabelnya 'tps')
    protected $table = 'tps';

    // Ini daftar kolom yang BOLEH diisi (Mass Assignment)
    // Sesuai dengan gambar yang kamu kirim
    protected $fillable = [
        'nama_tps',
        'kecamatan',
        'alamat',
        'jadwal',
        'lat',
        'lng',
    ];
}