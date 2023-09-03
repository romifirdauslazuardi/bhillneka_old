<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PageSeeder::class,
            TemplateLandingSeeder::class,
            BusinessCategorySeeder::class,
            BankSeeder::class,
            TableCodeSeeder::class,
            ProductMikrotikEmptyMikrotikConfigIdSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
            UserSeeder::class,
            BusinessSeeder::class,
            BusinessSlugSeeder::class,
            UserBankSeeder::class,
        ]);
    }
}
