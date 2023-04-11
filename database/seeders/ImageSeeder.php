<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public const COUNT = 5;

    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->faker->image(
            storage_path('/images'),
            $this->faker->numberBetween(200, 500),
            $this->faker->numberBetween(200, 250),
        );
    }
}
