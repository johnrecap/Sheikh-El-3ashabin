<?php

namespace App\Exports;

use App\Libraries\AppLibrary;
use App\Services\OrderHistoryService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderHistoryExport implements FromCollection, WithHeadings
{

    public OrderHistoryService $orderHistoryService;
    public PaginateRequest $request;

    public function __construct(OrderHistoryService $orderHistoryService, $request)
    {
        $this->orderHistoryService = $orderHistoryService;
        $this->request      = $request;
    }

    public function collection()
    {
        $orderArray  = [];
        $ordersArray = $this->orderHistoryService->list($this->request);

        foreach ($ordersArray as $order) {
            $orderArray[] = [
                $order->order_serial_no,
                trans('orderType.' . $order->order_type),
                optional($order->user)->name,
                AppLibrary::flatAmountFormat($order->total),
                AppLibrary::datetime($order->order_datetime),
                trans('source.' . $order->source),
                trans('orderStatus.' . $order->status),
            ];
        }
        return collect($orderArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.order_serial_no'),
            trans('all.label.order_type'),
            trans('all.label.customer'),
            trans('all.label.amount'),
            trans('all.label.date'),
            trans('all.label.source'),
            trans('all.label.status'),
        ];
    }
}