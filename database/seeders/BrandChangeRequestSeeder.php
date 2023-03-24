<?php

namespace Database\Seeders;

use App\Models\BrandChangeRequest;
use Illuminate\Database\Seeder;

class BrandChangeRequestSeeder extends Seeder
{
    public const COUNT = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BrandChangeRequest::factory()->count(self::COUNT)->create();
    }
}
