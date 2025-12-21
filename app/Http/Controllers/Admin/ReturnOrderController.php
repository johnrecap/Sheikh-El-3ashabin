<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReturnOrdersExport;
use Exception;
use App\Models\ReturnOrder;
use App\Services\ReturnOrderService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ReturnOrderRequest;
use App\Http\Resources\ReturnOrderResource;
use App\Http\Resources\ReturnOrderDetailsResource;

class ReturnOrderController extends AdminController
{

    public ReturnOrderService $returnOrderService;

    public function __construct( ReturnOrderService $returnOrderService)
    {
        parent::__construct();
        $this->returnOrderService = $returnOrderService;
        $this->middleware(['permission:return-orders'])->only('export', 'downloadAttachment');
        $this->middleware(['permission:return_order_create'])->only('store');
        $this->middleware(['permission:return_order_edit'])->only('edit', 'update');
        $this->middleware(['permission:return_order_delete'])->only('destroy');
        $this->middleware(['permission:return_order_show'])->only('show');
    }

    public function index(PaginateRequest $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return  ReturnOrderResource::collection($this->returnOrderService->list($request));
        } catch(Exception $exception){
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function store(ReturnOrderRequest $request): \Illuminate\Http\Response|\Illuminate\Foundation\Application|ReturnOrderResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ReturnOrderResource($this->returnOrderService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function show(ReturnOrder $returnOrder): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ReturnOrderDetailsResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ReturnOrderDetailsResource($this->returnOrderService->show($returnOrder));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function edit(ReturnOrder $returnOrder): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ReturnOrderDetailsResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ReturnOrderDetailsResource($this->returnOrderService->edit($returnOrder));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function update(ReturnOrderRequest $request, ReturnOrder $returnOrder): \Illuminate\Http\Response|\Illuminate\Foundation\Application|ReturnOrderResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ReturnOrderResource($this->returnOrderService->update($request,$returnOrder));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function destroy(ReturnOrder $returnOrder): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->returnOrderService->destroy($returnOrder);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function export(PaginateRequest $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ReturnOrdersExport ($this->returnOrderService, $request), 'ReturnOrders.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function downloadAttachment(ReturnOrder $returnOrder)
    {
        try {
            return $this->returnOrderService->downloadAttachment($returnOrder);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
