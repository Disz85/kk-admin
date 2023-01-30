<?php

namespace App\Providers;

use App\Events\AuthorDeletingEvent;
use App\Listeners\AuthorDeleting;
use App\Models\Article;
use App\Models\Ingredient;
use App\Observers\ArticleObserver;
use App\Observers\IngredientObserver;
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
        AuthorDeletingEvent::class => [
            AuthorDeleting::class,
        ],
    ];

    /**
     * The model observers.
     *
     * @var array
     */
    protected $observers = [
        Article::class => [ ArticleObserver::class ],
        Ingredient::class => [ IngredientObserver::class ],
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
