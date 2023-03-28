<?php

namespace App\Enum;

enum TagTypeEnum: string
{
    case BRAND_CATEGORY = 'brand_category';
    case SALES_OUTLET = 'sales_outlet';
    case NATIONALITY = 'nationality';
    case COMPANY = 'company';

    /**
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
