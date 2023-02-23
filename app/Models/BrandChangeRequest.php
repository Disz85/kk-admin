<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * BrandChangeRequest model
 *
 * @OA\Schema(
 *     @OA\Xml(name="BrandChangeRequest"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="data", type="string"),
 *     @OA\Property(property="brand_id", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property array $data
 * @property Media|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Brand|null $brand_id
 */
class BrandChangeRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'data',
        'brand_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @return BelongsTo<Brand, BrandChangeRequest>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
