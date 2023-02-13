<?php

namespace App\Models;

use App\Interfaces\HasDependencies;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * Tag model
 *
 * @OA\Schema(
 *     @OA\Xml(name="Tag"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_highlighted", type="bool"),
 *     @OA\Property(property="articles", type="object"),
 *     @OA\Property(property="brands", type="object"),
 *     @OA\Property(property="products", type="object"),
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
 * @property bool $is_highlighted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Article $articles
 * @property Brand $brands
 * @property Product $products
 */
class Tag extends Model implements HasDependencies
{
    use GeneratesSlug;
    use HasFactory;

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'is_highlighted',
    ];

    /**
     * @return MorphToMany
     */
    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'taggable');
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->products()->exists()
            || $this->articles()->exists()
            || $this->brands()->exists();
    }
}
