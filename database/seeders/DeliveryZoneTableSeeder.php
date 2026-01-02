<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use App\Enums\Status;
use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class DeliveryZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryZone::create([
            'name'                      => 'Mirpur-1',
            'email'                     => 'mirpur@inilabs.xyz',
            'phone'                     => '+8801464646464',
            'latitude'                  => '23.8042375',
            'longitude'                 => '90.3525979',
            'address'                   => 'House : 25, Road No: 2, Block A, Mirpur-1, Dhaka 1216',
            'delivery_radius_kilometer' => 5,
            'delivery_charge_per_kilo'  => 2,
            'minimum_order_amount'      => 30,
            'status'                    => Status::ACTIVE,
        ]);

        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            DeliveryZone::create([
                'name'                      => 'Gulshan-1',
                'email'                     => 'gulshan@inilabs.xyz',
                'phone'                     => '+8801243535366',
                'latitude'                  => '23.7948597',
                'longitude'                 => '90.4083123',
                'address'                   => '1st floor, Adam Building, House: 41 Road: 52, Dhaka 1212',
                'delivery_radius_kilometer' => 5,
                'delivery_charge_per_kilo'  => 2,
                'minimum_order_amount'      => 50,
                'status'                    => Status::ACTIVE,
            ]);
        }
    }
}