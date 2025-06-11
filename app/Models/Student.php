<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'user_id',
    'school_id',
    'first_name',
    'last_name',
    'email',
    'admission_number',
    'date_of_birth',
    'gender',
];
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}