<?php

namespace App\Models;

use App\Traits\CategoryHierarchy;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Category
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $image_id
 * @property int parent_id
 * @property bool $archived
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Category extends Model
{
    use HasFactory;
    use GeneratesSlug;
    use CategoryHierarchy;

    public const TYPE_ARTICLE = 'article';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_SKINTYPE = 'skintype';
    public const TYPE_SKINCONCERN = 'skinconcern';
    public const TYPE_INGREDIENT = 'ingredient';

    protected $table = 'categories';

    protected $slugFrom = 'name';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'archived',
        'parent_id',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'categoryable');
    }

    public function ingredients(): MorphToMany
    {
        return $this->morphedByMany(Ingredient::class, 'categoryable');
    }
}
