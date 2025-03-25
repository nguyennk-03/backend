<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status' => OrderStatusEnum::class,
        'payment_status' => PaymentStatusEnum::class,
        'created_at' => 'datetime',
    ];

    /**
     * Quan hệ tới User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Quan hệ tới Discount
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    /**
     * Quan hệ tới OrderItem
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    /**
     * Quan hệ tới Payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    /**
     * Kiểm tra đơn hàng đã thanh toán chưa
     */
    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatusEnum::PAID;
    }

    /**
     * Scope lọc theo trạng thái đơn hàng
     */
    public function scopeStatus($query, OrderStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope lọc đơn hàng đã thanh toán
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PAID);
    }

    /**
     * Scope lọc đơn hàng theo user
     */
    public function scopeUserOrders($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePaymentStatus($status)
    {
        if ($this->isPaid() && $status !== PaymentStatusEnum::PAID) {
            return response()->json(['message' => 'Không thể thay đổi trạng thái của thanh toán đã hoàn thành.'], 400);
        }

        $this->update(['payment_status' => $status]);

        return $this;
    }

    /**
     * Mutator cho status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }

    /**
     * Mutator cho payment_status
     */
    protected function paymentStatus(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }
}