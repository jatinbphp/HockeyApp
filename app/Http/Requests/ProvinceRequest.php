<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProvinceRequest extends FormRequest
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
        $provinceId = $this->route('province');
        $rules = [
            'name' => [
                'required',
                Rule::unique('provinces','name')->ignore($provinceId)
            ]
        ];

        return $rules;
    }

    public function messages(){
        return [
            'name' => 'The Province name field is required.',
            'name.unique' => 'Province name already exists.'
        ];
    }
}
