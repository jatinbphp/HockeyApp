<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SchoolRequest extends FormRequest
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
        $schoolId = $this->route('school');
        $rules = [
            'name' => [
                'required',
                Rule::unique('schools','name')->ignore($schoolId)
            ],
            'town' => 'required',
            'province_id' => 'required'
        ];

        return $rules;
    }

    public function messages(){

        return [
            'name' => 'The School name field is required.',
            'name.unique' => 'School name already exists.',
            'town' => 'The Town name field is required.',
            'province_id' => 'Please select Province'
        ];
    }
}
