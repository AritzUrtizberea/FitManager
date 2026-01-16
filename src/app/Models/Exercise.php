<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    public function routines() {
    return $this->belongsToMany(Routine::class)->withPivot('sets', 'reps', 'rest_time')->withTimestamps();
}
}
