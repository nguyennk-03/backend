<?php

namespace App\Enums;

enum NotificationStatusEnum: int
{
    case Unread = 0;
    case Read = 1;

    public function label(): string
    {
        return match ($this) {
            self::Unread => 'Chưa đọc',
            self::Read => 'Đã đọc',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Unread => 'badge-warning',
            self::Read => 'badge-success',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::Unread => 'bi bi-envelope',
            self::Read => 'bi bi-envelope-open',
        };
    }

    public static function options(): array
    {
        return [
            self::Unread->value => self::Unread->label(),
            self::Read->value => self::Read->label(),
        ];
    }
}
