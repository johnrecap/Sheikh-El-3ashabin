<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdministratorRequest extends FormRequest
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
                Rule::unique("users", "email")->ignore($this->route('administrator.id'))
            ],
            'password'              => [
                $this->route('administrator.id') ? 'nullable' : 'required',
                'string',
                'min:6'
            ],
            'password_confirmation' => [$this->route('administrator.id') ? 'nullable' : 'required', 'string', 'min:6', 'same:password'],
            'phone'                 => [
                'nullable',
                'string',
                'max:20',
                Rule::unique("users", "phone")->ignore($this->route('administrator.id'))
            ],
            'status'                => ['required', 'numeric', 'max:24'],
            'country_code'          => ['required', 'string', 'max:20'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->route('administrator.id') === 1 && request('status') === Status::INACTIVE) {
                $validator->getMessageBag()->add('status', trans('all.message.supper_admin_status'));
            }
        });
    }
}