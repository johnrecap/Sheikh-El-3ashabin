<?php

namespace Database\Seeders;


use App\Enums\Ask;
use App\Enums\Source;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Enums\OrderType;
use App\Models\StockTax;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PosPaymentMethod;
use Illuminate\Database\Seeder;
use App\Models\ProductVariation;
use Dipokhalder\EnvEditor\EnvEditor;

class PosOrderTableSeeder extends Seeder
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

            Order::insert([
                [
                    'order_serial_no'    => date('dmy') . '6',
                    'user_id'            => 2,
                    'subtotal'           => 85.500000,
                    'tax'                => 7.600000,
                    'discount'           => 0.000000,
                    'delivery_charge'    => 0.000000,
                    'total'              => 93.100000,
                    'order_type'         => OrderType::POS,
                    'order_datetime'     => now(),
                    'payment_method'     => 1,
                    'payment_status'     => PaymentStatus::PAID,
                    'pos_payment_method' => PosPaymentMethod::CASH,
                    'pos_payment_note'   => null,
                    'status'             => OrderStatus::DELIVERED,
                    'active'             => Ask::YES,
                    'source'             => Source::POS,
                    'reason'             => NULL,
                    'created_at'         => now(),
                    'updated_at'         => now()
                ],

                [
                    'order_serial_no'    => date('dmy') . '7',
                    'user_id'            => 2,
                    'subtotal'           => 66.030000,
                    'tax'                => 14.370000,
                    'discount'           => 0.000000,
                    'delivery_charge'    => 0.000000,
                    'total'              => 80.400000,
                    'order_type'         => OrderType::POS,
                    'order_datetime'     => now(),
                    'payment_method'     => 1,
                    'payment_status'     => PaymentStatus::PAID,
                    'status'             => OrderStatus::DELIVERED,
                    'pos_payment_method' => PosPaymentMethod::CARD,
                    'pos_payment_note'   => '1234',
                    'active'             => Ask::YES,
                    'source'             => Source::POS,
                    'reason'             => NULL,
                    'created_at'         => now(),
                    'updated_at'         => now()
                ],

                [
                    'order_serial_no'    => date('dmy') . '8',
                    'user_id'            => 3,
                    'subtotal'           => 35.000000,
                    'tax'                => 3.500000,
                    'discount'           => 0.000000,
                    'delivery_charge'    => 0.000000,
                    'total'              => 38.500000,
                    'order_type'         => OrderType::POS,
                    'order_datetime'     => now(),
                    'payment_method'     => 1,
                    'payment_status'     => PaymentStatus::PAID,
                    'pos_payment_method' => PosPaymentMethod::MOBILE_BANKING,
                    'pos_payment_note'   => 'BK-TXN12345678',
                    'status'             => OrderStatus::DELIVERED,
                    'active'             => Ask::YES,
                    'source'             => Source::POS,
                    'reason'             => NULL,
                    'created_at'         => now(),
                    'updated_at'         => now()
                ],
            ]);

            Stock::insert([
                [
                    'product_id'      => 4,
                    'model_type'      => Order::class,
                    'model_id'        => 6,
                    'item_type'       => Product::class,
                    'item_id'         => 4,
                    'variation_names' => Null,
                    'sku'             => '133656',
                    'price'           => 19.000000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 19.000000,
                    'tax'             => 0.950000,
                    'total'           => 19.950000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
                [
                    'product_id'      => 5,
                    'model_type'      => Order::class,
                    'model_id'        => 6,
                    'item_type'       => Product::class,
                    'item_id'         => 5,
                    'variation_names' => Null,
                    'sku'             => '1695535',
                    'price'           => 33.250000,
                    'quantity'        => -2,
                    'discount'        => 0.000000,
                    'subtotal'        => 66.500000,
                    'tax'             => 6.650000,
                    'total'           => 73.150000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
                [
                    'product_id'      => 7,
                    'model_type'      => Order::class,
                    'model_id'        => 7,
                    'item_type'       => Product::class,
                    'item_id'         => 7,
                    'variation_names' => Null,
                    'sku'             => '4445732',
                    'price'           => 42.750000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 42.750000,
                    'tax'             => 8.550000,
                    'total'           => 51.300000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],

                [
                    'product_id'      => 8,
                    'model_type'      => Order::class,
                    'model_id'        => 7,
                    'item_type'       => Product::class,
                    'item_id'         => 8,
                    'variation_names' => Null,
                    'sku'             => '4445289',
                    'price'           => 23.280000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 23.280000,
                    'tax'             => 5.820000,
                    'total'           => 29.100000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],


                [
                    'product_id'      => 46,
                    'model_type'      => Order::class,
                    'model_id'        => 8,
                    'item_type'       => Product::class,
                    'item_id'         => 46,
                    'variation_names' => Null,
                    'sku'             => '0050137',
                    'price'           => 35.000000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 35.000000,
                    'tax'             => 3.500000,
                    'total'           => 38.500000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]

            ]);

            StockTax::insert([
                [
                    'stock_id'   => 450,
                    'product_id' => 4,
                    'tax_id'     => 5,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 0.950000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 451,
                    'product_id' => 5,
                    'tax_id'     => 7,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 3.325000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 452,
                    'product_id' => 7,
                    'tax_id'     => 10,
                    'name'       => 'VAT-20',
                    'code'       => 'VAT-20%',
                    'tax_rate'   => 20.000000,
                    'tax_amount' => 8.550000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 453,
                    'product_id' => 8,
                    'tax_id'     => 11,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 1.164000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 453,
                    'product_id' => 8,
                    'tax_id'     => 12,
                    'name'       => 'VAT-20',
                    'code'       => 'VAT-20%',
                    'tax_rate'   => 20.000000,
                    'tax_amount' => 4.656000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 454,
                    'product_id' => 46,
                    'tax_id'     => 78,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 3.500000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],


            ]);
        }
    }
}
