<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'size',
        'color',
        'discount_percent',
        'discounted_price',
        'stock_quantity',
        'sold',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
        'discounted_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'sold' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Biến thể thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ: Biến thể có nhiều hình ảnh
    public function images()
    {
        return $this->hasMany(Image::class, 'variant_id');
    }

    // Quan hệ: Hình ảnh chính của biến thể
    public function mainImage()
    {
        return $this->hasOne(Image::class, 'variant_id')->where('is_main', true);
    }

    // Quan hệ: Biến thể trong giỏ hàng
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Quan hệ: Biến thể trong chi tiết đơn hàng
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::saving(function ($variant) {
            if ($variant->isDirty('discount_percent')) {
                // Fetch the price from the related product
                $price = $variant->product->price;
                $variant->discounted_price = $price * (1 - $variant->discount_percent / 100);
            }
        });
    }
}
