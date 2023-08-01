<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Table;
use DB;
use Log;

class TableCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = new Table();
        $tables = $tables->whereNull("code");
        $tables = $tables->get();

        DB::beginTransaction();
        try {
            foreach($tables as $index => $row){
                $row->update([
                    'code' => Str::random(10)
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
        }
    }
}
