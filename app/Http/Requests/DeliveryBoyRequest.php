<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Order;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryBoyRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'                  => ['required', 'string', 'max:190'],
            'email'                 => [
                'required',
                'email',
                'max:190',
                Rule::unique("users", "email")->ignore($this->route('deliveryBoy.id'))
            ],
            'password'              => [
                $this->route('deliveryBoy.id') ? 'nullable' : 'required',
                'string',
                'min:6'
            ],
            'password_confirmation' => [$this->route('deliveryBoy.id') ? 'nullable' : 'required', 'string', 'min:6', 'same:password'],
            'username'              => [
                'nullable',
                'max:190',
                Rule::unique("users", "username")->ignore($this->route('deliveryBoy.id'))
            ],
            'device_token'          => ['nullable', 'string'],
            'web_token'             => ['nullable', 'string'],
            'phone'                 => [
                'nullable',
                'string',
                'max:20',
                Rule::unique("users", "phone")->ignore($this->route('deliveryBoy.id'))
            ],
            'delivery_zone_id' => ['required', 'numeric', 'max:24'],
            'status'           => ['required', 'numeric', 'max:24'],
            'country_code'     => ['required', 'string', 'max:20'],
        ];
    }

    public function attributes(): array
    {
        return [
            'delivery_zone_id' => 'delivery zone'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (request('delivery_zone_id') && $this->route('deliveryBoy.id')) {
                $deliveryBoy = User::findOrFail($this->route('deliveryBoy.id'));
                $order = Order::where('order_type', "!=", OrderType::POS)->where('delivery_boy_id', $this->route('deliveryBoy.id'))->whereIn('status', [OrderStatus::CONFIRMED, OrderStatus::ON_THE_WAY])->first();
                if ($order && $deliveryBoy->delivery_zone_id !== (int)request('delivery_zone_id')) {
                    $validator->errors()->add('delivery_zone_id', 'The delivery zone cannot be changed due to active orders.');
                }
            }
        });
    }
}
