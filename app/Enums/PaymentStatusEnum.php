<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 0;
    case PAID = 1;
    case FAILED = 2;
    case REFUNDED = 3;
    case CANCELED = 4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ thanh toán',
            self::PAID => 'Đã thanh toán',
            self::FAILED => 'Thanh toán thất bại',
            self::REFUNDED => 'Đã hoàn tiền',
            self::CANCELED => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::PAID => 'bg-success',
            self::FAILED => 'bg-danger',
            self::REFUNDED => 'bg-info',
            self::CANCELED => 'bg-dark',
            default => 'bg-light text-dark',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::PENDING => 'bi bi-clock',
            self::PAID => 'bi bi-check-circle',
            self::FAILED => 'bi bi-x-circle',
            self::REFUNDED => 'bi bi-arrow-counterclockwise',
            self::CANCELED => 'bi bi-x-circle',
            default => 'bi bi-question',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::PAID->value => self::PAID->label(),
            self::FAILED->value => self::FAILED->label(),
            self::REFUNDED->value => self::REFUNDED->label(),
            self::CANCELED->value => self::CANCELED->label(),
        ];
    }
}
