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
        'streak',          // <--- Correcto
        'last_streak_at',  // <--- ¡FALTABA ESTE! Sin esto no guarda la fecha
    ];

    protected $attributes = [
        'streak' => 0,
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}