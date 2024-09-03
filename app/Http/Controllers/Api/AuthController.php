<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Child;
use App\Models\ContactUs;
use App\Models\EmailTemplate;
use App\Mail\RegistrationMail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', [
            'except' => ['login','register','childrenRegister','contactUs','getActiveSkill','resetPassword']
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'email' => 'required|email',
            'password' => 'required',
            // 'device_type' => 'required|string',
            // 'device_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $credentials = $request->only('email', 'password');

        // Try to authenticate against the User model
        $user = $this->attemptLogin(User::class, $credentials);
        if (!$user) {
            // If User authentication fails, try to authenticate against the Child model
            $user = $this->attemptLogin(Child::class, $credentials);            

        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email or Password not match!',
                'data' => (object)[]
            ], 401);
        }

        if ($user->status == 'inactive') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account has been inactive',
                'data' => (object)[]
            ], 401);
        }

        // Generate token
        $token = JWTAuth::fromUser($user);

        // Store device_type, device_id, and token in the database
        $user->update([
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,
            'session_token' => $token
        ]);

        
        if($user->role == "guardian"){
            $user->role = $user->role;
        }else{
            $user->role = "children";
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Login Successful!',
            'data' => $user
        ], 200);
    }

    protected function attemptLogin($model, $credentials)
    {
        $user = $model::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return false;
    }

    public function loginOld(Request $request){

        $validator = Validator::make($request->post(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email or Password not match!',
                'data' => (object)[]
            ], 401);
        }

        $user = Auth::user();

        if (empty($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not found',
                'data' => (object)[]
            ], 404);
        }else{

            if ($user['status'] == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account has been inactive',
                    'data' => (object)[]
                ], 401);
            }
        }

        // Store device_type, device_id, and token in the database
        $user->update([
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,
            'session_token' => $token
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfull!',
            'data' => $user
        ],200);
    }

    public function register(Request $request){

        $validator = Validator::make($request->post(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users|unique:children',
            'password' => 'required',
            'phone' => 'required|numeric',  
            'terms' => 'required'        
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

     
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'terms' => (($request->terms)?'1':'0'),
            'role' => 'guardian'
        ]);

        $token = Auth::login($user);

        $user->update([
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,
            'session_token' => $token
        ]); 
        
        $template_details= EmailTemplate::find(1);
        $guardianEmail= $user->email ?? "";

        $template_details = EmailTemplate::find(1);

        if(!empty($template_details))
        {
            $placeholders = [
                '{{firstname}}' => ucfirst($user['firstname']),
                '{{lastname}}' => ucfirst($user['lastname']),
            ];

            $messageBody = str_replace(
                array_keys($placeholders), 
                array_values($placeholders), 
                $template_details->template_message
            );

            $mailData = [
                'salutation' => 'Hello ' . ucfirst($user['firstname']) . ' ' . ucfirst($user['lastname']) . ',',
                'body' => $messageBody,
                'subject' => $template_details->template_subject ?? "",
            ];

            Mail::to([$guardianEmail])->send(new RegistrationMail($mailData));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Register Successfully!',
            'data' => $user
        ],200);

    }

    public function childrenRegister(Request $request)
    {
        try{

            $request->validate([
                'children' => 'required|array|min:1',
                'children.*.firstname' => 'required',
                'children.*.lastname' => 'required',
                'children.*.email' => 'required|email|unique:users,email|unique:children,email',
                'children.*.username' => 'required',
                'children.*.password' => 'required',
                'children.*.phone' => 'required|numeric',
                'children.*.date_of_birth' => 'required|date',
                'children.*.province' => 'required',
                'children.*.school' => 'required',
            ]);

            

            $parentId = $request->parent_id ?? 0;
            $childrenData = $request->children;
            $createdChildren = [];
            $template_details = EmailTemplate::find(1);

            foreach ($childrenData as $childData) {
                $childData['parent_id'] = $parentId;
                $childData['password'] = bcrypt($childData['password']); // Hash password

                $child = Child::create($childData);
                $token = Auth::login($child);

                $child->update([
                    'device_type' => $childData['device_type'] ?? null,
                    'device_id' => $childData['device_id'] ?? null,
                    'session_token' => $token
                ]);

                $createdChildren[] = $child;

                if ($template_details && $child->email) {
                    $placeholders = [
                        '{{firstname}}' => ucfirst($child['firstname']),
                        '{{lastname}}' => ucfirst($child['lastname']),
                    ];
        
                    $messageBody = str_replace(
                        array_keys($placeholders), 
                        array_values($placeholders), 
                        $template_details->template_message
                    );
        
                    $mailData = [
                        'salutation' => 'Hello ' . ucfirst($child['firstname']) . ' ' . ucfirst($child['lastname']) . ',',
                        'body' => $messageBody,
                        'subject' => $template_details->template_subject ?? "",
                    ];
        
                    Mail::to($child->email)->send(new RegistrationMail($mailData));
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Register Successfully!',
                'data' => $createdChildren
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => 'Somthing went wrong!',
                'data' => (object)[]
            ],404);
        }
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout Successfully!'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'token' => Auth::refresh()
        ]);
    }

    public function contactUs(Request $request){

        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required',       
        ]);

        $contact = ContactUs::create([
            'fullname' => $request->fullname,          
            'email' => $request->email,           
            'phone' => $request->phone,
            'message' => $request->message           
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thank you! Your message has been successfully sent. We will get back to you shortly.',
            'data' => $contact
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'password' => 'required|confirmed|min:6',
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {  
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {

            $token = Str::random(60);
            $user->update(['remember_token' => $token]);
            $resetUrl = url('/password/reset/' . $token. '?email=' . urlencode($user->email));

            $mailData["email"] = $user->email;
            $mailData["title"] = "Reset Your Password";
            $mailData["salutation"] = "Dear ".$user->firstname.' '.$user->lastname;
            $mailData["body"] = "Please click below link for reset your password.";
            $mailData["resetUrl"] = $resetUrl;

            Mail::to($request->email)->send(new ResetPasswordMail($mailData));
            $response["status"] = 'success';
            $response["message"] = 'Password reset link has been sent to your email.';

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset link has been sent to your email',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!',
                'data' => (object)[]
            ], 404);
        }
    }
}
