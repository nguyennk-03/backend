<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'discount_id',
        'payment_id',
        'status',
        'total_price',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status' => 'string',
        'payment_method' => 'string',
        'payment_status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public static function getStatusList()
    {
        return ['pending', 'completed', 'canceled'];
    }
    public static function getPaymentMethods()
    {
        return ['momo', 'vnpay', 'paypal', 'cod'];
    }
    public static function getPaymentStatus()
    {
        return ['pending', 'paid', 'failed', 'refunded'];
    }
}

