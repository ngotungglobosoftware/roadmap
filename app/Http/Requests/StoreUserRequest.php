<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'first_name' => 'required|max:50',
            'middle_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone' => 'max:15',
            'email' => 'required|unique:users|email|max:50',
            'password' => 'required|max:32',
            'password_confirmation' => 'required|same:password',
        ];
    }
    
}
