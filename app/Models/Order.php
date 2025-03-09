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
        'total_price', // tổng tiền 
        'amount', // tiền một phần
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'status' => OrderStatusEnum::class,
        'payment_status' => PaymentStatusEnum::class,
    ];

    // 🔹 Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Quan hệ với Discount
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    // 🔹 Quan hệ với Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔹 Quan hệ với Payment (Cập nhật)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id'); // ✅ Thay belongsTo bằng hasOne
    }

    // 🔹 Kiểm tra đơn hàng đã thanh toán hay chưa
    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatusEnum::PAID;
    }

    // 🔹 Scope lọc theo trạng thái đơn hàng
    public function scopeStatus($query, OrderStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    // 🔹 Scope lọc đơn hàng đã thanh toán
    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PAID);
    }

    // 🔹 Scope lấy đơn hàng của người dùng cụ thể
    public function scopeUserOrders($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // 🔹 Xử lý thanh toán MoMo
    public function processMomoPayment($momoService)
    {
        if ($this->isPaid()) {
            return response()->json(['message' => 'Đơn hàng đã được thanh toán.'], 400);
        }

        $qrCode = $momoService->generateQRCode($this->amount);

        // Cập nhật trạng thái thanh toán
        $this->update([
            'payment_status' => PaymentStatusEnum::PENDING,
        ]);

        return [
            'message' => 'Tạo thanh toán MoMo thành công.',
            'qr_code' => $qrCode,
            'order' => $this,
        ];
    }

    // 🔹 Xử lý cập nhật trạng thái thanh toán
    public function updatePaymentStatus($status)
    {
        if ($this->isPaid() && $status !== PaymentStatusEnum::PAID) {
            return response()->json(['message' => 'Không thể thay đổi trạng thái của thanh toán đã hoàn thành.'], 400);
        }

        $this->update(['payment_status' => $status]);

        return $this;
    }

    // 🔹 Mutator: Xử lý status
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }

    // 🔹 Mutator: Xử lý payment_status
    protected function paymentStatus(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }
}
