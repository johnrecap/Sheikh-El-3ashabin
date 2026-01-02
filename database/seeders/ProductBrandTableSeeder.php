<?php

namespace Database\Seeders;

use App\Enums\Status;
use Illuminate\Support\Str;
use App\Models\ProductBrand;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;

class ProductBrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $brands = [
        'Dove',
        'Great Value',
        'Nature Valley',
        'Oxi Clean',
        'Pampers',
        'Suave',
        'Purell',
        'Johnson'
    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->brands as $brand) {
                $productBand = ProductBrand::create([
                    'name'        => $brand,
                    'slug'        => Str::slug($brand),
                    'description' => null,
                    'status'      => Status::ACTIVE,
                ]);

                if (file_exists(public_path('/images/seeder/brand/'. strtolower(str_replace(' ', '_', $brand)) . '.png'))) {
                    $productBand->addMedia(public_path('/images/seeder/brand/' . strtolower(str_replace(' ', '_', $brand)) . '.png'))->preservingOriginal()->toMediaCollection('product-brand');
                }
            }
        }
    }
}
