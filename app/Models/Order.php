<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'discount_id', 'payment_id', 'status',
        'payment_status', 'total_price', 'total_after_discount',
        'tracking_code', 'recipient_name', 'recipient_phone',
        'shipping_address', 'note'
    ];

    protected $casts = [
        'status' => 'integer',
        'payment_status' => 'integer',
        'total_price' => 'decimal:2',
        'total_after_discount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Đơn hàng sử dụng một mã giảm giá
    public function discount()
    {
        return $this->belongsTo(Discount::class)->withDefault();
    }

    // Quan hệ: Đơn hàng sử dụng một phương thức thanh toán
    public function payment()
    {
        return $this->belongsTo(Payment::class)->withDefault();
    }

    // Quan hệ: Đơn hàng có nhiều chi tiết đơn hàng
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}