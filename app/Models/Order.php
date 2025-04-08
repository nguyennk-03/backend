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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatusEnum::PAID;
    }

    public function scopeStatus($query, OrderStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PAID);
    }

    public function scopeUserOrders($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function updatePaymentStatus($status)
    {
        if ($this->isPaid() && $status !== PaymentStatusEnum::PAID) {
            return response()->json(['message' => 'Không thể thay đổi trạng thái của thanh toán đã hoàn thành.'], 400);
        }

        $this->update(['payment_status' => $status]);

        return $this;
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }

    protected function paymentStatus(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }
    public function getStatusTextAttribute(): string
    {
        return $this->status?->label() ?? 'Không xác định';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status?->badgeClass() ?? 'bg-secondary';
    }


}