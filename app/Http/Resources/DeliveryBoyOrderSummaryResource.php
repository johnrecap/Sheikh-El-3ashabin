<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryBoyOrderSummaryResource extends JsonResource
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
            "delivered" => $this->info['delivered'],
            "returned"  => $this->info['returned'],
        ];
    }
}
