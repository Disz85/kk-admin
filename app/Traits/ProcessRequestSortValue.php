<?php

namespace App\Traits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * @mixin FormRequest
 */
trait ProcessRequestSortValue
{
    public function getSortBy(): string
    {
        $sortBy = $this->get('sort', '');
        return ltrim($sortBy, '-');
    }

    public function getSortDirection(): string
    {
        $sortBy = $this->get('sort', '');
        return Str::startsWith($sortBy, '-') ? 'desc' : 'asc';
    }
}
