<?php

namespace App\Enums;

enum ProductStatusEnum: int
{
    case NORMAL = 0;
    case NEW = 1;
    case HOT = 2;
    case BEST_SELLER = 3;

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => 'Thường',
            self::NEW => 'Mới',
            self::HOT => 'Nổi bật',
            self::BEST_SELLER => 'Bán chạy',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::NORMAL => 'badge-secondary',
            self::NEW => 'badge-primary',
            self::HOT => 'badge-success',
            self::BEST_SELLER => 'badge-danger',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::NORMAL => 'bi bi-box',
            self::NEW => 'bi bi-newspaper',
            self::HOT => 'bi bi-fire',
            self::BEST_SELLER => 'bi bi-star',
        };
    }

    public static function options(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::NEW->value => self::NEW->label(),
            self::HOT->value => self::HOT->label(),
            self::BEST_SELLER->value => self::BEST_SELLER->label(),
        ];
    }
}
