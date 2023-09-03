<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'name' => RoleEnum::OWNER,
        ], [
            'name' => RoleEnum::OWNER,
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::AGEN,
        ], [
            'name' => RoleEnum::AGEN,
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::CUSTOMER,
        ], [
            'name' => RoleEnum::CUSTOMER,
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::ADMIN_AGEN,
        ], [
            'name' => RoleEnum::ADMIN_AGEN,
            'guard_name' => 'web'
        ]);
    }
}
