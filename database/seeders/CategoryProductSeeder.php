<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RESTAURANT
        ProductCategory::create([
            'name' => 'Breakfast',
            'image' => null,
            'business_id' => 2,
        ]);
        ProductCategory::create([
            'name' => 'Lunch',
            'image' => null,
            'business_id' => 2,
        ]);
        ProductCategory::create([
            'name' => 'Dinner',
            'image' => null,
            'business_id' => 2,
        ]);
        ProductCategory::create([
            'name' => 'Tea & Coffe',
            'image' => null,
            'business_id' => 2,
        ]);
    }
}
