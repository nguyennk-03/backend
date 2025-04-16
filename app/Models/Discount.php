<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'code', 'discount_type', 'value', 'min_order_amount',
        'start_date', 'end_date', 'is_active', 'usage_limit', 'used_count'
    ];

    protected $casts = [
        'discount_type' => 'string',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Mã giảm giá có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Quan hệ: Mã giảm giá áp dụng cho nhiều sản phẩm
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_discounts');
    }
}