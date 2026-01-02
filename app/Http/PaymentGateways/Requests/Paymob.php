<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Paymob extends FormRequest
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
        if (request()->paymob_status == Activity::ENABLE) {
            return [
                'paymob_api_key'        => ['required', 'string'],
                'paymob_integration_id' => ['required', 'string'],
                'paymob_iframe_id'      => ['required', 'string'],
                'paymob_hmac'           => ['required', 'string'],
                'paymob_mode'           => ['required', 'numeric'],
                'paymob_status'         => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'paymob_api_key'        => ['nullable', 'string'],
                'paymob_integration_id' => ['nullable', 'string'],
                'paymob_iframe_id'      => ['nullable', 'string'],
                'paymob_hmac'           => ['nullable', 'string'],
                'paymob_mode'           => ['nullable', 'numeric'],
                'paymob_status'         => ['nullable', 'numeric'],
            ];
        }
    }
}
