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

    /**
     * Returns an ISO8601 formatted time.
     *
     * @param $second
     * @return string
     */
    public static function formatSecToISO8601($second): string
    {
        $h = intval($second / 3600);
        $m = intval(($second - $h * 3600) / 60);
        $s = $second - ($h * 3600 + $m * 60);
        $ret = 'PT';
        if ($h) {
            $ret .= $h.'H';
        }
        if ($m) {
            $ret .= $m.'M';
        }
        if ((! $h && ! $m) || $s) {
            $ret .= $s.'S';
        }

        return $ret;
    }

    /**
     * Returns a sanitize string.
     *
     * @param $string
     * @return string
     */
    public static function sanitizeString($string): string
    {
        $string = pathinfo($string, PATHINFO_FILENAME);
        $string = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $string);
        $string = mb_ereg_replace("([\.]{2,})", '', $string);
        $string = str_replace("_", " ", $string);

        return $string;
    }
}
