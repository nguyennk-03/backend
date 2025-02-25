<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price', 'category_id', 'brand_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function discounts() {
        return $this->belongsToMany(Discount::class, 'product_discounts');
    }
    public function images()
    {
        return $this->hasManyThrough(Image::class, ProductVariant::class, 'product_id', 'product_variant_id');
    }
}
