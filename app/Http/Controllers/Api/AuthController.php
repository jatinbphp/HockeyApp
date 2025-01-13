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
use App\Mail\EmailVerificationMail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', [
            'except' => ['login','register','childrenRegister','contactUs','getActiveSkill','resetPassword','forgotPassword']
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'email' => 'required',
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

        $loginField = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = ['login' => $request->input('email'), 'password' => $request->input('password')];
        // $credentials = $request->only('email', 'password');

        // Try to authenticate against the User model
        $user = $this->attemptLogin(User::class, $credentials, $loginField);
        if (!$user) {
            // If User authentication fails, try to authenticate against the Child model
            $user = $this->attemptLogin(Child::class, $credentials, $loginField);
        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username or Password not match!',
                'data' => (object)[]
            ], 401);
        }

        if ($user->status == 'inactive') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is not verified. Please check your email for the verification link to activate your account.',
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

    protected function attemptLogin($model, $credentials, $loginField)
    {
        // $user = $model::where($loginField, $credentials['login'])->first();
        $user = $model::where('username', $credentials['login'])->first();

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
            'username' => 'required|unique:users,username|unique:children,username',
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
            'username' => $request->username,
            'password' => $request->password,
            'phone' => $request->phone,
            'terms' => (($request->terms)?'1':'0'),
            'older_than_18' => '0',
            'role' => 'guardian',
            'status' => 'inactive'
        ]);

        $token = Auth::login($user);

        $verificationToken = Str::random(60);
       
        $user->update([
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,
            'session_token' => $token,
            'remember_token' => $verificationToken
        ]);     

        $template_details= EmailTemplate::find(5);
        $guardianEmail= $user->email ?? "";
      
        $verificationUrl = url('/account/verify/' . $verificationToken);

        if(!empty($template_details))
        {
            $placeholders = [
                '{{firstname}}' => ucfirst($user['firstname']),
                '{{verifiy_mail_link}}' => $verificationUrl,
            ];

            $messageBody = str_replace(
                array_keys($placeholders), 
                array_values($placeholders), 
                $template_details->template_message
            );

            $mailData = [
                'salutation' => 'Hello ' . ucfirst($user['firstname']),
                'body' => $messageBody,
                'subject' => $template_details->template_subject ?? "",
            ];

            Mail::to([$guardianEmail])->send(new EmailVerificationMail($mailData));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Register Successfully!',
            'data' => $user
        ],200);

    }

    /* CHILDREN REGISTRATION */
    public function childrenRegister(Request $request)
    {
        try{

            $validator = Validator::make($request->post(), [
                'children' => 'required|array|min:1',
                'children.*.firstname' => 'required',
                'children.*.lastname' => 'required',
                'children.*.email' => 'required|email',
                'children.*.username' => 'required|unique:users,username|unique:children,username',
                'children.*.password' => 'required',
                'children.*.phone' => 'required|numeric',
                'children.*.date_of_birth' => 'required|date',
                'children.*.province' => 'required',
                'children.*.school' => 'required',
                'children.*.gender' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);
            }


            $parentId = $request->parent_id ?? 0;
            $childrenData = $request->children;
            $createdChildren = [];
            $template_details = EmailTemplate::find(1);

            foreach ($childrenData as $childData) {
                $childData['parent_id'] = $parentId;
                $childData['province_id'] = $childData['province'];
                $childData['school_id'] = $childData['school'];

                $dob = date('Y-m-d',strtotime($childData['date_of_birth']));
                $childData['age_group'] = getAgeGroup($dob);
                $orgPass = $childData['password'];
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
                        '{{username}}' => $child['username'],
                        '{{password}}' => $orgPass,
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

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->post(), [
            'username' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }
    
        // Try finding the user in both tables
        $user = User::where('username', $request->username)->first();
        $child = Child::where('username', $request->username)->first();
    
        // Use the found model or set response if not found
        $foundUser = $user ?: $child;
    
        if (!empty($foundUser)) {
            $token = Str::random(60);
            $foundUser->update(['remember_token' => $token]);
            $resetUrl = url('/password/reset/' . $token . '?username=' . urlencode($foundUser->username));            

            if(!empty($child) && !empty($child->email)){

                // Mail sending logic here
                $mailData["email"] = $child->email;
                $mailData["title"] = "Reset Your Password";
                $mailData["salutation"] = "Dear " . $child->firstname . ' ' . $child->lastname;
                $mailData["body"] = "Please click the link below to reset your password.";
                $mailData["resetUrl"] = $resetUrl;
        
                Mail::to($child->email)->send(new ResetPasswordMail($mailData));

            }else{

                // Mail sending logic here
                $mailData["email"] = $user->email;
                $mailData["title"] = "Reset Your Password";
                $mailData["salutation"] = "Dear " . $user->firstname . ' ' . $user->lastname;
                $mailData["body"] = "Please click the link below to reset your password.";
                $mailData["resetUrl"] = $resetUrl;
        
                Mail::to($user->email)->send(new ResetPasswordMail($mailData));
            }
           
    
            return response()->json([
                'status' => 'success',
                'message' => 'Password reset link has been sent to your email.',
                'data' => $foundUser,
                'resetUrl' => $resetUrl
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
