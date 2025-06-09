<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeBreakdown extends Model
{
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}