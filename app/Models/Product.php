<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'slug',
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
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function shelves()
    {
        return $this->morphToMany(Shelf::class, 'shelves')->using('product_shelf');
    }
}
