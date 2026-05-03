<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'tracking_id', 'nama_pelapor', 'kontak', 'deskripsi', 
        'tipe_sampah', 'lokasi_manual', 'foto_bukti', 
        'latitude', 'longitude', 'status',
    ];

    /**
     * Relasi ke tabel comments.
     * Pastikan kolom di tabel comments adalah 'report_id'.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'report_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}