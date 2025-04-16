<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'image',
        'category_id', 'brand_id', 'author', 'views'
    ];

    protected $casts = [
        'views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Bài viết thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ: Bài viết thuộc về một thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }
}