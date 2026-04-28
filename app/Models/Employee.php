<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nip',
        'name',
        'birth_date',
        'gender',
        'status',
        'education_level',
        'education_detail',
        'tmt_start',
        'tmt_end',
        'tmt_kgb',
        'last_promoted_at',
        'type',
        'position_id',
        'rank_grade_id',
    ];

    public function initials(): string
    {
        // Jika nama kosong, kembalikan default
        if (empty($this->name)) {
            return '-';
        }

        $words = explode(' ', $this->name);
        
        // Jika nama terdiri dari 2 kata atau lebih (Misal: Budi Santoso -> BS)
        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }

        // Jika nama hanya 1 kata (Misal: Budi -> BU)
        return mb_strtoupper(mb_substr($this->name, 0, 2));
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function rankGrade()
    {
        return $this->belongsTo(RankGrade::class);
    }

    public function notifications() 
    {
        return $this->hasMany(Notification::class);
    }
}
