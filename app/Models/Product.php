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

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function discounts() {
        return $this->belongsToMany(Discount::class, 'product_discounts');
    }
}
