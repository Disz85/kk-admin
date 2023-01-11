<?php

namespace App\Models;

use App\Enum\CategoryTypeEnum;
use App\Traits\CategoryHierarchy;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Category
 *
 * @OA\Schema(
 *     @OA\Xml(name="Author"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="legacy_id", type="int"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="parent_id", type="int"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="archived", type="bool")
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $image_id
 * @property int $parent_id
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

    protected $table = 'categories';

    protected $slugFrom = 'name';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'archived',
        'parent_id',
        'image_id',
        'type',
        'description',
    ];

    protected $casts = [
        'description' => 'array',
        'type' => CategoryTypeEnum::class,
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
