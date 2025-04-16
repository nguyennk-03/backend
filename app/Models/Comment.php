<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'product_id', 'message', 'is_staff', 'is_hidden'];

    protected $casts = [
        'is_staff' => 'boolean',
        'is_hidden' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Bình luận thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    // Quan hệ: Bình luận thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }
}