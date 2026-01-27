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
    'streak', // <--- AÑADE ESTO
];

protected $attributes = [
        'streak' => 0,
    ];
// Relación inversa (opcional pero recomendada)
public function user()
{
    return $this->belongsTo(User::class);
}
}
