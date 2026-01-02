<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Outlet;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;

class OutletTableSeeder extends Seeder
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
            Outlet::insert([
                [
                    'delivery_zone_id' => 1,
                    'name'         => 'Mirpur 1',
                    'email'        => 'mirpuroutlet@example.com',
                    'country_code' => '+880',
                    'phone'        => '1325736364',
                    'latitude'     => '23.7956',
                    'longitude'    => '90.3537',
                    'address'      => 'House :31, Road: 9, Block: A, Mirpur 1',
                    'status'       => Status::ACTIVE,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'delivery_zone_id' => 2,
                    'name'             => 'Gulshan 1',
                    'email'            => 'gulshanoneoutlet@example.com',
                    'country_code'     => '+880',
                    'phone'            => '1275362435',
                    'latitude'         => '23.7948597',
                    'longitude'        => '90.4083123',
                    'address'          => "House :20, Road: 19, Block: B, Gulshan 1,Dhaka 1212",
                    'status'           => Status::ACTIVE,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
            ]);
        }
    }
}