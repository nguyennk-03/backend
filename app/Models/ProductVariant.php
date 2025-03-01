<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model {
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = ['product_id', 'size_id', 'color_id', 'stock'];

    public function size() {
        return $this->belongsTo(Size::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function images() {
        return $this->hasMany(Image::class,'variant_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }
    
    public function carts()
    {
        return $this->hasMany(Cart::class, 'variant_id');
    }
}
