<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductOffer
 *
 * @OA\Schema(
 *     @OA\Xml(name="User"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="price", type="string"),
 *     @OA\Property(property="used", type="string"),
 *     @OA\Property(property="where_to_find", type="string"),
 *     @OA\Property(property="shipping_payment", type="string"),
 *     @OA\Property(property="is_sold", type="bool"),
 *     @OA\Property(property="image", type="int"),
 *     @OA\Property(property="approved_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $price
 * @property string|null $used
 * @property string|null $where_to_find
 * @property string|null $shipping_payment
 * @property bool|null $is_sold
 *
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Media|null $image
 */
class ProductOffer extends Model
{
    use HasFactory;
    use GeneratesSlug;

    /**
     * @var string
     */
    protected string $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_id',
        'image_id',
        'price',
        'used',
        'where_to_find',
        'shipping_payment',
        'is_sold',
        'approved_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'description' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
