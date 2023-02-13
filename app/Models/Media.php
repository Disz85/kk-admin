<?php

namespace App\Models;

use App\Utility\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * Class Media
 *
 * @OA\Schema(
 *     @OA\Xml(name="Media"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="path", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="width", type="int"),
 *     @OA\Property(property="height", type="int"),
 *     @OA\Property(property="x", type="int"),
 *     @OA\Property(property="y", type="int"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="published_at", type="string", format="date-time")
 * );
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $path
 * @property string $type
 * @property string|null $title
 * @property int|null $width
 * @property int|null $height
 * @property int|null $x
 * @property int|null $y
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Media extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'path',
        'type',
        'title',
        'width',
        'height',
        'x',
        'y',
    ];

    /**
     * @param string|null $value
     * @return string|null
     */
    public function getCreatedAtAttribute(?string $value): ?string
    {
        return Helpers::getFormattedDate($value);
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    public function getUpdatedAtAttribute(?string $value): ?string
    {
        return Helpers::getFormattedDate($value);
    }
}
