<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'classroom_id', 'nis', 'name',
        'gender', 'birth_date', 'status'
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function getAgeAttribute()
    {
        return $this->birth_date
            ? $this->birth_date->age : null;
    }
}