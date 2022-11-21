<?php

namespace App\Helpers\Import;

use Carbon\Carbon;

class TimestampToDateConverter
{
    public function convert(string $timestamp): string
    {
        return Carbon::createFromTimestamp($timestamp, 'Europe/Budapest')->format('Y-m-d');
    }
}
