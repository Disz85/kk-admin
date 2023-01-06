<?php

namespace App\Enum;

enum CategoryTypeEnum: string
{
    case Article = 'article';
    case Product = 'product';
    case SkinType = 'skintype';
    case SkinConcern = 'skinconcern';
    case Ingredient = 'ingredient';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
