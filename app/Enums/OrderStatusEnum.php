<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 0;
    case PROCESSING = 1;
    case SHIPPED = 2;
    case COMPLETED = 3;
    case CANCELED = 4;
    case RETURNED = 5;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::PROCESSING => 'Đang xử lý',
            self::COMPLETED => 'Đã thanh toán',
            self::SHIPPED => 'Đã giao',
            self::CANCELED => 'Đã hủy',
            self::RETURNED => 'Đã trả hàng',
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
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::PROCESSING->value => self::PROCESSING->label(),
            self::SHIPPED->value => self::SHIPPED->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            self::CANCELED->value => self::CANCELED->label(),
            self::RETURNED->value => self::RETURNED->label(),
        ];
    }
}
