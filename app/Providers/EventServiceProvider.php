<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Brand;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
use App\Observers\ArticleObserver;
use App\Observers\AuthorObserver;
use App\Observers\BrandObserver;
use App\Observers\IngredientObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The model observers.
     *
     * @var array
     */
    protected $observers = [
        Article::class => [ ArticleObserver::class ],
        Author::class => [ AuthorObserver::class ],
        Brand::class => [ BrandObserver::class ],
        Ingredient::class => [ IngredientObserver::class ],
        Product::class => [ ProductObserver::class ],
        User::class => [ UserObserver::class ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
