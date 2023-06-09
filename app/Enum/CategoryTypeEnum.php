<?php

namespace App\Enum;

enum CategoryTypeEnum: string
{
    case Article = 'article';
    case Product = 'product';
    case SkinType = 'skintype';
    case SkinConcern = 'skinconcern';
    case HairProblem = 'hairproblem';
    case Ingredient = 'ingredient';

    /**
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
