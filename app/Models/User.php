<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'avatar', 'role'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    protected $table = 'users';
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
        $directory = public_path('images/users'); // Đường dẫn đến thư mục chứa hình ảnh
        $files = array_diff(scandir($directory), array('..', '.')); // Lấy danh sách tệp tin

        // Lọc ra những tệp tin hình ảnh
        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        // Nếu có hình ảnh, chọn ngẫu nhiên một hình ảnh
        if (!empty($images)) {
            return 'images/users/' . $images[array_rand($images)];
        }

        // Trả về null hoặc một đường dẫn mặc định nếu không có hình ảnh
        return null;
    }
}
