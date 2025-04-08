<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
    case RETURNED = 'returned'; 

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::PROCESSING => 'Đang xử lý',
            self::COMPLETED => 'Đã thanh toán',
            self::SHIPPED => 'Đã giao',
            self::CANCELED => 'Đã hủy',
            self::RETURNED => 'Đã trả hàng', 
            default => 'Không xác định',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::COMPLETED => 'bg-success',
            self::PENDING => 'bg-warning',
            self::SHIPPED => 'bg-primary',
            self::CANCELED => 'bg-danger',
            self::PROCESSING => 'bg-secondary',
            self::RETURNED => 'bg-info', 
            default => 'bg-dark',
        };
    }
     public function iconClass(): string
    {
        return match($this) {
            self::PENDING => 'bi bi-hourglass-split',
            self::PROCESSING => 'bi bi-gear-wide-connected',
            self::SHIPPED => 'bi bi-truck',
            self::COMPLETED => 'bi bi-check-circle',
            self::CANCELED => 'bi bi-x-circle',
            self::RETURNED => 'bi bi-arrow-counterclockwise',
            default => 'bi bi-question-circle',
        };
    }
}