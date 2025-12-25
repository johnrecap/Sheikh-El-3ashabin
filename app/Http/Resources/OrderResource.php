<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'                   => $this->id,
            'order_serial_no'      => $this->order_serial_no,
            'user_id'              => $this->user_id,
            "total_amount_price"   => AppLibrary::flatAmountFormat($this->total),
            "total_currency_price" => AppLibrary::currencyAmountFormat($this->total),
            'payment_status'       => $this->payment_status,
            'status'               => $this->status,
            'status_name'          => trans('orderStatus.' . $this->status),
            'order_items'          => optional($this->orderProducts)->count(),
            'order_datetime'       => AppLibrary::datetime($this->order_datetime),
            'user'                 => new UserResource($this->user),
            // Guest order info
            'is_guest_order'       => $this->is_guest_order ?? false,
            'guest_name'           => $this->guest_name,
            'guest_email'          => $this->guest_email,
            'guest_phone'          => $this->guest_phone,
            'guest_governorate'    => $this->guest_governorate,
            'guest_city'           => $this->guest_city,
            'guest_street'         => $this->guest_street,
            'guest_building_number' => $this->guest_building_number,
            'guest_apartment'      => $this->guest_apartment,
            // Display name - use guest name if guest order, otherwise use user name
            'customer_name'        => $this->is_guest_order ? $this->guest_name : optional($this->user)->name,
            'customer_phone'       => $this->is_guest_order ? $this->guest_phone : optional($this->user)->phone,
        ];
    }
}
