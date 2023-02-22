<?php

namespace App\Models;

use App\Interfaces\HasDependencies;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use OpenApi\Annotations as OA;

/**
 * Class Brand
 *
 * @OA\Schema(
 *     @OA\Xml(name="Brand"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="url", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="where_to_find", type="string"),
 *     @OA\Property(property="created_by", type="int"),
 *     @OA\Property(property="updated_by", type="int"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="products", type="int"),
 *     @OA\Property(property="tags", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $uuid
 * @property int $legacy_id
 * @property string $title
 * @property string $slug
 * @property string|null $url
 * @property string|null $description
 * @property string|null $where_to_find
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $created_by
 * @property User|null $updated_by
 * @property int|null $image_id
 * @property Media|null $image
 * @property Product[]|Collection $products
 * @property Tag[]|Collection $tags
 */
class Brand extends Model implements HasDependencies
{
    use GeneratesSlug;
    use HasFactory;

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var string
     */
    protected $slugFrom = 'title';

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
        'title',
        'url',
        'description',
        'image_id',
        'where_to_find',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->products()->exists();
    }
}
