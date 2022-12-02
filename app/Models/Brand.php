<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Brand
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $image_id
 * @property int $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Brand extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected $with = [
        'tags',
    ];

    protected $slugFrom = 'title';

    protected $casts = [
        'description' => 'array',
        'approved' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'legacy_id',
        'title',
        'slug',
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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }
}
