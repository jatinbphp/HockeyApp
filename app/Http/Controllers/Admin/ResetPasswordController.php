<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request,$token){
        return view('admin.reset-password.reset-page')->with(
            ['token' => $token, 'username' => $request->username]
        );
    }

    public function resetPassword(Request $request, $token = null){
        // Get the username from the URL query parameters
        $username = $request->username;
        $token = $request->token;
        
        // Try to find the user in the User table first
        $user = User::where('username', $username)->where('remember_token', $token)->first();
    
        // If not found in User, check the Child table
        if (!$user) {
            $user = Child::where('username', $username)->where('remember_token', $token)->first();
        }
       
        // If user or child is found, update password
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
                'remember_token' => ''  // Clear the token after password reset
            ]);
    
            \Session::flash('success', 'Password has been reset successfully!');
            return redirect()->route('login'); // Or any other route you want to redirect to after password reset
    
        } else {
            \Session::flash('danger', 'You are not authorized to reset this password.');
            return redirect()->back();
        }
    }
}
