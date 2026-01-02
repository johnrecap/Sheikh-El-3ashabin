<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Damage;
use App\Models\Product;
use App\Models\Stock;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class DamageTableSeeder extends Seeder
{
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {

            Damage::insert([
                [
                    'date'         => now(),
                    'reference_no' => "1001",
                    'tax'          => 3.750000,
                    'discount'     => 0.000000,
                    'subtotal'     => 15.000000,
                    'total'        => 18.750000,
                ],
                [
                    'date'         => now(),
                    'reference_no' => "1002",
                    'tax'          => 4.500000,
                    'discount'     => 0.000000,
                    'subtotal'     => 30.000000,
                    'total'        => 34.500000,
                ],
                [
                    'date'         => now(),
                    'reference_no' => "1003",
                    'tax'          => 0.000000,
                    'discount'     => 0.000000,
                    'subtotal'     => 750.000000,
                    'total'        => 750.000000,
                ],

            ]);

            Stock::insert([
                [
                    'product_id'      => 1,
                    'model_type'      => Damage::class,
                    'model_id'        => 1,
                    'item_type'       => Product::class,
                    'item_id'         => 1,
                    'variation_names' => null,
                    'sku'             => '5723867639',
                    'price'           => 15.000000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 15.000000,
                    'tax'             => 3.750000,
                    'total'           => 18.750000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ],
                [
                    'product_id'      => 2,
                    'model_type'      => Damage::class,
                    'model_id'        => 2,
                    'item_type'       => Product::class,
                    'item_id'         => 2,
                    'variation_names' => null,
                    'sku'             => '2457977357',
                    'price'           => 30.000000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 30.000000,
                    'tax'             => 4.500000,
                    'total'           => 34.500000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ],
                [
                    'product_id'      => 419,
                    'model_type'      => Damage::class,
                    'model_id'        => 3,
                    'item_type'       => Product::class,
                    'item_id'         => 419,
                    'variation_names' => null,
                    'sku'             => '2801208414',
                    'price'           => 250.000000,
                    'quantity'        => -3,
                    'discount'        => 0.000000,
                    'subtotal'        => 750.000000,
                    'tax'             => 0.000000,
                    'total'           => 750.000000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ],



            ]);

        }
    }
}
