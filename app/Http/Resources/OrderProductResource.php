<?php

namespace App\Http\Resources;

use App\Enums\Ask;
use App\Libraries\AppLibrary;
use App\Models\ProductVariation;
use Illuminate\Http\Resources\Json\JsonResource;
use Smartisan\Settings\Facades\Settings;

class OrderProductResource extends JsonResource
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
            'id'                      => (int) $this->id,
            'stock_id'                => (int) $this->id, 
            'order_id'                => (int) $this->model_id,
            'product_id'              => (int) $this->product_id,
            'product_name'            => $this->product?->name,
            'product_image'           => $this->product?->thumb,
            'product_slug'            => $this->product?->slug,
            'category_name'           => $this?->product?->category?->name,
            'sell_by_fraction'        => $this->product?->sell_by_fraction,
            'price'                   => $this->price,
            'currency_price'          => AppLibrary::currencyAmountFormat($this->price),
            'quantity'                => abs($this->quantity),
            'order_quantity'          => abs($this->quantity),
            'discount'                => $this->discount,
            'discount_currency_price' => AppLibrary::currencyAmountFormat($this->discount),
            'tax'                     => $this->tax,
            'tax_currency'            => AppLibrary::currencyAmountFormat($this->tax),
            'subtotal'                => AppLibrary::flatAmountFormat($this->subtotal),
            'total'                   => AppLibrary::flatAmountFormat($this->total),
            'subtotal_currency_price' => AppLibrary::currencyAmountFormat($this->subtotal),
            'total_currency_price'    => AppLibrary::currencyAmountFormat($this->total),
            'status'                  => (int) $this->status,
            'variation_names'         => $this->variation_names,
            'product_user_review'     => $this?->product?->userReview ? true : false,
            'product_user_review_id'  => $this?->product?->userReview?->id,
            'is_refundable'           => $this?->product?->refundable === Ask::YES ? true : false,
            'has_variation'           => $this->item_type == ProductVariation::class ? true : false,
            'variation_id'            => $this->item_type == ProductVariation::class ? $this->item_id : ''
        ];
    }
}
