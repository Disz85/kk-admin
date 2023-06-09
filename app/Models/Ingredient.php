<?php

namespace App\Models;

use App\Interfaces\HasDependencies;
use App\Resources\Elastic\IngredientResource;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Ingredient model
 *
 * @OA\Schema(
 *     @OA\Xml(name="Ingredient"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="ewg_data", type="string"),
 *     @OA\Property(property="ewg_score", type="int", minimum=0, maximum=10),
 *     @OA\Property(property="ewg_score_max", type="int", minimum=0, maximum=10),
 *     @OA\Property(property="comedogen_index", type="int", minimum=0, maximum=5),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="categories", type="int"),
 *     @OA\Property(property="products", type="int"),
 *     @OA\Property(property="created_by", type="int"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
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
 * @property string $slug
 * @property string|null $ewg_data
 * @property int|null $ewg_score
 * @property int|null $ewg_score_max
 * @property int|null $comedogen_index
 * @property bool|null $is_top
 * @property string|null $description
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Category[]|Collection $categories
 * @property Product[]|Collection $products
 * @property User|null $created_by
 */

class Ingredient extends Model implements HasDependencies
{
    use HasFactory;
    use GeneratesSlug;
    use Searchable;

    /**
     * @var string
     */
    protected $table = 'ingredients';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'ewg_data',
        'ewg_score',
        'ewg_score_max',
        'comedogen_index',
        'is_top',
        'published_at',
        'created_by',
    ];

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'description' => 'array',
    ];

    public function shouldBeSearchable(): bool
    {
        return ! is_null($this->published_at);
    }

    /**
     * @return string[]
     */
    public function searchableWith(): array
    {
        return ['categories'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return (new IngredientResource($this))->toArray(request());
    }

    /**
     * @return MorphToMany<Category>
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->using(Categoryable::class);
    }

    /**
     * @return BelongsToMany<Product>
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

    /**
     * @return BelongsTo<User, Ingredient>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
