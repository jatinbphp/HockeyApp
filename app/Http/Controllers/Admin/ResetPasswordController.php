<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request,$token){
        return view('admin.reset-password.reset-page')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(ResetPasswordRequest $request, $token = null){
        $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();
    
        if (!empty($user)) {
            $user->update([
                'password' => Hash::make($request->password),
                'remember_token' => ''
            ]);

            \Session::flash('success', 'Password has been reset successfully!');
            return redirect()->back();

        } else {
            \Session::flash('danger', 'You are not authorised to reset password.');
            return redirect()->back();
        }

    }
}
