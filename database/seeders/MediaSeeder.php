<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public const COUNT = 20;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Media::factory()->count(self::COUNT)->create();
    }
}
