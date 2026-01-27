<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    use HasFactory;

    // Estos son los campos que permitimos guardar
    protected $fillable = ['user_id', 'name', 'description'];

    // Relación con Productos (Ya la deberías tener, pero revísala)
    public function products()
    {
        // 'withPivot' es vital para que al pedir la dieta, venga la cantidad (amount)
        return $this->belongsToMany(Product::class)->withPivot('amount')->withTimestamps();
    }
    
    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}