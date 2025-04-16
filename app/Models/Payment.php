<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Phương thức thanh toán có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}