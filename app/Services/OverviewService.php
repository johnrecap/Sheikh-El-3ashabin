<?php

namespace App\Services;

use Exception;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Libraries\AppLibrary;
use App\Libraries\QueryExceptionLibrary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OverviewService
{
    public function totalOrders()
    {
        try {
            return Order::where('user_id', Auth::user()->id)->count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function totalCompletedOrders()
    {
        try {
            return Order::where('user_id', Auth::user()->id)->where('status', OrderStatus::DELIVERED)->count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function totalReturnedOrders()
    {
        try {
            return Order::where('user_id', Auth::user()->id)->where('status', OrderStatus::RETURNED)->count();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function walletBalance()
    {
        try {
            return AppLibrary::currencyAmountFormat(Auth::user()->balance);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}