<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'grade_code'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
