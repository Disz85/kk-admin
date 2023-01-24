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
 * Class Article
 *
 * @OA\Schema(
 *     @OA\Xml(name="Article"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="lead", type="string"),
 *     @OA\Property(property="body", type="string"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="active", type="bool"),
 *     @OA\Property(property="hidden", type="bool"),
 *     @OA\Property(property="sponsored", type="bool"),
 *     @OA\Property(property="is_18_plus", type="bool"),
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
 * @property string $lead
 * @property string $body
 * @property int $image_id
 * @property bool $active
 * @property bool $hidden
 * @property bool $sponsored
 * @property bool $is_18_plus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 */
class Article extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected string $slugFrom = 'title';

    protected $casts = [
        'body' => 'array',
        'active' => 'boolean',
        'hidden' => 'boolean',
        'sponsored' => 'boolean',
        'is_18_plus' => 'boolean',
    ];

    protected $fillable = [
        'title',
        'lead',
        'body',
        'image_id',
        'active',
        'hidden',
        'sponsored',
        'is_18_plus',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->using(Categoryable::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    protected function prependSlugWithArticleType(): self
    {
        $this->slug = 'cikkek/' . $this->slug;

        return $this;
    }

    protected function prependSlugWithCategorySlug(): self
    {
        $this->slug = $this->category?->slug . '/' . $this->slug;

        return $this;
    }

    public function rebuildSlug(): void
    {
        if ($this->slug_frozen) {
            $currentSlugEnd = Str::afterLast($this->slug, '/');
            $this->slug = $currentSlugEnd;
            $this->slugOptions = $this->getSlugOptions();
        } else {
            $this->generateSlug();
        }

        $this->prependSlugWithArticleType()->prependSlugWithCategorySlug();

        $this->slug = $this->makeSlugUnique($this->slug);
    }
}
