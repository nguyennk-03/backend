<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'role',
        'google_id',
    ];

    protected $hidden = ['password', 'remember_token',];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public static function getRandomImage()
    {
        $directory = public_path('images/users');

        if (!File::exists($directory)) {
            return 'images/users/user-1.jpg';
        }

        $files = array_diff(scandir($directory), array('..', '.'));

        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        if (!empty($images)) {
            return 'images/users/' . $images[array_rand($images)];
        }

        return 'images/users/user-1.jpg';
    }
}
