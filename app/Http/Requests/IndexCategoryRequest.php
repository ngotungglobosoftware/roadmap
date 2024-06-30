<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexCategoryRequest extends FormRequest
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
            'page' => 'nullable|integer',
            'sortBy' => 'nullable|in:asc,desc',
            'limit' => 'nullable|integer',
            'query' => 'nullable|string',
            'startTime' => 'nullable|date',
            'endTime' => 'nullable|date|after_or_equal:startTime'
        ];
    }
}
