<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    public function diets()
{
    return $this->belongsToMany(Diet::class)->withPivot('amount')->withTimestamps();
}
}
