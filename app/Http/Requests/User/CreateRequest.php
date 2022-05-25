<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public mixed $password;

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
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:5',
            'role' => 'sometimes|string|max:32',
            'status' => 'sometimes|string|max:64',
            'notes' => 'sometimes|string|max:1000'
        ];
    }
}
