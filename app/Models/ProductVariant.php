<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model {
    use HasFactory;

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
        return $this->hasMany(Image::class,'product_variant_id');
    }
}
