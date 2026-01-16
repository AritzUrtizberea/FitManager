<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    // Relación con el Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Ejercicios (N:M)
    public function exercises()
    {
        // ¡Importante! withPivot para poder leer las series y reps después
        return $this->belongsToMany(Exercise::class)
                    ->withPivot('sets', 'reps') 
                    ->withTimestamps();
    }
}