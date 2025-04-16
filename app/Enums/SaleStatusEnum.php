<?php

namespace App\Enums;

enum SaleStatusEnum: int
{
    case NOT_SALE = 0;
    case IS_SALE = 1;

    public function label(): string
    {
        return match ($this) {
            self::NOT_SALE => 'Không giảm giá',
            self::IS_SALE => 'Có giảm giá',
        };
    }

    public static function options(): array
    {
        return [
            self::NOT_SALE->value => self::NOT_SALE->label(),
            self::IS_SALE->value => self::IS_SALE->label(),
        ];
    }
}
