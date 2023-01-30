<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Product
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property string $legacy_image_url
 * @property string $legacy_description
 * @property string $name
 * @property string $canonical_name
 * @property string $slug
 * @property int $image_id
 * @property string $price
 * @property string $size
 * @property string $where_to_find
 * @property string $description
 * @property int $brand_id
 * @property bool $active
 * @property bool $hidden
 * @property bool $sponsored
 * @property bool $is_18_plus
 * @property Carbon|null $created_at
 * @property int $created_by
 * @property Carbon|null $updated_at
 * @property int $updated_by
 * @property Carbon|null $published_at
 */
class Product extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected string $slugFrom = 'name';

    protected $casts = [
        'description' => 'array',
        'active' => 'boolean',
        'hidden' => 'boolean',
        'sponsored' => 'boolean',
        'is_18_plus' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'size',
        'where_to_find',
        'image_id',
        'brand_id',
        'active',
        'hidden',
        'sponsored',
        'is_18_plus',
        'created_by',
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
