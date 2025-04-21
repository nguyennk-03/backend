<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'style_id',
        'price',
        'discount_percent',
        'discounted_price',
        'stock_quantity',
        'sold'
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

    // Quan hệ: Biến thể thuộc về một kích thước
    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault();
    }

    // Quan hệ: Biến thể thuộc về một màu sắc
    public function color()
    {
        return $this->belongsTo(Color::class)->withDefault();
    }


    // Quan hệ: Biến thể có nhiều hình ảnh
    public function images()
    {
        return $this->hasMany(Image::class, 'variant_id');
    }

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
            if ($variant->isDirty('price') || $variant->isDirty('discount_percent')) {
                $variant->discounted_price = $variant->price * (1 - $variant->discount_percent / 100);
            }
        });
    }
}
