<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"                            => $this->id,
            "name"                          => $this->name,
            "governorate_name"              => $this->governorate_name,
            "email"                         => $this->email === null ? '' : $this->email,
            "phone"                         => $this->phone === null ? '' : $this->phone,
            "latitude"                      => $this->latitude === null ? '' : $this->latitude,
            "longitude"                     => $this->longitude === null ? '' : $this->longitude,
            "address"                       => $this->address,
            "delivery_radius_kilometer"     => $this->delivery_radius_kilometer,
            "delivery_charge_per_kilo"      => AppLibrary::flatAmountFormat($this->delivery_charge_per_kilo),
            "delivery_fee"                  => AppLibrary::flatAmountFormat($this->delivery_fee),
            "currency_delivery_fee"         => AppLibrary::currencyAmountFormat($this->delivery_fee),
            "minimum_order_amount"          => AppLibrary::flatAmountFormat($this->minimum_order_amount),
            "currency_minimum_order_amount" => AppLibrary::currencyAmountFormat($this->minimum_order_amount),
            "status"                        => $this->status,

        ];
    }
}
