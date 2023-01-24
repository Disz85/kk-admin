<?php

namespace App\Models;

use App\Events\AuthorDeletingEvent;
use App\Traits\GeneratesSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Author model
 *
 * @OA\Schema(
 *     @OA\Xml(name="Author"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string")
 * );
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $title
 * @property string $name
 * @property string $email
 * @property string $slug
 * @property string $description
 *
 */

class Author extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected $fillable = [
        'title',
        'name',
        'email',
        'slug',
        'description',
        'image_id',
    ];

    protected $dispatchesEvents = [
        'deleting' => AuthorDeletingEvent::class,
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
