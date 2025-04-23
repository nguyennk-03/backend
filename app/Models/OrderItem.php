<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Chi tiết đơn hàng thuộc về một đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ: Chi tiết đơn hàng chứa một biến thể sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}