<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::create([
            "name" => "Owner",
            "email" => "owner@mail.com",
            "phone" => "082",
            "email_verified_at" => Carbon::now(),
            "password" => bcrypt('password')
        ]);
        $owner->assignRole(RoleEnum::OWNER);
        $admin = User::create([
            "name" => "Admin Agen",
            "email" => "admin@mail.com",
            "phone" => "082",
            "email_verified_at" => Carbon::now(),
            "password" => bcrypt('password')
        ]);
        $admin->assignRole(RoleEnum::ADMIN_AGEN);
        $agen = User::create([
            "name" => "Owner",
            "email" => "agen@mail.com",
            "phone" => "082",
            "email_verified_at" => Carbon::now(),
            "password" => bcrypt('password')
        ]);
        $agen->assignRole(RoleEnum::AGEN);
    }
}
