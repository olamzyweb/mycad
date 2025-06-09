<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}