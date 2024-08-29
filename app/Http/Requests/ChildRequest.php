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
       // Get the ID from the route for updating purposes
       $childId = $this->route('child'); 

       // Default rules
       $rules = [
           'child_firstname' => 'required|string|max:255',
           'child_lastname' => 'required|string|max:255',
           'child_username' => 'required|string|max:255',
           'child_dob' => 'required|date',
           'child_phone' => 'required|numeric',
           'province_id' => 'required',
           'school_id' => 'required',
           
       ];

       // Apply password rule based on request method
       

       return $rules;
    }

    
}
