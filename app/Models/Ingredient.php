<?php

namespace App\Models;

use App\Interfaces\HasDependencies;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * Ingredient model
 *
 * @OA\Schema(
 *     @OA\Xml(name="Ingredient"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="legacy_id", type="int"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="ewg_score", type="int", minimum=0, maximum=10),
 *     @OA\Property(property="comedogen_index", type="int", minimum=0, maximum=5),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="ewg_data", type="string"),
 *     @OA\Property(property="ewg_score_max", type="int", minimum=0, maximum=10),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_approved", type="bool"),
 *     @OA\Property(property="categories", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property int $image_id
 * @property int $ewg_score_max
 * @property int $comedogen_index
 *
 * @property string $name
 * @property string $slug
 * @property string $ewg_data
 * @property string $ewg_score
 * @property string $description
 *
 * @property bool $is_approved
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Media $image
 * @property Category $categories
 * @property Product $products
 */

class Ingredient extends Model implements HasDependencies
{
    use HasFactory;
    use GeneratesSlug;

    /**
     * @var string
     */
    protected $table = 'ingredients';

    /**
     * @var string[]
     */
    protected $with = [
        'categories',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'image_id',
        'description',
        'ewg_data',
        'ewg_score',
        'ewg_score_max',
        'comedogen_index',
        'is_approved',
    ];

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $casts = [
        'description' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_approved' => 'boolean',
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
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->using(Categoryable::class);
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredient');
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->products()->exists();
    }
}
