<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'variant_id', 'quantity', 'total_price'];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Giỏ hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Giỏ hàng chứa một biến thể sản phẩm
    public function variants()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}