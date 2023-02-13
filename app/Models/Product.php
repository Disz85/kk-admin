<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * Class Product
 *
 * @OA\Schema(
 *     @OA\Xml(name="Product"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="canonical_name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="price", type="string"),
 *     @OA\Property(property="size", type="string"),
 *     @OA\Property(property="where_to_find", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_active", type="bool"),
 *     @OA\Property(property="is_sponsored", type="bool"),
 *     @OA\Property(property="is_18_plus", type="bool"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="tags", type="int"),
 *     @OA\Property(property="categories", type="int"),
 *     @OA\Property(property="brand_id", type="int"),
 *     @OA\Property(property="ingredients", type="int"),
 *     @OA\Property(property="ingredients_by", type="int"),
 *     @OA\Property(property="created_by", type="int"),
 *     @OA\Property(property="updated_by", type="int"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $name
 * @property string|null $canonical_name
 * @property string $slug
 * @property string|null $price
 * @property string|null $size
 * @property string|null $where_to_find
 * @property string|null $description
 * @property bool $is_active
 * @property bool $is_sponsored
 * @property bool $is_18_plus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 *
 * @property Media|null $image_id
 * @property Tag $tags
 * @property Category $categories
 * @property Brand $brand_id
 * @property Ingredient $ingredients
 * @property User|null $created_by
 * @property User|null $updated_by
 * @property User|null $ingredients_by
 */
class Product extends Model
{
    use GeneratesSlug;
    use HasFactory;

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
    protected $casts = [
        'description' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'canonical_name',
        'description',
        'price',
        'size',
        'where_to_find',
        'image_id',
        'brand_id',
        'is_active',
        'is_sponsored',
        'is_18_plus',
        'created_by',
        'updated_by',
        'ingredients_by',
        'published_at',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * @return MorphToMany
     */
    public function shelves(): MorphToMany
    {
        return $this->morphToMany(Shelf::class, 'shelves')->using('product_shelf');
    }

    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }

    /**
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->using(Categoryable::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * @return BelongsToMany
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient');
    }
}
