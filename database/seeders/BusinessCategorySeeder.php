<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\BusinessCategoryEnum;
use App\Models\BusinessCategory;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::BARANG,
            'template_id' => 2,
        ],[
            'name' => BusinessCategoryEnum::BARANG,
            'template_id' => 2,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::JASA,
            'template_id' => 4
        ],[
            'name' => BusinessCategoryEnum::JASA,
            'template_id' => 4
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::MIKROTIK,
            'template_id' => 4
        ],[
            'name' => BusinessCategoryEnum::MIKROTIK,
            'template_id' => 4
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::FNB,
            'template_id' => 3,
        ],[
            'name' => BusinessCategoryEnum::FNB,
            'template_id' => 3,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::HOTEL,
            'template_id' => 1,
        ],[
            'name' => BusinessCategoryEnum::HOTEL,
            'template_id' => 1,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::ELECTRONIC,
            'template_id' => 2,
        ],[
            'name' => BusinessCategoryEnum::ELECTRONIC,
            'template_id' => 2,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::RESTAURANT,
            'template_id' => 3,
        ],[
            'name' => BusinessCategoryEnum::RESTAURANT,
            'template_id' => 3,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::FURNITURE,
            'template_id' => 2,
        ],[
            'name' => BusinessCategoryEnum::FURNITURE,
            'template_id' => 2,
        ]);
    }
}
