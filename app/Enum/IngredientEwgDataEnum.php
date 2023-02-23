<?php

namespace App\Enum;

enum IngredientEwgDataEnum: string
{
    case EWG_DATA_NONE = 'None';
    case EWG_DATA_LIMITED = 'Limited';
    case EWG_DATA_FAIR = 'Fair';
    case EWG_DATA_GOOD = 'Good';
    case EWG_DATA_ROBUST = 'Robust';

    /**
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
