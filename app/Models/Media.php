<?php

namespace App\Models;

use App\Utility\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 * @package App\Models
 *
 * @property int $id
 * @property string $path
 * @property string $type
 * @property string $title
 * @property int $width
 * @property int $height
 * @property int $x
 * @property int $y
 *
 * @property \DateTimeInterface $created_at
 * @property \DateTimeInterface $updated_at
 */
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
