<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'code', 'hex_code', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Màu sắc có nhiều biến thể sản phẩm
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}