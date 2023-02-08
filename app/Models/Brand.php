<?php

namespace App\Models;

use App\Casts\BooleanDatetime;
use App\Interfaces\HasDependencies;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
 *     @OA\Property(property="approved", type="bool"),
 *     @OA\Property(property="image_id", type="int"),
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
 * @property string $url
 * @property string $description
 * @property string $where_to_find
 * @property int $image_id
 * @property bool $approved
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Media|null $image
 */
class Brand extends Model implements HasDependencies
{
    use GeneratesSlug;
    use HasFactory;

    protected $slugFrom = 'title';

    protected $with = [
        'image',
        'tags',
    ];

    protected $casts = [
        'description' => 'array',
        'approved' => BooleanDatetime::class,
    ];

    protected $fillable = [
        'id',
        'legacy_id',
        'title',
        'url',
        'description',
        'image_id',
        'where_to_find',
        'created_by',
        'updated_by',
        'approved',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }

    public function hasDependencies(): bool
    {
        return $this->products()->exists();
    }
}
