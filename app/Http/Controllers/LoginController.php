<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use App\Models\User;



class LoginController extends Controller
{
   
    public function index(Request $request){
        // Validate incoming request data
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        // Prepare data for API request
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),            
        ];

        $user = User::where(['email' => $request->email,'role' => 'admin'])->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return redirect('admin/dashboard');
            } else {
                return back()->with('danger','Password not match!');
            }
        } else {
            return back()->with('danger','Email not Found.');
        }     
        
    }

    public function logout()
    {
        // Check if the session has a 'loginId'
        if (Session::has('loginId')) {
            // Remove 'loginId' from the session
            Session::pull('loginId');
        }

        // Redirect to the home page
        return redirect('/');
    }
}
 