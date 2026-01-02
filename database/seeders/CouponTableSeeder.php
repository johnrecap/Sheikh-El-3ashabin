<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Enums\TaxType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;


class CouponTableSeeder extends Seeder
{

    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            $coupons = [
                [
                    'name'             => 'Fresh Produce Frenzy',
                    'code'             => 'fresh15',
                    'discount'         => '15.00',
                    'discount_type'    => TaxType::PERCENTAGE,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(365),
                    'minimum_order'    => '50.00',
                    'maximum_discount' => '15.00',
                    'limit_per_user'   => '5',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'name'             => 'Pantry Saver Special',
                    'code'             => 'pantry25',
                    'discount'         => '25.00',
                    'discount_type'    => TaxType::FIXED,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(365),
                    'minimum_order'    => '100.00',
                    'maximum_discount' => '25.00',
                    'limit_per_user'   => '5',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'name'             => 'Weekly Essentials',
                    'code'             => 'weekly10',
                    'discount'         => '10.00',
                    'discount_type'    => TaxType::PERCENTAGE,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(180),
                    'minimum_order'    => '300.00',
                    'maximum_discount' => '50.00',
                    'limit_per_user'   => '3',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'name'             => 'Bulk Buy Bonus',
                    'code'             => 'bulk50',
                    'discount'         => '50.00',
                    'discount_type'    => TaxType::FIXED,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(90),
                    'minimum_order'    => '200.00',
                    'maximum_discount' => '50.00',
                    'limit_per_user'   => '2',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ]
            ];            
            foreach ($coupons as $coupon) {
                $couponObject = Coupon::create($coupon);
                if (file_exists(public_path('/images/seeder/coupon/' . strtolower(str_replace(' ', '_', $coupon['code'])) . '.png'))) {
                    $couponObject->addMedia(public_path('/images/seeder/coupon/' . strtolower(str_replace(' ', '_', $coupon['code'])) . '.png'))->preservingOriginal()->toMediaCollection('coupon');
                }
            }
        }
    }
}
