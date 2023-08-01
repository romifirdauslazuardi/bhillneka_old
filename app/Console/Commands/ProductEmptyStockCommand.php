<?php

namespace App\Console\Commands;

use App\Enums\ProductEnum;
use App\Enums\ProductStockEnum;
use Illuminate\Console\Command;
use App\Models\Product;
use Symfony\Component\Console\Command\Command as CommandAlias;
use DB;
use Log;

class ProductEmptyStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:empty-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Empty Stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $products = new Product();
            $products = $products->where("is_using_stock",ProductEnum::IS_USING_STOCK_TRUE);
            $products = $products->where("status",ProductEnum::STATUS_TRUE);
            $products = $products->get();

            foreach($products as $index => $row){
                $stock = $row->stocks()->where("type",ProductStockEnum::TYPE_MASUK)->sum("qty") - $row->stocks()->where("type",ProductStockEnum::TYPE_KELUAR)->sum("qty");

                if($stock <= 0){
                    $row->update([
                        'status' => ProductEnum::STATUS_FALSE
                    ]);
                }
            }

            DB::commit();

            return CommandAlias::SUCCESS;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency($th->getMessage());
            return CommandAlias::FAILURE;
        }

        
    }
}
