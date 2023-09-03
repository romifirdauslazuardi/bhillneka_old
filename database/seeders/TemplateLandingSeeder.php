<?php

namespace Database\Seeders;

use App\Models\LandingTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateLandingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LandingTemplate::create([
            'name' => 'hotel',
            'slug' => 'hotel'
        ]);

        LandingTemplate::create([
            'name' => 'furniture',
            'slug' => 'furniture'
        ]);

        LandingTemplate::create([
            'name' => 'restaurant',
            'slug' => 'restaurant'
        ]);
        LandingTemplate::create([
            'name' => 'service',
            'slug' => 'service'
        ]);
    }
}
