<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'cm', 'status'];

    protected $casts = [
        'cm' => 'decimal:1',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Kích thước có nhiều biến thể sản phẩm
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}