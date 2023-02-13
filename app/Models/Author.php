<?php

namespace App\Models;

use App\Interfaces\HasDependencies;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * Author model
 *
 * @OA\Schema(
 *     @OA\Xml(name="Author"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="articles", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @property int $id
 * @property string|null $title
 * @property string $name
 * @property string $slug
 * @property string|null $email
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Media|null $image_id
 * @property Article|null $articles
 */

class Author extends Model implements HasDependencies
{
    use GeneratesSlug;
    use HasFactory;

    /**
     * @var string
     */
    protected $slugFrom = 'name';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'name',
        'email',
        'description',
        'image_id',
    ];

    /**
     * @return BelongsToMany
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->articles()->exists();
    }
}
