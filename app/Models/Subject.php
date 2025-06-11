<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'school_id', 'classroom_id'];
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}