<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'comment', 'status'];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Đánh giá thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Đánh giá thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Hiển thị' : 'Ẩn';
    }
}