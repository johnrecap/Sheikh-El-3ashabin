<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\ProductService;
use App\Exports\ProductsReportExport;
use App\Http\Resources\ProductAdminResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;

class ProductsReportController extends AdminController
{

    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->middleware(['permission:products-report'])->only('index', 'export');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ProductAdminResource::collection($this->productService->productReport($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ProductsReportExport($this->productService, $request), 'Product-Report.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
