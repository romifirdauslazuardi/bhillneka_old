<?php

namespace Database\Seeders;

use App\Enums\ProductEnum;
use App\Models\MikrotikConfig;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Log;

class ProductMikrotikEmptyMikrotikConfigIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $products = new Product();
            $products = $products->whereIn("mikrotik", [ProductEnum::MIKROTIK_HOTSPOT, ProductEnum::MIKROTIK_PPPOE]);
            $products = $products->whereNull("mikrotik_config_id");
            $products = $products->get();

            foreach ($products as $index => $row) {
                $mikrotikConfig = new MikrotikConfig();
                $mikrotikConfig = $mikrotikConfig->where("business_id", $row->business_id);
                $mikrotikConfig = $mikrotikConfig->orderBy("created_at", "DESC");
                $mikrotikConfig = $mikrotikConfig->first();

                if ($mikrotikConfig) {
                    $row->update([
                        "mikrotik_config_id" => $mikrotikConfig->id
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
        }
    }
}
