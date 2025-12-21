<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Enums\Status;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Purchase;
use App\Enums\PurchaseStatus;
use App\Models\Stock;
use App\Services\ProductVariationService;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;


class PurchaseTableSeeder extends Seeder
{

    public int $i = 1, $j = 1;
    public array $purchaseSubtotals = [];
    public array $purchaseTotals = [];

    public function run()
    {
        $purchases = [
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1001",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 2,
                'date' => now(),
                'reference_no' => "1002",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1003",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1004",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 2,
                'date' => now(),
                'reference_no' => "1005",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1006",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1007",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 2,
                'date' => now(),
                'reference_no' => "1008",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 2,
                'date' => now(),
                'reference_no' => "1009",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1010",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1011",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 2,
                'date' => now(),
                'reference_no' => "1012",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
            [
                'supplier' => 1,
                'date' => now(),
                'reference_no' => "1013",
                'status' => PurchaseStatus::RECEIVED,
                'total' => 0,
            ],
        ];
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {

            $products = Product::select('id', 'sku', 'buying_price')->where('can_purchasable', Ask::YES)->get()->toArray();
            $from = 0;
            $to = ceil(count($products) / count($purchases));
            $temp = $to;

            foreach ($purchases as $purchase) {

                $purchase = Purchase::create([
                    'supplier_id' => $purchase['supplier'],
                    'date' => $purchase['date'],
                    'reference_no' => $purchase['reference_no'],
                    'status' => $purchase['status'],
                    'tax' => 0,
                    'discount' => 0,
                    'subtotal' => 0,
                    'total' => $purchase['total'],
                ]);


                // new 
                $purchaseTotal = 0;
                for ($i = $from; $i < $to; $i++) {
                    if (isset($products[$i]['id'])) {
                        $quantity = rand(10, 100);
                        $stock = Stock::create([
                            'product_id' => $products[$i]['id'],
                            'model_type' => Purchase::class,
                            'model_id' => $purchase->id,
                            'item_type' => Product::class,
                            'item_id' => $products[$i]['id'],
                            'variation_names' => '',
                            'sku' => $products[$i]['sku'],
                            'price' => $products[$i]['buying_price'],
                            'quantity' => $quantity,
                            'discount' => 0,
                            'tax' => 0,
                            'subtotal' => (($products[$i]['buying_price']) * $quantity),
                            'total' => (($products[$i]['buying_price']) * $quantity),
                            'status' => Status::ACTIVE,
                        ]);

                        $purchaseTotal += $products[$i]['buying_price'];
                    }
                }

                $from = $to;
                $to += $temp;

                $purchase->total = $purchaseTotal;
                $purchase->subtotal = $purchaseTotal;
                $purchase->save();
            }
        }
    }
}
