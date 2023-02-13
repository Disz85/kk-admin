<?php

namespace App\Models;

use App\Enum\CategoryTypeEnum;
use App\Interfaces\HasDependencies;
use App\Traits\CategoryHierarchy;
use App\Traits\GeneratesSlug;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * Class Category
 *
 * @OA\Schema(
 *     @OA\Xml(name="Category"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="parent_id", type="int"),
 *     @OA\Property(property="is_archived", type="bool"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="products", type="int"),
 *     @OA\Property(property="articles", type="int"),
 *     @OA\Property(property="ingredients", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $parent_id
 * @property bool $is_archived
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Product|null $products
 * @property Article|null $articles
 * @property Ingredient|null $ingredients
 */
class Category extends Model implements HasDependencies
{
    use HasFactory;
    use HasUuid;
    use GeneratesSlug;
    use CategoryHierarchy;

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'is_archived',
        'parent_id',
        'type',
        'description',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'description' => 'array',
        'type' => CategoryTypeEnum::class,
    ];

    /**
     * @return MorphToMany
     */
    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

    /**
     * @return MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'categoryable');
    }

    /**
     * @return MorphToMany
     */
    public function ingredients(): MorphToMany
    {
        return $this->morphedByMany(Ingredient::class, 'categoryable');
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->products()->exists()
            || $this->articles()->exists()
            || $this->ingredients()->exists();
    }
}
