<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultSubject extends Model
{
    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
