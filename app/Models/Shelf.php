<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * Class Shelf
 *
 *  @OA\Schema(
 *     @OA\Xml(name="Shelf"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="is_private", type="bool"),
 *     @OA\Property(property="user_id", type="int"),
 *     @OA\Property(property="products", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property bool $is_private
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user_id
 * @property Product $products
 */
class Shelf extends Model
{
    use HasFactory;
    use GeneratesSlug;

    /**
     * @var string
     */
    protected string $slugFrom = 'title';

    /**
     * @var array|string[]
     */
    public array $rules = [
        'title' => 'required',
        'user_id' => 'required',
    ];

    /**
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_shelf', null, 'product_id');
    }
}
