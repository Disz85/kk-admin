<?php

namespace Tests;

use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Queue;
use Laravel\Scout\Jobs\MakeSearchable;
use Laravel\Scout\Jobs\RemoveFromSearch;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $seeder = TestDatabaseSeeder::class;
    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        if (! is_dir(storage_path('images/seeder'))) {
            mkdir(storage_path('images/seeder'));
        }

        Queue::fake([
            MakeSearchable::class,
            RemoveFromSearch::class,
            \Elastic\ScoutDriverPlus\Jobs\RemoveFromSearch::class,
        ]);
    }
}
