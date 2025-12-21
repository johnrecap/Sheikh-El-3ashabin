<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\ProductAttribute;
use App\Services\ProductAttributeService;
use App\Http\Requests\ProductAttributeRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ProductAttributeResource;

class ProductAttributeController extends AdminController
{

    public ProductAttributeService $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        parent::__construct();
        $this->productAttributeService = $productAttributeService;
         $this->middleware(['permission:settings'])->only('show', 'store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ProductAttributeResource::collection($this->productAttributeService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(ProductAttribute $productAttribute): \Illuminate\Http\Response|ProductAttributeResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeResource($this->productAttributeService->show($productAttribute));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ProductAttributeRequest $request): \Illuminate\Http\Response|ProductAttributeResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeResource($this->productAttributeService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(ProductAttributeRequest $request, ProductAttribute        $productAttribute): \Illuminate\Http\Response|ProductAttributeResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAttributeResource($this->productAttributeService->update($request, $productAttribute));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(ProductAttribute $productAttribute): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->productAttributeService->destroy($productAttribute);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
