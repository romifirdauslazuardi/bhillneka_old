<?php

namespace Database\Seeders;

use App\Models\UserBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserBank::create([
            'user_id' => 3,
            'bank_id' => 63,
            'business_id' => 1,
            'bank_settlement_id' => 123,
            'name' => 'TEST',
            'branch' => 'WKWK',
            'number' => 9283234,
            'default' => 1,
            'author_id' => 3,
            'status' => 2
        ]);
        UserBank::create([
            'user_id' => 3,
            'bank_id' => 63,
            'business_id' => 2,
            'bank_settlement_id' => 123,
            'name' => 'TEST',
            'branch' => 'WKWK',
            'number' => 9283234,
            'default' => 1,
            'author_id' => 3,
            'status' => 2
        ]);
        UserBank::create([
            'user_id' => 3,
            'bank_id' => 63,
            'business_id' => 3,
            'bank_settlement_id' => 123,
            'name' => 'TEST',
            'branch' => 'WKWK',
            'number' => 9283234,
            'default' => 1,
            'author_id' => 3,
            'status' => 2
        ]);
        UserBank::create([
            'user_id' => 3,
            'bank_id' => 63,
            'business_id' => 4,
            'bank_settlement_id' => 123,
            'name' => 'TEST',
            'branch' => 'WKWK',
            'number' => 9283234,
            'default' => 1,
            'author_id' => 3,
            'status' => 2
        ]);
        UserBank::create([
            'user_id' => 3,
            'bank_id' => 63,
            'business_id' => 5,
            'bank_settlement_id' => 123,
            'name' => 'TEST',
            'branch' => 'WKWK',
            'number' => 9283234,
            'default' => 1,
            'author_id' => 3,
            'status' => 2
        ]);
    }
}
