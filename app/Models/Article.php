<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Article extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected $with = [
        'categories',
        'tags',
    ];

    protected $slugFrom = 'title';

    protected $casts = [
        'body' => 'array',
        'slug_frozen' => 'boolean',
        'active' => 'boolean',
        'hidden' => 'boolean',
        'sponsored' => 'boolean',
        'is_18_plus' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'title',
        'slug',
        'lead',
        'body',
        'image_id',
        'category_id',
        'active',
        'hidden',
        'sponsored',
        'is_18_plus',
    ];

//    public function author(): BelongsTo
//    {
//        return $this->belongsTo(Author::class);
//    }
//
//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class);
//    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public static function getTagClassName(): string
    {
        return Tag::class;
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
        if ($this->faq) {
            $categoryType = 'kerdesek_es_valaszok';
        } elseif ($this->vitamin_wise || $this->mineral_wise || $this->micronutrient_wise) {
            $categoryType = 'vitamin_kisokos';
        } else {
            $categoryType = 'cikkek';
        }

        $this->slug = $categoryType . '/' . $this->slug;

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
