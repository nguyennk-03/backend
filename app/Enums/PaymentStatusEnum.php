<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';   
    case PAID = 'paid';        
    case FAILED = 'failed';    
    case REFUNDED = 'refunded'; 

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ thanh toán',
            self::PAID => 'Đã thanh toán',
            self::FAILED => 'Thanh toán thất bại',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-warning',
            self::PAID => 'bg-success',
            self::FAILED => 'bg-danger',
            self::REFUNDED => 'bg-info',
        };
    }
}
