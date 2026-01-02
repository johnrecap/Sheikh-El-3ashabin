<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryZoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
            'name'      => [
                'required',
                'string',
                'max:190',
                Rule::unique("delivery_zones", "name")->ignore($this->route('deliveryZone.id'))
            ],
            'governorate_name'          => ['nullable', 'string', 'max:100'],
            'email'                     => ['nullable', 'email', 'max:190'],
            'phone'                     => ['nullable', 'string', 'max:20'],
            'latitude'                  => ['nullable', 'max:190'],
            'longitude'                 => ['nullable', 'max:190'],
            'delivery_radius_kilometer' => ['nullable', 'numeric'],
            'delivery_charge_per_kilo'  => ['nullable', 'numeric'],
            'delivery_fee'              => ['nullable', 'numeric'],
            'minimum_order_amount'      => ['required', 'numeric'],
            'address'                   => ['nullable', 'string', 'max:500'],
            'status'                    => ['required', 'numeric', 'max:24'],
        ];
    }
}
