<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Shelf
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property bool $is_private
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Shelf extends Model
{
    use HasFactory;
    use GeneratesSlug;

    protected string $slugFrom = 'title';

    public array $rules = [
        'title' => 'required',
        'user_id' => 'required',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_shelf', null, 'product_id');
    }
}
