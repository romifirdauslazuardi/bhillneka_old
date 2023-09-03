<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Business::create([
            "name" => "Asiifdev",
            "slug" => "asiifdev",
            "location" => "kestalan",
            "description" => "TESTTTT",
            "user_id" => 3,
            "category_id" => 2,
            "village_code" => "3309072004",
            "author_id" => 3
        ]);

        Business::create([
            "name" => "Asiif Resto",
            "slug" => "asiif-resto",
            "location" => "kestalan",
            "description" => "TESTTTT",
            "user_id" => 3,
            "category_id" => 7,
            "village_code" => "3309072004",
            "author_id" => 3
        ]);

        Business::create([
            "name" => "Asiif Furniture",
            "slug" => "asiif-furniture",
            "location" => "kestalan",
            "description" => "TESTTTT",
            "user_id" => 3,
            "category_id" => 8,
            "village_code" => "3309072004",
            "author_id" => 3
        ]);

        Business::create([
            "name" => "Asiif Mirkotik",
            "slug" => "asiif-mikrotik",
            "location" => "kestalan",
            "description" => "TESTTTT",
            "user_id" => 3,
            "category_id" => 3,
            "village_code" => "3309072004",
            "author_id" => 3
        ]);

        Business::create([
            "name" => "Asiif Hotel",
            "slug" => "asiif-hotel",
            "location" => "kestalan",
            "description" => "TESTTTT",
            "user_id" => 3,
            "category_id" => 5,
            "village_code" => "3309072004",
            "author_id" => 3
        ]);
    }
}
