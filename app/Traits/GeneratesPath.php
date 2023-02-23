<?php

namespace App\Traits;

trait GeneratesPath
{
    public function generatePath($categories)
    {
        $path = [];

        foreach ($categories as $category) {
            $path[] = $category->slug;
        }

        return implode('/', $path);
    }

    public static function bootGeneratesPath(): void
    {
        static::saving(function ($model): void {
            if (! $model->slug) {
                $model->generateSlug();
            }

            $model->path = $model->generatePath($model->hierarchy);
        });
    }
}
