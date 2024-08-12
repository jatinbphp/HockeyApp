<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChildRequest extends FormRequest
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
        $userId = $this->route('children');

        $rules = [
            'child_firstname' => 'required|string|max:255',
            'child_lastname' => 'required|string|max:255',
            // 'username' => 'required|string|max:255',
            // 'password' => 'confirmed|min:6',
            // 'email' => [
            //     'required',
            //     'email',
            //     Rule::unique('children','email')->ignore($userId)
            // ]
        ];

        // if ($this->isMethod('patch')) {
        //     $rules['password'] = 'nullable|confirmed|min:6';
        // }

        return $rules;
    }

    public function messages(){

        return [
            'child_firstname' => 'The firstname field is required.',
            'child_lastname' => 'The lastname field is required.',
            // 'username' => 'The username field is required.',
            // 'email.email' => 'The email address field must be a valid email address.',
            // 'email.unique' => 'Email already exists.'
        ];
    }
}
