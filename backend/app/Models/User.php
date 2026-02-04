<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // <--- IMPORTE NECESARIO

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'profile_photo_path', // <--- AÑADE ESTO para poder guardar la ruta
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONES ---
    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function diets() {
        return $this->hasMany(Diet::class);
    }

    public function routines() {
        return $this->hasMany(Routine::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
}

    // --- ACCESSOR (LA MAGIA) ---
    // Esto crea el atributo virtual 'profile_photo_url' que usas en el HTML
    public function getProfilePhotoUrlAttribute()
    {
        // Si hay una ruta guardada en la DB, devuelve la URL pública
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        // Si no hay foto, devuelve null (así tu HTML mostrará las iniciales)
        return null;
    }
}