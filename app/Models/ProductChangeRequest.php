<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * Class ProductChangeRequest
 *
 * @OA\Schema(
 *     @OA\Xml(name="ProductChangeRequest"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="data", type="string"),
 *     @OA\Property(property="product_id", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property array $data
 * @property Media $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Product $product_id
 */
class ProductChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'product_id',
        'user_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @return BelongsTo<Product, ProductChangeRequest>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, ProductChangeRequest>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
