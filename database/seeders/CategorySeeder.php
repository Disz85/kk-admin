<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public const COUNT = 30; // Pro type (6 type)

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (CategoryTypeEnum::cases() as $type) {
            Category::factory()->count(self::COUNT)->create(['type' => $type]);
        }
    }
}
