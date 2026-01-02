<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Product;
use App\Models\ReturnOrder;
use App\Models\Stock;
use App\Models\StockTax;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class ReturnOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {


            ReturnOrder::insert([
                [
                    'user_id'      => 3,
                    'date'         => now(),
                    'reference_no' => '12313131',
                    'subtotal'     => 24.000000,
                    'tax'          => 2.400000,
                    'discount'     => 0.000000,
                    'total'        => 26.400000,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]
            ]);

            Stock::insert([
                [
                    'product_id'      => 366,
                    'model_type'      => ReturnOrder::class,
                    'model_id'        => 1,
                    'item_type'       => Product::class,
                    'item_id'         => 366,
                    'variation_names' => null,
                    'sku'             => '4506553636',
                    'price'           => 24.000000,
                    'quantity'        => 1,
                    'discount'        => 0.000000,
                    'subtotal'        => 24.000000,
                    'tax'             => 2.400000,
                    'total'           => 26.400000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
            ]);

            StockTax::insert([
                [
                    'stock_id'   => 457,
                    'product_id' => 366,
                    'tax_id'     => 2,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 2.400000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }
    }
}