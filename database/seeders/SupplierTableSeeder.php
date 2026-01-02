<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;


class SupplierTableSeeder extends Seeder
{
    public array $grocerySuppliers = [
        [
            'company'      => 'Sysco Corporation',
            'name'         => 'Mr. Raj & DK',
            'email'        => 'rajdk@example.com',
            'country_code' => '+880',
            'phone'        => '12548723456',
            'address'      => 'Dhaka Bangladesh'
        ],
        [
            'company'      => 'McLane Company',
            'name'         => 'Md. Smith Pio',
            'email'        => 'smith@example.com',
            'country_code' => '+880',
            'phone'        => '12548797653',
            'address'      => 'Dhaka Bangladesh'
        ],
    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->grocerySuppliers as $grocerySupplier) {
                Supplier::create([
                    'company'      => $grocerySupplier['company'],
                    'name'         => $grocerySupplier['name'],
                    'email'        => $grocerySupplier['email'],
                    'country_code' => $grocerySupplier['country_code'],
                    'phone'        => $grocerySupplier['phone'],
                    'address'      => $grocerySupplier['address'],
                ]);
            }
        }
    }
}
