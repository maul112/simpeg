<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'tracking_id', // Tambahkan baris ini
        'nama_pelapor',
        'kontak',
        'deskripsi',
        'tipe_sampah',
        'lokasi_manual',
        'foto_bukti',
        'latitude',
        'longitude',
        'status',
    ];
}