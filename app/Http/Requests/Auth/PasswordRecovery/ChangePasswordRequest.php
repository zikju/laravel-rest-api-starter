<?php

namespace App\Http\Requests\Auth\PasswordRecovery;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token' => 'required|string|min:36|exists:users,confirmation_token',
            'password' => 'required|string|confirmed|min:5|max:64',
        ];
    }
}
