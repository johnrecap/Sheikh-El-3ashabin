<?php

namespace App\Http\Requests;

use App\Rules\ValidJsonOrder;
use App\Enums\PosPaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class PosOrderRequest extends FormRequest
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
            'customer_id'        => ['required', 'numeric'],
            'subtotal'           => ['required', 'numeric'],
            'discount'           => ['nullable', 'numeric'],
            'tax'                => ['required', 'numeric'],
            'total'              => ['required', 'numeric'],
            'order_type'         => ['required', 'numeric'],
            'source'             => ['required', 'numeric'],
            'products'           => ['required', 'json', new ValidJsonOrder],
            'pos_payment_method' => ['required', 'numeric'],
            'pos_payment_note'   => request('pos_payment_method') === PosPaymentMethod::CARD || request('pos_payment_method') === PosPaymentMethod::MOBILE_BANKING || request('pos_payment_method') === PosPaymentMethod::OTHER ? (request('pos_payment_method') === PosPaymentMethod::CARD ? ['required', 'numeric', 'min_digits:4', 'max_digits:4'] : ['required', 'string']) : ['nullable', 'string']
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'customer'
        ];
    }

    public function messages()
    {
        return [
            'pos_payment_note.required'    => request('pos_payment_method') == PosPaymentMethod::CARD ? 'Last 4 digits of card is required' : (request('pos_payment_method') == PosPaymentMethod::MOBILE_BANKING ? 'Transaction ID field is required' : 'Payment note field is required'),
            'pos_payment_note.numeric'     => 'The card digits must be a number',
            'pos_payment_note.min_digits'  => 'The cart must contain at least 4 digits',
            'pos_payment_note.max_digits'  => 'The cart must not contain more than 4 digits',
            'pos_received_amount.required' => 'The received amount field is required',
            'dining_table_id.required'     => 'The dining table field is required'
        ];
    }
}