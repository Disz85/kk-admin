<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * Class Article
 *
 * @OA\Schema(
 *     @OA\Xml(name="Article"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="uuid", type="string"),
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
 * @property string $uuid
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
 * @property-read Collection|Tag[]|null $tags
 * @property-read Collection|Category[]|null $categories
 * @property-read Collection|Author[] $authors
 */
class Article extends Model
{
    use GeneratesSlug;
    use HasFactory;

    /**
     * @var string
     */
    protected string $slugFrom = 'title';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'body' => 'array',
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
        'published_at',
    ];

    /**
     * @return BelongsTo<Media, Article>
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * @return MorphToMany<Tag>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
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
     * @return BelongsToMany<Author>
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
        $this->generateSlug();

        $this->slug = $this->makeSlugUnique($this->slug);
    }
}
