<?php

namespace App\Providers;

use App\Repositories\MediaRepository;
use App\Repositories\MediaRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $singletons = [
        MediaRepositoryInterface::class => MediaRepository::class,
    ];
}
