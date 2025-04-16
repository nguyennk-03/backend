<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    protected $fillable = ['product_id', 'discount_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Liên kết thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ: Liên kết thuộc về một mã giảm giá
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}