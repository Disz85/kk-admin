<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * Class Article
 *
 * @OA\Schema(
 *     @OA\Xml(name="Article"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="lead", type="string"),
 *     @OA\Property(property="body", type="string"),
 *     @OA\Property(property="is_active", type="bool"),
 *     @OA\Property(property="is_sponsored", type="bool"),
 *     @OA\Property(property="is_18_plus", type="bool"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="tags", type="int"),
 *     @OA\Property(property="categories", type="int"),
 *     @OA\Property(property="authors", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="published_at", type="string", format="date-time")
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $lead
 * @property string|null $body
 * @property bool $is_active
 * @property bool $is_sponsored
 * @property bool $is_18_plus
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 *
 * @property Media|null $image_id
 * @property Tag|null $tags
 * @property Category|null $categories
 * @property Author $authors
 *
 */
class Article extends Model
{
    use GeneratesSlug;
    use HasFactory;
    use HasUuid;

    /**
     * @var string
     */
    protected string $slugFrom = 'title';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var string[]
     */
    protected $casts = [
        'body' => 'array',
        'is_active' => 'boolean',
        'is_sponsored' => 'boolean',
        'is_18_plus' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'lead',
        'body',
        'image_id',
        'is_active',
        'is_sponsored',
        'is_18_plus',
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
     * @return BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * @return void
     */
    public function rebuildSlug(): void
    {
        if ($this->slug_frozen) {
            $currentSlugEnd = Str::afterLast($this->slug, '/');
            $this->slug = $currentSlugEnd;
            $this->slugOptions = $this->getSlugOptions();
        } else {
            $this->generateSlug();
        }

        $this->slug = $this->makeSlugUnique($this->slug);
    }
}
