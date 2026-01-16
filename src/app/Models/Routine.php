<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    public function exercises() {
    return $this->belongsToMany(Exercise::class)->withPivot('sets', 'reps', 'rest_time')->withTimestamps();
}
}
