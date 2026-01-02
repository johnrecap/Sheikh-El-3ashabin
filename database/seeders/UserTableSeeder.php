<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Enums\Role as EnumRole;
use App\Enums\Status;
use App\Models\Address;
use App\Models\User;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $envService = new EnvEditor();

        $admin = User::create([
            'name'              => 'John Doe',
            'email'             => 'admin@example.com',
            'phone'             => '1254875855',
            'username'          => 'admin',
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'status'            => Status::ACTIVE,
            'country_code'      => '+880',
            'delivery_zone_id'  => null,
            'is_guest'          => Ask::NO,
        ]);
        $admin->assignRole((int) EnumRole::ADMIN);

        if ($envService->getValue('DEMO')) {
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 10',
                'latitude'  => '23.8069',
                'longitude' => '90.3687',
                'user_id'   => $admin->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $admin->id,
            ]);
        }
        $customer = User::create([
            'name'              => 'Walking Customer',
            'email'             => 'walkingcustomer@example.com',
            'phone'             => '125444455',
            'username'          => 'default-customer',
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'status'            => Status::ACTIVE,
            'country_code'      => '+880',
            'delivery_zone_id'  => null,
            'is_guest'          => Ask::NO,
        ]);
        $customer->assignRole(EnumRole::CUSTOMER);

        if ($envService->getValue('DEMO')) {
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.7873',
                'longitude' => '90.3514',
                'user_id'   => $customer->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 2',
                'latitude'  => '23.7948',
                'longitude' => '90.4143',
                'user_id'   => $customer->id,
            ]);
        }

        if ($envService->getValue('DEMO')) {
            $customerOne = User::create([
                'name'              => 'Will Smith',
                'email'             => 'customer@example.com',
                'phone'             => '125333344',
                'username'          => 'will-smith',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);

            $customerOne->assignRole(EnumRole::CUSTOMER);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.803630739519967',
                'longitude' => '90.35418099725523',
                'user_id'   => $customerOne->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 1',
                'latitude'  => '23.78125917876206',
                'longitude' => '90.4133013474871',
                'user_id'   => $customerOne->id,
            ]);

            $employeeOne = User::create([
                'name'              => 'Kiron Khan',
                'email'             => 'manager@example.com',
                'phone'             => '1275333453',
                'username'          => 'kiron-khan1313',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);

            $employeeOne->assignRole(EnumRole::MANAGER);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.7873',
                'longitude' => '90.3514',
                'user_id'   => $employeeOne->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $employeeOne->id,
            ]);
            $employeeTwo = User::create([
                'name'              => 'Shohag Ali',
                'email'             => 'shohag@example.com',
                'phone'             => '1257654433',
                'username'          => 'shohag-ali3324',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);
            $employeeTwo->assignRole(EnumRole::MANAGER);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 2',
                'latitude'  => '23.7948',
                'longitude' => '90.4143',
                'user_id'   => $employeeTwo->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 1',
                'latitude'  => '23.7821',
                'longitude' => '90.4161',
                'user_id'   => $employeeTwo->id,
            ]);

            $posOperatorOne = User::create([
                'name'              => 'Farha Israt ',
                'email'             => 'posoperator@example.com',
                'phone'             => '156873641',
                'username'          => 'farha-istat343',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);
            $posOperatorOne->assignRole(EnumRole::POS_OPERATOR);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $posOperatorOne->id,
            ]);


            $posOperatorTwo = User::create([
                'name'              => 'Sahataz Afnan',
                'email'             => 'sahataz@example.com',
                'phone'             => '1249955570',
                'username'          => 'sahataz-afnan232',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);
            $posOperatorTwo->assignRole(EnumRole::POS_OPERATOR);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 2',
                'latitude'  => '23.7948',
                'longitude' => '90.4143',
                'user_id'   => $posOperatorTwo->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 1',
                'latitude'  => '23.7821',
                'longitude' => '90.4161',
                'user_id'   => $posOperatorTwo->id,
            ]);
            $stuffOne = User::create([
                'name'              => 'Rohim Miya',
                'email'             => 'stuff@example.com',
                'phone'             => '1222224443',
                'username'          => 'rohim-miya768',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);
            $stuffOne->assignRole(EnumRole::STUFF);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.7873',
                'longitude' => '90.3514',
                'user_id'   => $stuffOne->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $stuffOne->id,
            ]);

            $stuffTwo = User::create([
                'name'              => 'Kala Chan',
                'email'             => 'kala@example.com',
                'phone'             => '1238426043',
                'username'          => 'kala-chan890',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => null,
                'is_guest'          => Ask::NO,
            ]);
            $stuffTwo->assignRole(EnumRole::STUFF);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 2',
                'latitude'  => '23.7948',
                'longitude' => '90.4143',
                'user_id'   => $stuffTwo->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 1',
                'latitude'  => '23.7821',
                'longitude' => '90.4161',
                'user_id'   => $stuffTwo->id,
            ]);
        }

        if ($envService->getValue('DEMO')) {

            $deliveryBoyOne = User::create([
                'name'              => 'Rafiul Khan',
                'email'             => 'deliveryboy@example.com',
                'phone'             => '1244444333',
                'username'          => 'kawsar-uddin131',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => 1,
                'is_guest'          => Ask::NO,
            ]);
            $deliveryBoyOne->assignRole(EnumRole::DELIVERY_BOY);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.7873',
                'longitude' => '90.3514',
                'user_id'   => $deliveryBoyOne->id,
            ]);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $deliveryBoyOne->id,
            ]);

            $deliveryBoyTwo = User::create([
                'name'              => 'Heron Khan',
                'email'             => 'heron@example.com',
                'phone'             => '1256444333',
                'username'          => 'heron-khan131',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => 1,
                'is_guest'          => Ask::NO,
            ]);
            $deliveryBoyTwo->assignRole(EnumRole::DELIVERY_BOY);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 1',
                'latitude'  => '23.7956',
                'longitude' => '90.3537',
                'user_id'   => $deliveryBoyTwo->id,
            ]);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Mirpur 2',
                'latitude'  => '23.7873',
                'longitude' => '90.3514',
                'user_id'   => $deliveryBoyTwo->id,
            ]);

            $deliveryBoyThree = User::create([
                'name'              => 'Nur Mahmud',
                'email'             => 'nurmahmud@example.com',
                'phone'             => '12555555333',
                'username'          => 'nur-mahmud123',
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'),
                'status'            => Status::ACTIVE,
                'country_code'      => '+880',
                'delivery_zone_id'  => 2,
                'is_guest'          => Ask::NO,
            ]);
            $deliveryBoyThree->assignRole(EnumRole::DELIVERY_BOY);
            Address::create([
                'label'     => 'Home',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 2',
                'latitude'  => '23.7948',
                'longitude' => '90.4143',
                'user_id'   => $deliveryBoyThree->id,
            ]);
            Address::create([
                'label'     => 'Work',
                'address'   => 'Dhaka Bangladesh',
                'apartment' => rand(0, 999) . ', Gulshan 1',
                'latitude'  => '23.7821',
                'longitude' => '90.4161',
                'user_id'   => $deliveryBoyThree->id,
            ]);
        }
    }
}