<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'classroom_student')
                    ->withTimestamps();
    }

    public function users()
    {
         return $this->belongsToMany(User::class, 'subadmin_classroom_assignments', 'classroom_id', 'subadmin_id')
                ->withPivot('school_id')
                ->using(SubadminClassroomAssignment::class);
    }

    public function fees()
    {
        return $this->hasOne(Fee::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
