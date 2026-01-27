<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Añadido
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)
                    ->withPivot('sets', 'reps', 'rest_time') // Añadido rest_time que está en tu migración
                    ->withTimestamps();
    }
}