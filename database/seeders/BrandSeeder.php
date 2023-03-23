<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public const COUNT = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->pluck('id');

        Brand::factory()->count(self::COUNT)->create(['created_by' => $users->random()]);
    }
}
