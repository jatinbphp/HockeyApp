<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
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
        $catId = $this->route('category');
        $rules = [
            'name' => [
                'required',
                Rule::unique('categories','name')->ignore($catId)
            ]
        ];

        return $rules;
    }

    public function messages(){
        return [
            'name' => 'The Category name field is required.',
            'name.unique' => 'Category name already exists.'
        ];
    }
}
