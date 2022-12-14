<?php

namespace App\Enum;

enum SkinTypeEnum: string
{
    case DRY = 'száraz';
    case COMBINED = 'kombinált';
    case NORMAL = 'normál';
    case GREASY = 'zsíros';
}
