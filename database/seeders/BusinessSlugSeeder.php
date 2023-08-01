<?php

namespace Database\Seeders;

use App\Helpers\SlugHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;
use DB;
use Log;

class BusinessSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $business = new Business();
        $business = $business->whereNull("slug");
        $business = $business->get();

        DB::beginTransaction();
        try {
            foreach($business as $index => $row){
                $row->update([
                    'slug' => SlugHelper::generate(Business::class,$row->name,'slug')
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
        }
    }
}
