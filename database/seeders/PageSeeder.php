<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::updateOrCreate([
            'slug' => 'about-us'
        ],[
            'name' => 'About Us',
            'slug' => 'about-us',
        ]);

        Page::updateOrCreate([
            'slug' => 'privacy-policy'
        ],[
            'name' => 'Privacy Policy',
            'slug' => 'privacy-policy',
        ]);

        Page::updateOrCreate([
            'slug' => 'terms-and-conditions'
        ],[
            'name' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
        ]);
    }
}
