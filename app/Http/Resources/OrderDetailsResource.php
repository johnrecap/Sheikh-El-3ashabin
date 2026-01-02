<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'id'                             => $this->id,
            'order_serial_no'                => $this->order_serial_no,
            'user_id'                        => $this->user_id,
            'guest_name'                     => $this->guest_name,
            'guest_email'                    => $this->guest_email,
            'guest_phone'                    => $this->guest_phone,
            'is_guest_order'                 => $this->is_guest_order ?? false,
            'guest_governorate'              => $this->guest_governorate,
            'guest_city'                     => $this->guest_city,
            'guest_street'                   => $this->guest_street,
            'guest_building_number'          => $this->guest_building_number,
            'guest_apartment'                => $this->guest_apartment,
            // Display name - use guest name if guest order, otherwise use user name
            'customer_name'                  => $this->is_guest_order ? $this->guest_name : optional($this->user)->name,
            'customer_phone'                 => $this->is_guest_order ? $this->guest_phone : optional($this->user)->phone,
            "subtotal_currency_price"        => AppLibrary::currencyAmountFormat($this->subtotal),
            "tax_currency_price"             => AppLibrary::currencyAmountFormat($this->tax),
            "discount_currency_price"        => AppLibrary::currencyAmountFormat($this->discount),
            "total_currency_price"           => AppLibrary::currencyAmountFormat($this->total),
            "total_amount_price"             => AppLibrary::flatAmountFormat($this->total),
            "delivery_charge_currency_price" => AppLibrary::currencyAmountFormat($this->delivery_charge),
            'order_type'                     => $this->order_type,
            'order_date'                     => AppLibrary::date($this->order_datetime),
            'order_time'                     => AppLibrary::time($this->order_datetime),
            'order_datetime'                 => AppLibrary::datetime($this->order_datetime),
            'payment_method'                 => $this->payment_method,
            'payment_method_name'            => $this->paymentMethod?->name,
            'payment_status'                 => $this->payment_status,
            'status'                         => $this->status,
            'delivery_boy'                   => new UserResource($this->deliveryBoy),
            'reason'                         => $this->reason,
            'source'                         => $this->source,
            'active'                         => (int) $this->active,
            'user'                           => new UserResource($this->user),
            'order_address'                  => new AddressResource($this->address),
            'outlet_address'                 => new OutletResource($this?->outletAddress),
            'order_products'                 => OrderProductResource::collection($this->orderProducts),
            'delivery_boy_name'              => $this?->deliveryBoy?->name,
            'pos_payment_id'                 => $this->pos_payment_method,
            'pos_payment_method'             => trans("posPaymentMethod.{$this->pos_payment_method}"),
            'pos_payment_note'               => $this->pos_payment_note,
            'delivery_zone_id'               => $this->delivery_zone_id,
            'delivery_zone_name'             => $this?->deliveryZone?->name,
            'edited_amount'                  => $this->edited_amount,
            'edited_currency_amount'         => AppLibrary::currencyAmountFormat(abs($this->edited_amount)),
            'transaction'                    => $this->transaction?->id,
            'edited_date'                    => $this->edited_date,
            'order_items'                    => optional($this->orderProducts)->count(),
            'status_name'                    => trans('orderStatus.' . $this->status),
            'images'                         => $this->images,
        ];
    }
}
