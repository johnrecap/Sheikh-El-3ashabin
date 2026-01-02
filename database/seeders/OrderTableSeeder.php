<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\Source;
use App\Enums\Status;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTax;
use App\Models\Transaction;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderTableSeeder extends Seeder
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
                    'order_serial_no'  => date('dmy') . '1',
                    'user_id'          => 3,
                    'subtotal'         => 11.760000,
                    'tax'              => 1.764000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.000000,
                    'total'            => 23.524000,
                    'order_type'       => OrderType::DELIVERY,
                    'order_datetime'   => now(),
                    'payment_method'   => 4,
                    'payment_status'   => PaymentStatus::PAID,
                    'status'           => OrderStatus::DELIVERED,
                    'delivery_boy_id'  => 10,
                    'delivery_zone_id' => 1,
                    'active'           => Ask::YES,
                    'source'           => Source::WEB,
                    'reason'           => NULL,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],

                [
                    'order_serial_no'  => date('dmy') . '2',
                    'user_id'          => 3,
                    'subtotal'         => 152.000000,
                    'tax'              => 28.025000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.000000,
                    'total'            => 190.025000,
                    'order_type'       => OrderType::DELIVERY,
                    'order_datetime'   => now(),
                    'payment_method'   => 1,
                    'payment_status'   => PaymentStatus::PAID,
                    'status'           => OrderStatus::DELIVERED,
                    'delivery_boy_id'  => 10,
                    'delivery_zone_id' => 1,
                    'active'           => Ask::YES,
                    'source'           => Source::WEB,
                    'reason'           => NULL,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],


                [
                    'order_serial_no'  => date('dmy') . '3',
                    'user_id'          => 3,
                    'subtotal'         => 720.000000,
                    'tax'              => 36.000000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.000000,
                    'total'            => 766.000000,
                    'order_type'       => OrderType::DELIVERY,
                    'order_datetime'   => now(),
                    'payment_method'   => 1,
                    'payment_status'   => PaymentStatus::UNPAID,
                    'status'           => OrderStatus::ON_THE_WAY,
                    'delivery_boy_id'  => 10,
                    'delivery_zone_id' => 1,
                    'active'           => Ask::YES,
                    'source'           => Source::WEB,
                    'reason'           => NULL,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],

                [
                    'order_serial_no'  => date('dmy') . '4',
                    'user_id'          => 3,
                    'subtotal'         => 33.250000,
                    'tax'              => 3.325000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.000000,
                    'total'            => 41.575000,
                    'order_type'       => OrderType::DELIVERY,
                    'order_datetime'   => now(),
                    'payment_method'   => 4,
                    'payment_status'   => PaymentStatus::PAID,
                    'status'           => OrderStatus::RETURNED,
                    'delivery_boy_id'  => 10,
                    'delivery_zone_id' => 1,
                    'active'           => Ask::YES,
                    'source'           => Source::WEB,
                    'reason'           => 'Product returned. Order amount pay via cash. Diapers size worng.',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],

                [
                    'order_serial_no'  => date('dmy') . '5',
                    'user_id'          => 3,
                    'subtotal'         => 17.460000,
                    'tax'              => 4.365000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.000000,
                    'total'            => 31.825000,
                    'order_type'       => OrderType::DELIVERY,
                    'order_datetime'   => now(),
                    'payment_method'   => 1,
                    'payment_status'   => PaymentStatus::UNPAID,
                    'status'           => OrderStatus::PENDING,
                    'delivery_boy_id'  => null,
                    'delivery_zone_id' => 1,
                    'active'           => Ask::YES,
                    'source'           => Source::WEB,
                    'reason'           => NULL,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
            ]);

            Stock::insert([

                [
                    'product_id'      => 3,
                    'model_type'      => Order::class,
                    'model_id'        => 1,
                    'item_type'       => Product::class,
                    'item_id'         => 132,
                    'variation_names' => Null,
                    'sku'             => '3826597',
                    'price'           => 11.760000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 11.760000,
                    'tax'             => 1.760000,
                    'total'           => 13.524000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],

                [
                    'product_id'      => 55,
                    'model_type'      => Order::class,
                    'model_id'        => 2,
                    'item_type'       => Product::class,
                    'item_id'         => 55,
                    'variation_names' => Null,
                    'sku'             => '3118266734',
                    'price'           => 42.750000,
                    'quantity'        => -2,
                    'discount'        => 0.000000,
                    'subtotal'        => 85.500000,
                    'tax'             => 21.380000,
                    'total'           => 106.875000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],

                [
                    'product_id'      => 366,
                    'model_type'      => Order::class,
                    'model_id'        => 2,
                    'item_type'       => Product::class,
                    'item_id'         => 366,
                    'variation_names' => Null,
                    'sku'             => '325702',
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
                    'product_id'      => 212,
                    'model_type'      => Order::class,
                    'model_id'        => 3,
                    'item_type'       => Product::class,
                    'item_id'         => 212,
                    'variation_names' => Null,
                    'sku'             => '041747',
                    'price'           => 180.000000,
                    'quantity'        => -4,
                    'discount'        => 0.000000,
                    'subtotal'        => 720.000000,
                    'tax'             => 36.000000,
                    'total'           => 756.000000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],

                [
                    'product_id'      => 366,
                    'model_type'      => Order::class,
                    'model_id'        => 4,
                    'item_type'       => Product::class,
                    'item_id'         => 366,
                    'variation_names' => Null,
                    'sku'             => '325702',
                    'price'           => 33.250000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 33.250000,
                    'tax'             => 3.325000,
                    'total'           => 36.575000,
                    'status'          => Status::ACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],

                [
                    'product_id'      => 250,
                    'model_type'      => Order::class,
                    'model_id'        => 5,
                    'item_type'       => Product::class,
                    'item_id'         => 250,
                    'variation_names' => Null,
                    'sku'             => '291931',
                    'price'           => 17.460000,
                    'quantity'        => -1,
                    'discount'        => 0.000000,
                    'subtotal'        => 17.460000,
                    'tax'             => 4.370000,
                    'total'           => 21.825000,
                    'status'          => Status::INACTIVE,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ],
            ]);

            StockTax::insert([

                [
                    'stock_id'   => 444,
                    'product_id' => 132,
                    'tax_id'     => 228,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 0.588000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 444,
                    'product_id' => 132,
                    'tax_id'     => 229,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 1.176000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],


                [
                    'stock_id'   => 445,
                    'product_id' => 55,
                    'tax_id'     => 93,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 2.137500,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 445,
                    'product_id' => 55,
                    'tax_id'     => 94,
                    'name'       => 'VAT-20',
                    'code'       => 'VAT-20%',
                    'tax_rate'   => 20.000000,
                    'tax_amount' => 8.550000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 446,
                    'product_id' => 366,
                    'tax_id'     => 612,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 3.325000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'stock_id'   => 447,
                    'product_id' => 212,
                    'tax_id'     => 354,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 9.000000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 448,
                    'product_id' => 366,
                    'tax_id'     => 612,
                    'name'       => 'VAT-10',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10.000000,
                    'tax_amount' => 3.325000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 449,
                    'product_id' => 250,
                    'tax_id'     => 420,
                    'name'       => 'VAT-5',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5.000000,
                    'tax_amount' => 0.873000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'stock_id'   => 449,
                    'product_id' => 250,
                    'tax_id'     => 420,
                    'name'       => 'VAT-20',
                    'code'       => 'VAT-20%',
                    'tax_rate'   => 20.000000,
                    'tax_amount' => 3.492000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);


            OrderAddress::insert([
                [
                    'order_id'     => 1,
                    'user_id'      => 3,
                    'label'        => "Home",
                    'address'      => "Dhaka Bangladesh",
                    'apartment'    => "House :30, Road: 13, Block: A, Mirpur 2",
                    'latitude'     => "23.803630739519967",
                    'longitude'    => "90.35418099725523",
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'order_id'     => 2,
                    'user_id'      => 3,
                    'label'        => "Home",
                    'address'      => "Dhaka Bangladesh",
                    'apartment'    => "House :30, Road: 13, Block: A, Mirpur 2",
                    'latitude'     => "23.803630739519967",
                    'longitude'    => "90.35418099725523",
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'order_id'     => 3,
                    'user_id'      => 3,
                    'label'        => "Home",
                    'address'      => "Dhaka Bangladesh",
                    'apartment'    => "House :30, Road: 13, Block: A, Mirpur 2",
                    'latitude'     => "23.803630739519967",
                    'longitude'    => "90.35418099725523",
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'order_id'     => 4,
                    'user_id'      => 3,
                    'label'        => "Home",
                    'address'      => "Dhaka Bangladesh",
                    'apartment'    => "House :30, Road: 13, Block: A, Mirpur 2",
                    'latitude'     => "23.803630739519967",
                    'longitude'    => "90.35418099725523",
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'order_id'     => 5,
                    'user_id'      => 3,
                    'label'        => "Home",
                    'address'      => "Dhaka Bangladesh",
                    'apartment'    => "House :30, Road: 13, Block: A, Mirpur 2",
                    'latitude'     => "23.803630739519967",
                    'longitude'    => "90.35418099725523",
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
            ]);

            Transaction::insert([
                [
                    'sign'           => '+',
                    'order_id'       => 1,
                    'transaction_no' => 'txn_3PWBCmGAN8SIuxA71OjUxDeJ',
                    'amount'         => 23.524000,
                    'payment_method' => 'stripe',
                    'type'           => 'payment',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ],
                [
                    'sign'           => '+',
                    'order_id'       => 4,
                    'transaction_no' => 'txn_3PWBCmGAN8SIuxA71OjUxDeJ',
                    'amount'         => 41.575000,
                    'payment_method' => 'stripe',
                    'type'           => 'payment',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ],
            ]);
        }
    }
}
