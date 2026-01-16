<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
    'user_id',
    'phone',
    'sex',
    'weight',
    'height',
    'activity',
];

// Relación inversa (opcional pero recomendada)
public function user()
{
    return $this->belongsTo(User::class);
}
}
