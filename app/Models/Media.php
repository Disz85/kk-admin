<?php

namespace App\Models;

use App\Utility\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'type',
        'title',
        'width',
        'height',
        'x',
        'y',
    ];

    public function getCreatedAtAttribute(?string $value): ?string
    {
        return Helpers::getFormattedDate($value);
    }

    public function getUpdatedAtAttribute(?string $value): ?string
    {
        return Helpers::getFormattedDate($value);
    }
}
