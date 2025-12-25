<?php

namespace App\Http\Requests;

use App\Enums\OrderType;
use App\Rules\ValidJsonOrder;
use Illuminate\Foundation\Http\FormRequest;

class GuestOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Guest orders don't require authentication
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Guest information (required)
            'guest_name'       => ['required', 'string', 'max:100'],
            'guest_email'      => ['required', 'email', 'max:100'],
            'guest_phone'      => ['required', 'string', 'max:20'],

            // Address fields (for delivery orders)
            'governorate'      => (int) request('order_type') === OrderType::DELIVERY ? ['required', 'string'] : ['nullable'],
            'city'             => (int) request('order_type') === OrderType::DELIVERY ? ['required', 'string'] : ['nullable'],
            'street'           => ['nullable', 'string'],
            'building_number'  => ['nullable', 'string'],
            'apartment'        => ['nullable', 'string'],

            // Order fields
            'subtotal'         => ['required', 'numeric'],
            'discount'         => ['nullable', 'numeric'],
            'delivery_charge'  => (int) request('order_type') == OrderType::DELIVERY ? ['required', 'numeric'] : ['nullable'],
            'tax'              => ['required', 'numeric'],
            'total'            => ['required', 'numeric'],
            'order_type'       => ['required', 'numeric'],
            'outlet_id'        => (int) request('order_type') == OrderType::PICK_UP ? ['required', 'numeric', 'not_in:0'] : ['nullable'],
            'coupon_id'        => ['nullable', 'numeric'],
            'source'           => ['required', 'numeric'],
            'payment_method'   => ['required', 'numeric'],
            'delivery_zone_id' => ['required', 'numeric'],
            'products'         => ['required', 'json', new ValidJsonOrder],
            'images[]'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ];
    }

    /**
     * Custom validation messages (Arabic)
     */
    public function messages(): array
    {
        return [
            'guest_name.required'  => 'الاسم مطلوب',
            'guest_email.required' => 'البريد الإلكتروني مطلوب',
            'guest_email.email'    => 'البريد الإلكتروني غير صالح',
            'guest_phone.required' => 'رقم الهاتف مطلوب',
            'governorate.required' => 'المحافظة مطلوبة',
            'city.required'        => 'المدينة مطلوبة',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (request('order_type') != OrderType::DELIVERY && request('order_type') != OrderType::PICK_UP) {
                $validator->errors()->add('order_type', 'نوع الطلب غير صالح');
            }
        });
    }
}
