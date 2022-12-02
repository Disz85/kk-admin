<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Ingredient extends Model
{
    use HasFactory;
    use GeneratesSlug;

    /**
     * @var string[]
     */
    protected $with = [
        'categories',
    ];

    /**
     * @var string[]
     *
     * ewg_data = ['None','Limited','Fair','Good', 'Robust']
     * ewg_score = 0-10
     * ewg_score_max = 0-10
     */
    protected $fillable = [
        'name',
        'slug',
        'image_id',
        'ewg_data',
        'ewg_score',
        'ewg_score_max',
        'comedogen_index'
    ];

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $casts = [
        'description' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->using(Categoryable::class);
    }

}
