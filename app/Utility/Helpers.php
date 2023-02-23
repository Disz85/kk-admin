<?php

namespace App\Utility;

use Illuminate\Support\Carbon;

class Helpers
{
    /**
     * Returns a formatted date.
     *
     * @param string|null $date
     * @return string|null
     */
    public static function getFormattedDate(?string $date): ?string
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
    }
}
