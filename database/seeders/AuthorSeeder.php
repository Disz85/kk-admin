<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public const COUNT = 20;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Author::factory()->count(self::COUNT)->create();
    }
}
