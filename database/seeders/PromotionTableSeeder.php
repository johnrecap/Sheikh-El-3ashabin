<?php

namespace Database\Seeders;

use App\Enums\PromotionType;
use App\Enums\Status;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Promotion;
use App\Models\PromotionProduct;
use App\Services\ProductService;
use Dipokhalder\EnvEditor\Exceptions\EnvException;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;

class PromotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $groceryPromotions = [
        [
            'name'        => 'Collected From Garden',
            'type'        => PromotionType::SMALL,
            'category_id' => 1,
        ],
        [
            'name'        => 'Seasonal Fruits',
            'type'        => PromotionType::SMALL,
            'category_id' => 2,
        ],
        [
            'name'        => 'Special Offer On Seafood',
            'type'        => PromotionType::SMALL,
            'category_id' => 3,
        ],
        [
            'name'        => 'Buy Fresh & Organic Sea Food',
            'type'        => PromotionType::BIG,
            'category_id' => null,
        ],
    ];

    /**
     * @throws EnvException
     */
    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            $productService = new ProductService();

            foreach ($this->groceryPromotions as $groceryPromotion) {
                $groceryPromotionObject = Promotion::create([
                    'name'   => $groceryPromotion['name'],
                    'slug'   => Str::slug($groceryPromotion['name']),
                    'type'   => $groceryPromotion['type'],
                    'status' => Status::ACTIVE,
                ]);

                if (file_exists(public_path('/images/seeder/promotion/' . strtolower(str_replace(' ', '_', $groceryPromotion['name'])) . '.jpg'))) {
                    $groceryPromotionObject->addMedia(public_path('/images/seeder/promotion/' . strtolower(str_replace(' ', '_', $groceryPromotion['name'])) . '.jpg'))->preservingOriginal()->toMediaCollection('promotion');
                }

                if($groceryPromotion['category_id']) {
                    $category = ProductCategory::where(['id' =>  $groceryPromotion['category_id']])->first();
                    $products = $productService->ancestorCategoryWiseProducts($category, 35);
                } else {
                    $products = Product::select('id')->inRandomOrder()->limit(35)->get();
                }

                foreach ($products as $product) {
                    PromotionProduct::create([
                        'promotion_id' => $groceryPromotionObject->id,
                        'product_id'   => $product->id,
                    ]);
                }
            }
        }
    }
}
