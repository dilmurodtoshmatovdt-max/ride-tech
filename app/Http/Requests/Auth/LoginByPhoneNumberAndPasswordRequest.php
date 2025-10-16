<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginByPhoneNumberAndPasswordRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'phone_number' => 'required|integer',
            'password' => 'required|string|min:4',
        ];
    }

    public function attributes(): array
    {
        return [
            'phone_number' => 'Номер телефона',
            'password' => 'Пароль',
        ];
    }
}
