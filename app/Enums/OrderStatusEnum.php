<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 0;
    case AWAITING_CONFIRMATION = 1;
    case PROCESSING = 2;
    case PACKING = 3;
    case SHIPPED = 4;
    case DELIVERED = 5;
    case CANCELED = 6;
    case RETURN_REQUESTED = 7;
    case RETURN_PROCESSING = 8;
    case RETURNED = 9;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xử lý',
            self::AWAITING_CONFIRMATION => 'Chờ xác nhận',
            self::PROCESSING => 'Đang xử lý',
            self::PACKING => 'Đang đóng gói',
            self::SHIPPED => 'Đã gửi đi',
            self::DELIVERED => 'Giao thành công',
            self::CANCELED => 'Đã hủy',
            self::RETURN_REQUESTED => 'Yêu cầu trả hàng',
            self::RETURN_PROCESSING => 'Đang xử lý trả hàng',
            self::RETURNED => 'Đã trả hàng',
            default => 'Không xác định',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::AWAITING_CONFIRMATION => 'bg-info text-dark',
            self::PROCESSING => 'bg-secondary',
            self::PACKING => 'bg-primary',
            self::SHIPPED => 'bg-primary',
            self::DELIVERED => 'bg-success',
            self::CANCELED => 'bg-danger',
            self::RETURN_REQUESTED => 'bg-warning',
            self::RETURN_PROCESSING => 'bg-info',
            self::RETURNED => 'bg-dark',
            default => 'bg-light text-dark',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::PENDING => 'bi bi-hourglass-split',
            self::AWAITING_CONFIRMATION => 'bi bi-question-circle',
            self::PROCESSING => 'bi bi-gear-wide-connected',
            self::PACKING => 'bi bi-box-seam',
            self::SHIPPED => 'bi bi-truck',
            self::DELIVERED => 'bi bi-check-circle',
            self::CANCELED => 'bi bi-x-circle',
            self::RETURN_REQUESTED => 'bi bi-arrow-left-circle',
            self::RETURN_PROCESSING => 'bi bi-arrow-repeat',
            self::RETURNED => 'bi bi-arrow-counterclockwise',
            default => 'bi bi-question',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::AWAITING_CONFIRMATION->value => self::AWAITING_CONFIRMATION->label(),
            self::PROCESSING->value => self::PROCESSING->label(),
            self::PACKING->value => self::PACKING->label(),
            self::SHIPPED->value => self::SHIPPED->label(),
            self::DELIVERED->value => self::DELIVERED->label(),
            self::CANCELED->value => self::CANCELED->label(),
            self::RETURN_REQUESTED->value => self::RETURN_REQUESTED->label(),
            self::RETURN_PROCESSING->value => self::RETURN_PROCESSING->label(),
            self::RETURNED->value => self::RETURNED->label(),
        ];
    }
}
