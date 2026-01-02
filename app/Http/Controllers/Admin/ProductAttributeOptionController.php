<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductAttributeOptionRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ProductAttributeOptionResource;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Services\ProductAttributeOptionService;
use Exception;

class ProductAttributeOptionController extends AdminController
{
    private ProductAttributeOptionService $productAttributeOptionService;

    public function __construct(ProductAttributeOptionService $productAttributeOptionService)
    {
        parent::__construct();
        $this->productAttributeOptionService = $productAttributeOptionService;
        $this->middleware(['permission:settings'])->only('index', 'store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request, ProductAttribute $productAttribute): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ProductAttributeOptionResource::collection($this->productAttributeOptionService->list($request, $productAttribute));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ProductAttributeOptionRequest $request, ProductAttribute $productAttribute): \Illuminate\Http\Response | ProductAttributeOptionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeOptionResource($this->productAttributeOptionService->store($request, $productAttribute));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(ProductAttributeOptionRequest $request, ProductAttribute $productAttribute, ProductAttributeOption $productAttributeOption): \Illuminate\Http\Response | ProductAttributeOptionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeOptionResource($this->productAttributeOptionService->update($request, $productAttribute, $productAttributeOption));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(ProductAttribute $productAttribute, ProductAttributeOption $productAttributeOption): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->productAttributeOptionService->destroy($productAttribute, $productAttributeOption);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(ProductAttribute $productAttribute, ProductAttributeOption $productAttributeOption): \Illuminate\Http\Response | ProductAttributeOptionResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeOptionResource($this->productAttributeOptionService->show($productAttribute, $productAttributeOption));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}