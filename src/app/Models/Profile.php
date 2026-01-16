<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'age', 'gender', 'height', 'weight', 'physical_activity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
