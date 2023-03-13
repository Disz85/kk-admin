<?php

namespace App\Models;

use App\Enum\SkinConcernEnum;
use App\Enum\SkinTypeEnum;
use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @OA\Schema(
 *     @OA\Xml(name="User"),
 *     @OA\Property(property="id", type="int"),
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="sso_id", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="lastname", type="string"),
 *     @OA\Property(property="firstname", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="birth_year", type="int"),
 *     @OA\Property(property="skin_type", type="string"),
 *     @OA\Property(property="skin_concern", type="string"),
 *     @OA\Property(property="image_id", type="int"),
 *     @OA\Property(property="roles", type="string"),
 *     @OA\Property(property="shelves", type="int"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * );
 *
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $uuid
 * @property string|null $sso_id
 * @property string|null $title
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string $username
 * @property string|null $email
 * @property string|null $slug
 * @property string|null $description
 * @property int|null $birth_year
 * @property string|null $skin_type
 * @property string|null $skin_concern
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Media|null $image_id
 * @property Role[] $roles
 * @property Shelf|null $shelves
 */
class User extends Authenticatable
{
    use GeneratesSlug;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected string $slugFrom = 'username';
    protected string $guard_name = 'api';

    /**
     * @var string[]
     */
    protected $with = ['image'];

    /**
     * @var array|string[]
     */
    public array $rules = [
        'email' => 'required|email',
        'username' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'lastname',
        'firstname',
        'email',
        'password',
        'username',
        'sso_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'skin_type' => SkinTypeEnum::class,
        'skin_concern' => SkinConcernEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function productChangeRequests(): HasMany
    {
        return $this->HasMany(ProductChangeRequest::class);
    }
}
