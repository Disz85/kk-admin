<?php

namespace App\Models;

use app\Enum\SkinConcernEnum;
use app\Enum\SkinTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property string $legacy_nickname
 * @property string $sso_id
 * @property string $title
 * @property string $lastname
 * @property string $firstname
 * @property string $username
 * @property string $email
 * @property string $slug
 * @property string $description
 * @property int $image_id
 * @property int $birth_year
 * @property string $skin_type
 * @property string $skin_concern
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Media|null $image
 * @property Role[] $roles
 * @property Collection<Shelf> $shelves
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected string $slugFrom = 'username';

    public $rules = [
        'email' => 'required|email',
        'username' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'lastname',
        'firstname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'skin_type' => SkinTypeEnum::class,
        'skin_concern' => SkinConcernEnum::class,
    ];
}
