<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');

        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'confirmed|min:6',
            'image' => 'mimes:jpg,jpeg,png',
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($userId)
            ]
        ];

        if ($this->isMethod('patch')) {
            $rules['password'] = 'nullable|confirmed|min:6';
        }

        return $rules;
    }

    public function messages(){

        return [
            'firstname' => 'The firstname field is required.',
            'lastname' => 'The lastname field is required.',
            'username' => 'The username field is required.',
            'email.email' => 'The email address field must be a valid email address.',
            'email.unique' => 'Email already exists.'
        ];
    }
}
