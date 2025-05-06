<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'product_id', 'message', 'is_staff', 'parent_id', 'status'];

    protected $casts = [
        'is_staff' => 'boolean',
        'status' => 'boolean',
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
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Quan hệ với bảng `comments` (danh sách câu trả lời của bình luận này)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Hàm để kiểm tra xem bình luận là từ nhân viên hay không
    public function isFromStaff()
    {
        return $this->is_staff;
    }
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Hiển thị' : 'Ẩn';
    }
}