<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SkillRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'instruction' => 'required',
            'score_instruction' => 'required',
            'video_url' => 'required',
            'image' => 'mimes:jpg,jpeg,png'
           
        ];     

        // Check if it's an edit scenario
        if ($this->isMethod('patch')) {
            $rules['image'] = 'mimes:jpeg,jpg,png';
        }

        return $rules;
    }

    public function messages(){

        return [
            'name' => 'The skill name field is required.',
            'category_id' => 'Please select category',
            'short_description' => 'The short description field is required.',
            'long_description' => 'The long description field is required.',
            'instruction' => 'The instruction field is required.',
            'score_instruction' => 'The score instruction field is required.',
            'video_url' => 'The video url field is required.'           
        ];
    }
}
