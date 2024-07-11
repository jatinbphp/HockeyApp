<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
   
    public function index(Request $request){
        // Validate incoming request data
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        // Prepare data for API request
        $data = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),            
        ];

        $user = User::where('email','=',$request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return redirect('dashboard');
            } else {
                return back()->with('fail','Password not match!');
            }
        } else {
            return back()->with('fail','This Username is not register.');
        }     
        
    }

    public function logout()
    {
        $data = array();
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('login');
        }
    }
}
 