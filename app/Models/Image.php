<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['product_variant_id', 'image_url'];
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,       // Bảng đích (products)
            ProductVariant::class,// Bảng trung gian (product_variants)
            'id',                 // Khóa chính của `product_variants`
            'id',                 // Khóa chính của `products`
            'product_variant_id',  // Khóa ngoại trong `images`
            'product_id'          // Khóa ngoại trong `product_variants`
        );
    }
    public function getImageUrlAttribute($value)
    {
        return asset('images/' . $value); // Trả về đường dẫn đầy đủ
    }
}

