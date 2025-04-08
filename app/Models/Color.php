<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors';

    // Nên thêm đầy đủ các cột dùng trong seeder + logic
    protected $fillable = [
        'name',
        'code',
        'hex_code',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Mối quan hệ: Color thuộc nhiều Product thông qua product_variants
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
                    ->withPivot('size_id', 'quantity', 'price', 'image')
                    ->withTimestamps();
    }

    /**
     * Mối quan hệ: Color thuộc nhiều Size thông qua product_variants
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_variants')
                    ->withPivot('product_id', 'quantity', 'price', 'image')
                    ->withTimestamps();
    }

    /**
     * Mối quan hệ: Color có nhiều ProductVariant
     */
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
