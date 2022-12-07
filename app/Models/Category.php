<?php

namespace App\Models;

use App\Traits\CategoryHierarchy;
use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    use HasFactory;
    use GeneratesSlug;
    use CategoryHierarchy;

    public const TYPE_ARTICLE = 'article';
    public const TYPE_PRODUCT = 'product';

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
}
