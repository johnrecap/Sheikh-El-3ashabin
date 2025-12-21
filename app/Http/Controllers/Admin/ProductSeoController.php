<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\Product;
use App\Services\ProductSeoService;
use App\Http\Requests\ProductSeoRequest;
use App\Http\Resources\ProductSeoResource;

class ProductSeoController extends AdminController
{
    private ProductSeoService $productSeoService;

    public function __construct(ProductSeoService $productSeoService)
    {
        parent::__construct();
        $this->productSeoService = $productSeoService;
        $this->middleware(['permission:products_show'])->only('index', 'update');
    }

    public function index(Product $product): \Illuminate\Http\Response | ProductSeoResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductSeoResource($this->productSeoService->list($product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ProductSeoRequest $request, Product $product): \Illuminate\Http\Response | ProductSeoResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductSeoResource($this->productSeoService->update($request, $product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
