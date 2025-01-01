<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChildModalRequest extends FormRequest
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
       $childId = $_POST['child_id']; 
        
       // Default rules
       $rules = [
           'child_firstname' => 'required|string|max:255',
           'child_lastname' => 'required|string|max:255',
           'email' => 'required|string|max:255',
           'child_dob' => 'required|date',
           'child_phone' => 'required|numeric',
           'province_id' => 'required',
           'school_id' => 'required',
           'child_username' => [
            'required',
            function ($attribute, $value, $fail) use ($childId) {
                $existsInUsers = DB::table('users')
                    ->where('username', $value)
                    // ->where('id', '<>', $childId)
                    ->exists();

                $existsInChildren = DB::table('children')
                    ->where('username', $value)
                    ->where('id', '<>', $childId)
                    ->exists();

                if ($existsInUsers || $existsInChildren) {
                    $fail('The username has already been taken.');
                }
            },
        ]
        ];

       // Apply password rule based on request method
       

       return $rules;
    }

    
}
