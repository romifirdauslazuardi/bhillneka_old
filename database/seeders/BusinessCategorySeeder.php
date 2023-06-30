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
        ],[
            'name' => BusinessCategoryEnum::BARANG,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::JASA,
        ],[
            'name' => BusinessCategoryEnum::JASA,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::MIKROTIK,
        ],[
            'name' => BusinessCategoryEnum::MIKROTIK,
        ]);

        BusinessCategory::updateOrCreate([
            'name' => BusinessCategoryEnum::FNB,
        ],[
            'name' => BusinessCategoryEnum::FNB,
        ]);
    }
}
