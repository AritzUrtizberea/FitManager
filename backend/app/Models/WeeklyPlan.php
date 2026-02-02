<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day',
        'meals_summary',
        'calories',
        'status'
    ];
    
    // Relación inversa: Un plan pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Probablemente lo tengas así:
public function products() 
{
    return $this->belongsToMany(Product::class);
}
}