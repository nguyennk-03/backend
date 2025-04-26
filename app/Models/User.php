<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens, HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'is_locked', 'status',
        'phone', 'avatar', 'address', 'role'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_locked' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Người dùng có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Quan hệ: Người dùng có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Quan hệ: Người dùng có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Quan hệ: Người dùng có nhiều thông báo
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Quan hệ: Người dùng có nhiều danh sách yêu thích
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}