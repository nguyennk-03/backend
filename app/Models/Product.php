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
        return $this->hasMany(ProductVariant::class,'variant_id');
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
        return $this->belongsToMany(Discount::class, 'discounts');
    }

    // Quan hệ: Sản phẩm trong danh sách yêu thích
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function getImageAttribute()
    {
        $mainImage = $this->variants()
            ->whereHas('images', fn($query) => $query->where('is_main', true))
            ->with(['images' => fn($query) => $query->where('is_main', true)])
            ->first();

        return $mainImage?->images->first()?->path ?? null;
    }

    // Accessor cho tổng tồn kho
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock_quantity');
    }
}