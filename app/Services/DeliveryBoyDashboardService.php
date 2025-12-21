<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Libraries\QueryExceptionLibrary;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Smartisan\Settings\Facades\Settings;

class DeliveryBoyDashboardService
{
    /**
     * @throws Exception
     */

    public function orderOverview()
    {
        try {
            $order = new Order;

            $orderOverviewArray = [];

            $orderOverviewArray["total_order"]     = $order->where('delivery_boy_id', Auth::user()->id)->count();
            $orderOverviewArray["total_delivered"] = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED])->count();
            $orderOverviewArray["total_returned"]  = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED])->count();

            return $orderOverviewArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
    public function orderStatistics(Request $request)
    {
        try {
            $order = new Order;

            if ($request->first_date && $request->last_date) {
                $first_date = Date('Y-m-d', strtotime($request->first_date));
                $last_date  = Date('Y-m-d', strtotime($request->last_date));
            } else {
                $first_date = Carbon::today()->toDateString();
                $last_date  = Carbon::today()->toDateString();
            }

            $orderStatisticsArray = [];

            $orderStatisticsArray["total_order"]      = $order->where('delivery_boy_id', Auth::user()->id)->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["delivered"]        = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["returned"]         = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $orderStatisticsArray["out_for_delivery"] = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::ON_THE_WAY])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();

            return $orderStatisticsArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function orderSummary(Request $request)
    {
        try {
            $order = new Order;
            if ($request->first_date && $request->last_date) {
                $first_date = Date('Y-m-d', strtotime($request->first_date));
                $last_date  = Date('Y-m-d', strtotime($request->last_date));
            } else {
                $first_date = Date('Y-m-01', strtotime(Carbon::today()->toDateString()));
                $last_date  = Date('Y-m-t', strtotime(Carbon::today()->toDateString()));
            }

            $orderSummaryArray = [];

            $total_delivered = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_returned  = $order->where(['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();

            if ($total_delivered > 0) {
                $orderSummaryArray["delivered"] = (int) $total_delivered;
            } else {
                $orderSummaryArray["delivered"] = 0;
            }

            if ($total_returned > 0) {
                $orderSummaryArray["returned"] = (int) $total_returned;
            } else {
                $orderSummaryArray["returned"] = 0;
            }

            return $orderSummaryArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function nextDeliveryOrder()
    {
        try {
            $lastCompletedOrder = $this->getLastCompletedOrder(Auth::user()->id);
            if (!$lastCompletedOrder) {
                $deliveryZone = DeliveryZone::findOrFail(Auth::user()->delivery_zone_id);
                $order = $this->getNextDelivery(Auth::user()->id, $deliveryZone->latitude, $deliveryZone->longitude);
                if ($order) {
                    return $order;
                } else {
                    return [];
                }
            }

            $completedOrderLat = $lastCompletedOrder?->address->lat;
            $completedOrderLng = $lastCompletedOrder?->address->lng;

            $nextOrder = $this->getNextDelivery(Auth::user()->id, $completedOrderLat, $completedOrderLng);
            if (!$nextOrder) {
                return [];
            }
            return $nextOrder;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function getLastCompletedOrder($deliveryBoyId)
    {
        return Order::where('delivery_boy_id', $deliveryBoyId)->where('status', OrderStatus::DELIVERED)->whereDate('updated_at', Carbon::today())->orderBy('updated_at', 'desc')->first();
    }

    public function getNextDelivery($deliveryBoyId, $completedOrderLat, $completedOrderLng)
    {
        $completedOrderLat = (float) $completedOrderLat;
        $completedOrderLng = (float) $completedOrderLng;
        $nextOrder =  Order::with(['paymentMethod', 'user', 'address'])
            ->where('delivery_boy_id', $deliveryBoyId)
            ->where('status', OrderStatus::ON_THE_WAY)
            ->get()
            ->map(function ($order) use ($completedOrderLat, $completedOrderLng) {
                $distance = (6371 * acos(
                    cos(deg2rad($completedOrderLat))
                        * cos(deg2rad($order?->address->latitude))
                        * cos(deg2rad($order?->address->longitude) - deg2rad($completedOrderLng))
                        + sin(deg2rad($completedOrderLat))
                        * sin(deg2rad($order?->address->latitude))
                ));

                $order->setAttribute('distance', $distance);
                return $order;
            })
            ->sortBy('distance')
            ->first();

        return $nextOrder;
    }
}