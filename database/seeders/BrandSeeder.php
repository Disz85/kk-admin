<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\User;
use Database\Factories\MediaFactory;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public const COUNT = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = User::query()->pluck('id');

        Brand::factory()->count(self::COUNT)->create([
            'created_by' => $users->random(),
            'image_id' => MediaFactory::new()->withRealImage()->create()->id,
        ]);
    }
}
