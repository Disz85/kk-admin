<?php

namespace app\Enum;

enum SkinConcernEnum: string
{
    case NONE = 'nincs';
    case ACNE = 'pattanások (acne)';
    case REDNESS = 'pirosság (rozacea)';
    case UNEVEN_SKIN = 'egyenetlen bőrfelszín';
    case PIGMENT_SPOTS = 'pigmentfoltok';
    case WIDE_PORES = 'tág pórusok';
    case SKIN_AGING = 'bőröregedés';
    case DEHYDRATED_SKIN = 'vízhiány';
    case HYPERSENSITIVITY = 'túlérzékenység';
}
