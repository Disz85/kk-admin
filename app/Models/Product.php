<?php

namespace App\Models;

use App\Traits\GeneratesSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 *
 * Fields
 * @property int $id
 * @property int $legacy_id
 * @property string $name
 * @property string $canonical_name
 * @property string $slug
 * @property string $description
 * @property int $brand_id
 * @property bool $active
 * @property bool $hidden
 * @property bool $sponsored
 * @property int $price
 * @property bool $is_18_plus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 */
class Product extends Model
{
    use GeneratesSlug;
    use HasFactory;

    protected string $slugFrom = 'name';

    public function shelves()
    {
        return $this->morphToMany(Shelf::class, 'shelves')->using('product_shelf');
    }
}
