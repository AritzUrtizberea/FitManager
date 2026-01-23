<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'barcode',    // <--- Nuevo
        'image_url',
        'kcal',
        'proteins',   // <--- Nuevo
        'carbs',      // <--- Nuevo
        'fats',       // <--- Nuevo
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        // Con withPivot podemos acceder al campo 'amount'
        return $this->belongsToMany(Product::class)->withPivot('amount')->withTimestamps();
    }
}
