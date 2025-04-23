<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['product_id', 'path', 'is_main'];

    protected $casts = [
        'is_main' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Hình ảnh thuộc về một sản phẩm
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}