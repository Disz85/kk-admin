<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Categoryable extends MorphPivot
{
    use HasFactory;

    protected $table = 'categoryables';

    public $timestamps = false;
    public $incrementing = true;

    protected static function boot()
    {
        parent::boot();

        static::created(function (Categoryable $pivot) {
            if (($article = $pivot->pivotParent) instanceof Article) {
                $article->rebuildSlug();
                $article->timestamps = false;
                $article->saveQuietly();
            }
        });
    }
}
