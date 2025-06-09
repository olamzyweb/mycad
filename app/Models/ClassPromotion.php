<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassPromotion extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClassroom()
    {
        return $this->belongsTo(Classroom::class, 'from_classroom_id');
    }
}
