<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'message',
        'status',
        'sk_file_path',
        'submitted_at',
        'is_read',
    ];

    protected $casts = [
        // Pastikan kolom ini di-cast sebagai datetime
        'submitted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
