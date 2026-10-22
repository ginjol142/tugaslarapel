<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'grade_level'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}