<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $table = 'sizes';
    
    protected $fillable = ['size'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
                    ->withPivot('color_id', 'quantity', 'price', 'image')
                    ->withTimestamps();
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_variants')
                    ->withPivot('product_id', 'quantity', 'price', 'image')
                    ->withTimestamps();
    }
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
