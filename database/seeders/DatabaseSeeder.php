<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            MediaSeeder::class,
            CategorySeeder::class,
            IngredientSeeder::class,
            AuthorSeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            BrandSeeder::class,
            BrandChangeRequestSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
