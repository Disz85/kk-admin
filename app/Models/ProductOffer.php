<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOffer extends Model
{
    use HasFactory;
    use GeneratesSlug;

    /**
     * @var string
     */
    protected string $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_id',
        'image_id',
        'price',
        'used',
        'place',
        'shipping_payment',
        'is_sold',
        'approved_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'description' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
