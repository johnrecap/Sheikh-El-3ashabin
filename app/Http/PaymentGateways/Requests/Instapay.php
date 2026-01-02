<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Instapay extends FormRequest
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
        if (request()->instapay_status == Activity::ENABLE) {
            return [
                'instapay_phone_number' => ['required', 'string'],
                'instapay_account_name' => ['nullable', 'string'],
                'instapay_status'       => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'instapay_phone_number' => ['nullable', 'string'],
                'instapay_account_name' => ['nullable', 'string'],
                'instapay_status'       => ['nullable', 'numeric'],
            ];
        }
    }
}
