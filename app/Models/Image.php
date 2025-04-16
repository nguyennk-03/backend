<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['variant_id', 'path', 'is_main'];

    protected $casts = [
        'is_main' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Hình ảnh thuộc về một biến thể sản phẩm
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}