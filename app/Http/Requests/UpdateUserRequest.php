<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'password' => 'required|max:32',
            'intro' => 'nullable',
            'profile' => 'nullable'
        ];
    }
}
