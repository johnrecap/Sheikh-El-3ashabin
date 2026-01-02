<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Services\DeliveryBoyDashboardService;
use App\Http\Resources\DeliveryBoyOrderSummaryResource;
use App\Http\Resources\DeliveryBoyOrderOverviewResource;
use App\Http\Resources\DeliveryBoyOrderStatisticsResource;

class DeliveryBoyDashboardController extends AdminController
{
    private OrderService $orderService;
    private  DeliveryBoyDashboardService $deliveryBoyDashboardService;

    public function __construct(OrderService $orderService, DeliveryBoyDashboardService $deliveryBoyDashboardService)
    {
        parent::__construct();
        $this->orderService = $orderService;
        $this->deliveryBoyDashboardService = $deliveryBoyDashboardService;
        $this->middleware(['permission:dashboard'])->only(
            'orderOverview',
            'orderStatistics',
            'activeOrder',
            'activeOrderShow',
            'changeStatus',
            'nextDeliveryOrder'
        );
    }

    public function orderOverview(): \Illuminate\Http\Response | DeliveryBoyOrderOverviewResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DeliveryBoyOrderOverviewResource($this->deliveryBoyDashboardService->orderOverview());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orderStatistics(
        Request $request
    ): \Illuminate\Http\Response | DeliveryBoyOrderStatisticsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new DeliveryBoyOrderStatisticsResource($this->deliveryBoyDashboardService->orderStatistics($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orderSummary(
        Request $request
    ): \Illuminate\Http\Response | DeliveryBoyOrderSummaryResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new DeliveryBoyOrderSummaryResource($this->deliveryBoyDashboardService->orderSummary($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function activeOrder(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return OrderDetailsResource::collection($this->orderService->deliveryBoyOrder($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function activeOrderShow(Order $order): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->deliveryBoyOrderDetails($order));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeStatus(Order $order)
    {
        try {
            return new OrderDetailsResource($this->orderService->deliveryBoyOrderChangeStatus($order));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function nextDeliveryOrder(): \Illuminate\Http\Response | array|OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $order = $this->deliveryBoyDashboardService->nextDeliveryOrder();
            if ($order) {
                return new OrderDetailsResource($order);
            } else {
                return ['data' => []];
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}