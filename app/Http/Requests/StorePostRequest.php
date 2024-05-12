<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'authorId' => 'required',
            'title' => 'required|max:75',
            'metaTitle' => 'required|max:100',
            'slug' => 'nullable|max:100',
            'content' => 'nullable',
            'summary' => 'nullable',
            'published' => 'required|in:0,1',
            'tags' => 'nullable'
        ];
    }
}
