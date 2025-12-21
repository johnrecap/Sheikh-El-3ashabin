<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Mada extends FormRequest
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
        if (request()->mada_status == Activity::ENABLE) {
            return [
                'mada_phone_number' => ['required', 'string'],
                'mada_account_name' => ['nullable', 'string'],
                'mada_status'       => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'mada_phone_number' => ['nullable', 'string'],
                'mada_account_name' => ['nullable', 'string'],
                'mada_status'       => ['nullable', 'numeric'],
            ];
        }
    }
}
