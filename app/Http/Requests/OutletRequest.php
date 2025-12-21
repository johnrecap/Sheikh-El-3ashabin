<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OutletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name'      => [
                'required',
                'string',
                'max:190',
                Rule::unique("outlets", "name")->ignore($this->route('outlet.id'))
            ],
            'email'            => ['nullable', 'email', 'max:190'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'country_code'     => ['nullable', 'string', 'max:20'],
            'latitude'         => ['nullable', 'max:190'],
            'longitude'        => ['nullable', 'max:190'],
            'address'          => ['required', 'string', 'max:500'],
            'status'           => ['required', 'numeric', 'max:24'],
            'delivery_zone_id' => ['required', 'numeric', Rule::unique("outlets", "delivery_zone_id")->ignore($this->route('outlet.id'))],
        ];
    }

    public function attributes()
    {
        return [
            'delivery_zone_id' => 'delivery zone'
        ];
    }
}
