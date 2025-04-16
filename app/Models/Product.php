<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'sale', 'hot', 'status',
        'stock_quantity', 'sold', 'category_id', 'brand_id'
    ];

    protected $casts = [
        'sale' => 'boolean',
        'hot' => 'integer',
        'status' => 'boolean',
        'stock_quantity' => 'integer',
        'sold' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Sản phẩm thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    // Quan hệ: Sản phẩm thuộc về một thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }

    // Quan hệ: Sản phẩm có nhiều biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Quan hệ: Sản phẩm có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Quan hệ: Sản phẩm có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Quan hệ: Sản phẩm có nhiều mã giảm giá
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'product_discounts');
    }

    // Quan hệ: Sản phẩm trong danh sách yêu thích
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}