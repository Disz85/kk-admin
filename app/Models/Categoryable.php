<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Categoryable extends MorphPivot
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'categoryables';

    public $timestamps = false;
    public $incrementing = true;

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Categoryable $pivot): void {
            if (($article = $pivot->pivotParent) instanceof Article) {
                $article->rebuildSlug();
                $article->timestamps = false;
                $article->saveQuietly();
            }
        });
    }
}
