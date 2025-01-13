<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;



class LoginController extends Controller
{
   
    public function index(Request $request){
        // Validate incoming request data
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'super_admin']) || 
            Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            $user = Auth::user();
            $request->session()->put('user_id', $user->id);
            \Log::info('User Authenticated: ' . Auth::id());
    
            return redirect()->route('admin.dashboard');
        } else {
            \Session::flash('danger', 'Invalid Credentials!');
            return redirect()->route('login');
        }  
        
    }

    public function logout(Request $request)
    {

        Auth::guard('web')->logout(); 
      
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/');
    }
}
 