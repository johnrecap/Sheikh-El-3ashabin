<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\ProductCategory;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $categories = [
        [
            'name' => 'Food',
            'children' => [
                [
                    'name' => 'Fruits & Vegetables',
                    'children' => [
                        ['name' => 'Fresh Vegetables'],
                        ['name' => 'Fresh Fruits'],
                        ['name' => 'Nuts & Dry Fruits'],
                    ],
                ],
                [
                    'name' => 'Meat & Fish',
                    'children' => [
                        ['name' => 'Chicken'],
                        ['name' => 'Beef'],
                        ['name' => 'Frozen Fish'],
                    ],
                ],
                [
                    'name' => 'Sauces & Pickles',
                    'children' => [
                        ['name' => 'Tomato Sauce'],
                        ['name' => 'Pickles'],
                        ['name' => 'Cooking Sauce'],
                        ['name' => 'Other Table Sauce'],
                    ],
                ],
                [
                    'name' => 'Candy & Chocolates',
                    'children' => [
                        ['name' => 'Chocolates'],
                        ['name' => 'Wafers'],
                        ['name' => 'Candies'],
                        ['name' => 'Gums & Mint'],
                    ],
                ],
                [
                    'name' => 'Beverages',
                    'children' => [
                        ['name' => 'Soft Drinks'],
                        ['name' => 'Tea'],
                        ['name' => 'Coffee'],
                        ['name' => 'Juice'],
                    ],
                ],
            ],
        ],
        [
            'name' => 'Cleaning Supplies',
            'children' => [
                [
                    'name' => 'Dishwashing Supplies',
                ],
                [
                    'name' => 'Laundry',
                ],
                [
                    'name' => 'Toilet Cleaners',
                ],
                [
                    'name' => 'Napkin & Paper Products',
                ],
                [
                    'name' => 'Floor & Glass Cleaners',
                ],
                [
                    'name' => 'Air Fresheners',
                ],
            ],
        ],

        [
            'name' => 'Personal Care',
            'children' => [
                [
                    'name' => 'Women’s Care',
                    'children' => [
                        ['name' => 'Women’s Soap'],
                        ['name' => 'Serum & Toner'],
                        ['name' => 'Shampoo & Conditioner'],
                        ['name' => 'Female Moisturizer'],
                        ['name' => 'Women’s Perfume'],
                    ],
                ],
                [
                    'name' => 'Men’s Care',
                    'children' => [
                        ['name' => 'Men’s Soap'],
                        ['name' => 'Men’s Perfume'],
                        ['name' => 'Shampoo & Conditioner'],
                        ['name' => 'Razors & Blades'],
                        ['name' => 'Men’s Facewash'],
                    ],
                ],
                [
                    'name' => 'Handwash',
                    'children' => [
                        ['name' => 'Liquid Handwash'],
                        ['name' => 'Hand Sanitizer'],
                    ],
                ],
                [
                    'name' => 'Oral Care',
                    'children' => [
                        ['name' => 'Toothpastes'],
                        ['name' => 'Toothbrushes'],
                    ],

                ],
            ],
        ],
        [
            'name' => 'Health & Wellness',
            'children' => [
                [
                    'name' => 'Keto Food',
                ],
                [
                    'name' => 'Antiseptics',
                ],
                [
                    'name' => 'Food Supplements',
                ],
                [
                    'name' => 'Herbal & Digestive Aids',
                ],
            ],
        ],
        [
            'name' => 'Baby Care',
            'children' => [
                [
                    'name' => 'Diapers',
                ],
                [
                    'name' => 'Baby Foods',
                ],
                [
                    'name' => 'Baby Skincare',
                ],
                [
                    'name' => 'Baby Oral Care',
                ],
                [
                    'name' => 'Newborn Essentials',
                ],
            ],
        ],
        [
            'name' => 'Home & Kitchen',
            'children' => [
                [
                    'name' => 'Kitchen Accessories',
                ],
                [
                    'name' => 'Kitchen Appliances',
                ],
                [
                    'name' => 'Tools & Hardware',
                ],
                [
                    'name' => 'Lights & Electrical',
                ],
            ],
        ],

    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->categories as $category) {
                $productCategory = ProductCategory::create([
                    'parent_id' => null,
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name'] . rand(1, 100)),
                    'description' => null,
                    'status' => Status::ACTIVE,
                ]);
                if (file_exists(public_path('/images/seeder/product-category/' . strtolower(str_replace(' ', '_', $category['name'])) . '.png'))) {
                    $productCategory->addMedia(public_path('/images/seeder/product-category/' . strtolower(str_replace(' ', '_', $category['name'])) . '.png'))->preservingOriginal()->toMediaCollection('product-category');
                }

                if (isset($category['children']) && count($category['children']) > 0) {
                    $this->nested($category['children'], $productCategory->id);
                }
            }
        }
    }

    public function nested($arrays, $id = null): void
    {
        foreach ($arrays as $array) {
            $productCategory = ProductCategory::create([
                'parent_id' => $id,
                'name' => $array['name'],
                'slug' => Str::slug($array['name'] . rand(101, 500)) . rand(100, 200),
                'description' => null,
                'status' => Status::ACTIVE,
            ]);
            if (file_exists(public_path('/images/seeder/product-category/' . strtolower(str_replace(' ', '_', $array['name'])) . '.png'))) {
                $productCategory->addMedia(public_path('/images/seeder/product-category/'. strtolower(str_replace(' ', '_', $array['name'])) . '.png'))->preservingOriginal()->toMediaCollection('product-category');
            }
            if (isset($array['children']) && count($array) > 0) {
                $this->nested($array['children'], $productCategory->id);
            }
        }
    }
}
