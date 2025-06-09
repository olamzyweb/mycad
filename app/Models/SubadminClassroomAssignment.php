<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubadminClassroomAssignment extends Pivot
{
    protected $table = 'subadmin_classroom_assignments'; // explicitly declare the table name

    protected $fillable = [
        'subadmin_id',
        'classroom_id',
        'school_id',
    ];

    public function subadmin()
    {
        return $this->belongsTo(User::class, 'subadmin_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
