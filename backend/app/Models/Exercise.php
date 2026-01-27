<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'wger_id', 'muscle_group'];

    // Relación con rutinas (Muchos a Muchos)
    public function routines()
    {
        return $this->belongsToMany(Routine::class)->withPivot('sets', 'reps', 'rest_time');
    }
}