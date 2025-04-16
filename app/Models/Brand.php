<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'logo', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Một thương hiệu có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Quan hệ: Một thương hiệu có nhiều bài viết
    public function news()
    {
        return $this->hasMany(News::class);
    }
}