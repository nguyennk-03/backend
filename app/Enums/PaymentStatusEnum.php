<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 0;
    case PAID = 1;
    case FAILED = 2;
    case REFUNDED = 3;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ thanh toán',
            self::PAID => 'Đã thanh toán',
            self::FAILED => 'Thất bại',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PAID => 'bg-success',
            self::PENDING => 'bg-warning',
            self::FAILED => 'bg-danger',
            self::REFUNDED => 'bg-secondary',
        };
    }

    public function iconClass(): string
    {
        return match($this) {
            self::PENDING => 'bi bi-clock',
            self::PAID => 'bi bi-check-circle',
            self::FAILED => 'bi bi-x-circle',
            self::REFUNDED => 'bi bi-arrow-counterclockwise',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::PAID->value => self::PAID->label(),
            self::FAILED->value => self::FAILED->label(),
            self::REFUNDED->value => self::REFUNDED->label(),
        ];
    }
}
