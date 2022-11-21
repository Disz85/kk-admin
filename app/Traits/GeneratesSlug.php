<?php
namespace App\Traits;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait GeneratesSlug
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->slugFrom ?? 'name')
            ->saveSlugsTo('slug')
            ->usingLanguage('hu')
            ->doNotGenerateSlugsOnUpdate();
    }
}
