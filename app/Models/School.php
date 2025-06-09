<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{

      protected $fillable = [
    'name',
   
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}
