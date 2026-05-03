<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Field yang boleh diisi
    protected $fillable = ['report_id', 'user_id', 'body'];

    // Relasi balik ke User (Admin)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi balik ke Report
    public function report() {
        return $this->belongsTo(Report::class);
    }
}