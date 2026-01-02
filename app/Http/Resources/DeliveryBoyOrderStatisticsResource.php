<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryBoyOrderStatisticsResource extends JsonResource
{
    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    public function toArray($request)
    {
        return [
            "total_order"      => $this->info['total_order'],
            "delivered"        => $this->info['delivered'],
            "returned"         => $this->info['returned'],
            "out_for_delivery" => $this->info['out_for_delivery'],
        ];
    }
}
