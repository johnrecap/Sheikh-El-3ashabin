<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Order;
use App\Services\ActiveOrderService;
use App\Exports\ActiveOrderExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\OrderDetailsResource;

class ActiveOrderController extends AdminController
{
    private ActiveOrderService $activeOrderService;

    public function __construct(ActiveOrderService $activeOrderService)
    {
        parent::__construct();
        $this->activeOrderService = $activeOrderService;
        $this->middleware(['permission:order-history'])->only('index', 'show', 'changeStatus', 'export');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return OrderDetailsResource::collection($this->activeOrderService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Order $order): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->activeOrderService->show($order));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeStatus(Order $order)
    {
        try {
            return new OrderDetailsResource($this->activeOrderService->changeStatus($order));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ActiveOrderExport($this->activeOrderService, $request), 'Order-history.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
