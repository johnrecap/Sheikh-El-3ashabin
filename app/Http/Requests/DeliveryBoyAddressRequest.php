<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryBoyAddressRequest extends FormRequest
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
            'label'           => ['required', 'string', 'max:190', Rule::unique("addresses", "label")->ignore($this->route('address.id'))->where('user_id', $this->route('deliveryBoy.id'))],
            'governorate'     => ['required', 'string', 'max:100'],
            'city'            => ['required', 'string', 'max:100'],
            'street'          => ['nullable', 'string', 'max:200'],
            'building_number' => ['nullable', 'string', 'max:50'],
            'apartment'       => ['nullable', 'string', 'max:200'],
        ];
    }
}
