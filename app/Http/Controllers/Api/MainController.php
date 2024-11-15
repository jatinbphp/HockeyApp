<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\PayFastService;
use App\Mail\SkillTestMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Categories;
use App\Models\Province;
use App\Models\Skill;
use App\Models\Score; 
use App\Models\Child;
use App\Models\Sponsors;
use App\Models\School;
use App\Models\Payment;
use App\Models\Fee;
use App\Models\EmailTemplate;
use App\Models\CmsPages;
use Illuminate\Support\Str;
use DOMDocument;
use Carbon\Carbon;


class MainController extends Controller
{
    protected $authController;

    public function __construct(AuthController $authController){

        // Set the injected AuthController to a class property
        $this->authController = $authController;

        $this->middleware('auth:api', [
            'except' => ['login','register','getActiveSchool','getActiveProvince','getSponsors','getActiveSkill','getChildrenProfile','getChildrensByParentId','submitScore','guardianProfileUpdate','childrenProfileUpdate','getActiveSkillById','getGuardianProfile','multipleChildrenProfileUpdate','getActiveRankings','getActiveRankingsById','getChildPayment','getPayfastPaymentUrl','commonSiginSignup','getCMSPage']
        ]);
    }

    /* GET ACTIVE CATEGORIES */
    public function getActiveCategories()
    {
        $activeCategories = Categories::select('id','name')->where('status', "active")->get();
        $activeCategories->makeHidden(['deleted_at']);

        if (!$activeCategories->isEmpty()) {

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $activeCategories
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active categories found',
                'data' => (object)[]
            ], 404);
        }
        
    }

    /* GET ACTIVE School */
    public function getActiveSchool()
    {
        $activeSchool = School::select('id','name')->where('status', "active")->get();
        $activeSchool->makeHidden(['deleted_at']);

        if (!$activeSchool->isEmpty()) {

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $activeSchool
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active school found',
                'data' => (object)[]
            ], 404);
        }
        
    }

    /* GET ACTIVE PROVINCE */
    public function getActiveProvince()
    {
        $activeProvince = Province::select('id','name')->where('status', "active")->get();
        
        if (!$activeProvince->isEmpty()) {

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $activeProvince
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active province found',
                'data' => (object)[]
            ], 404);
        }
        
    }

    /* GET ACTIVE SKILL */
    public function getActiveSkill()
    {
        $activeSkill = Skill::where('status', "active")->orderBy('position','asc')->get();
        
        if (!$activeSkill->isEmpty()) {

            // Transform the sponsors data to include full image URL
            $skills = $activeSkill->map(function ($skill) {
                $skill->featured_image = (!empty($skill->featured_image))? url($skill->featured_image): ''; // Assuming 'image' is the field that contains the image path

                $skill->icon_image = (!empty($skill->icon_image))? url($skill->icon_image): ''; // Assuming 'image' is the field that contains the image path

                if(!empty($skill->instruction)){
                    $instructionContent = $skill->instruction;

                    // Load the content into a DOMDocument object
                    $dom = new DOMDocument();
                    libxml_use_internal_errors(true); // To suppress warnings for malformed HTML
                    $dom->loadHTML($instructionContent);
                    libxml_clear_errors();

                    // Get all <li> tags
                    $liTags = $dom->getElementsByTagName('li');

                    // Initialize an array to store the list items
                    $liArray = [];

                    foreach ($liTags as $key => $li) {
                        // Get the text content of each <li> tag
                        $liArray[$key] = [
                            'id' => $key + 1, // Example property: ID, incrementing from 1
                            'text' => $li->textContent, // The text content of each <li> tag
                        ];
                    }

                    $skill->instruction = $liArray;

                }


                return $skill;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $skills
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active skill found',
                'data' => (object)[]
            ], 404);
        }
    }


    /* GET ACTIVE SKILL BY ID */
    public function getActiveSkillById($id)
    {

        $activeSkill = Skill::where(['status' => "active", "id" => $id])->get();
        
        if (!$activeSkill->isEmpty()) {

            // Transform the sponsors data to include full image URL
            $skills = $activeSkill->map(function ($skill) {
                $skill->featured_image = (!empty($skill->featured_image))? url($skill->featured_image): ''; // Assuming 'image' is the field that contains the image path

                if(!empty($skill->instruction)){
                    $instructionContent = $skill->instruction;

                    // Load the content into a DOMDocument object
                    $dom = new DOMDocument();
                    libxml_use_internal_errors(true); // To suppress warnings for malformed HTML
                    $dom->loadHTML($instructionContent);
                    libxml_clear_errors();

                    // Get all <li> tags
                    $liTags = $dom->getElementsByTagName('li');

                    // Initialize an array to store the list items
                    $liArray = [];

                    foreach ($liTags as $key => $li) {
                        // Get the text content of each <li> tag
                        $liArray[$key] = [
                            'id' => $key + 1, // Example property: ID, incrementing from 1
                            'text' => $li->textContent, // The text content of each <li> tag
                        ];
                    }

                    $skill->instruction = $liArray;

                }


                return $skill;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $skills
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active skill found',
                'data' => (object)[]
            ], 404);
        }
    }

    public function getSponsors(){

        $getSponsors = Sponsors::where('status', 'active')->get();
        
        if (!$getSponsors->isEmpty()) {

            // Transform the sponsors data to include full image URL
            $sponsors = $getSponsors->map(function ($sponsor) {
                $sponsor->image = url($sponsor->image); // Assuming 'image' is the field that contains the image path
                return $sponsor;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $sponsors
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active sponsors found',
                'data' => (object)[]
            ], 404);
        }

    }

    public function submitScore(Request $request){

        $validator = Validator::make($request->post(), [
            'skill_id' => 'required',
            'score' => 'required|numeric',
            'time_duration' => 'required',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480', // 20MB max size
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $getProvince = Child::select('id','province_id')->where('id',$request->student_id)->first();      

        // Save video if provided
        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $video->move(public_path('uploads/video'), $videoName);
            $videoPath = 'uploads/video/' . $videoName;
        }   

        // Convert time_duration to HH:MM:SS format
        $timeParts = explode(':', $request->time_duration);
        $minutes = $timeParts[0];
        $seconds = $timeParts[1];

        // Create a Carbon instance for accurate conversion to TIME format
        $timeDuration = Carbon::createFromTime(0, $minutes, $seconds)->format('H:i:s');
       
        $score = Score::create([
            'skill_id' => $request->skill_id,          
            'student_id' => $request->student_id,           
            'province_id' => ((!empty($getProvince->province_id))?$getProvince->province_id:0),
            'score' => $request->score,           
            'time_duration' => $timeDuration,       
            'video' => $videoPath, // Store video path in the database if applicable         
        ]);

        $student = Child::find($score->student_id);
        $skill = Skill::find($score->skill_id);
        $templateDetails = EmailTemplate::find(3);

        if (!empty($student)) {
            $placeholders = [
                '{{firstname}}' => ucfirst($student->firstname),
                '{{lastname}}' => ucfirst($student->lastname),
                '{{score}}' => $score->score ?? "",
                '{{duration}}' => $score->time_duration ?? "",
                '{{skill_name}}' => $skill->name ?? "",
            ];

            $messageBody = str_replace(
                array_keys($placeholders),
                array_values($placeholders),
                $templateDetails->template_message
            );

            $mailData = [
                'student_name' => ucfirst($student->firstname) . ' ' . ucfirst($student->lastname) . ',',
                'body' => $messageBody,
                'subject' => $templateDetails->template_subject ?? "",
            ];

            Mail::to($student->email)->send(new SkillTestMail($mailData));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Score Submited Successfully!',
            'data' => $score
        ],200);
    }

    public function getActiveRankings(){  

        // $getRanking = Score::with('skills')->where('status', 'accept')->get()->groupBy('skill_id');
        $getRanking = Score::with(['skills' => function ($query) {
            $query->orderBy('position', 'asc');
        }])
        ->where('status', 'accept')
        ->get()
        ->sortBy(function($score) {
            return $score->skills->position;
        })
        ->groupBy('skill_id');
        
        $rankings = [];
    
        foreach ($getRanking as $skillId => $scores) {
            $firstRanking = $scores->first(); 
    
            $rankings[] = [
                'skill_id' => $firstRanking->skill_id ?? '',
                'skill_name' => $firstRanking->skills->name ?? '',
            ];
        }

        if (!empty($rankings)) {
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $rankings
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No active rankings found',
                'data' => (object)[]
            ], 404);
        }
 
    }

    public function getActiveRankingsById(Request $request){

        $validator = Validator::make($request->post(), [
            'skill_id' => 'required'
        ]);

        if ($validator->fails()) {  

            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);

        }

        $children_information = Score::with('child')
        ->where(['skill_id' => $request->skill_id, 'status'=>'accept'])
        ->when(!empty($request->province_id) && $request->province_id != 0, function ($query) use ($request) {
            // return $query->where('province_id', $request->province_id);
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('province_id', $request->province_id);
            });
        })
        ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('school_id', $request->school_id);
            });
        })
        ->when(!empty($request->role) && !empty($request->user_id) && $request->my_own_info == 1 , function ($query) use ($request) {

            if($request->role == 'children'){

                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('id', $request->user_id);
                });

            }else if($request->role == 'guardian'){

                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('parent_id', $request->user_id);
                });

            }
            
        })
        ->orderByDesc('score') // Order by score in descending order
        ->get();

        foreach ($children_information as $skillId => $scores) {

            $rankings[] = [
                'child_name' => $scores->child->firstname.' '.$scores->child->lastname,
                'score' => $scores->score.'%' ?? '',
            ];
        }

        if (!empty($rankings)) {
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $rankings,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No active rankings found',
                'data' => (object)[],
            ], 404);
        }
    }

    public function getProfileById(Request $request){

        try{

            $validator = Validator::make($request->post(), [
                'user_id' => 'required',
                'role' => 'required'
            ]);

            if ($validator->fails()) {  

                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);

            }

            $userData = [];

            if($request->role == "guardian"){

                $userData = User::where('id', $request->user_id)->get();

            }else{

                $userData = Child::where('id', $request->user_id)->get();
            }

            if (!$userData->isEmpty()) {         

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile retrieved successfully',
                    'data' => $userData
                ],200);         

            }else{

                return response()->json([
                    'status' => 'error',
                    'message' => 'Profile not found',
                    'data' => (object)[]
                ], 404);
            } 
            

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the profile'
            ], 500);

        }
    }

    public function getGuardianProfile(Request $request){

        try{

            $validator = Validator::make($request->post(), [
                'user_id' => 'required'
            ]);

            if ($validator->fails()) {  

                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);

            }
        
            $userData = User::where('id', $request->user_id)->get();
            
            foreach ($userData as $guardian) {
                $guardian->image = (!empty($guardian->image)) ? url($guardian->image) : '';
            }

            if (!$userData->isEmpty()) {         

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile retrieved successfully',
                    'data' => $userData
                ],200);         

            }else{

                return response()->json([
                    'status' => 'error',
                    'message' => 'Profile not found',
                    'data' => (object)[]
                ], 404);
            } 
            

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the profile'
            ], 500);

        }
    }

    public function getChildrenProfile(Request $request){

        try{

            $validator = Validator::make($request->post(), [
                'user_id' => 'required'
            ]);

            if ($validator->fails()) {  

                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);

            }
        
            $userData = Child::where('id', $request->user_id)->get();
            
            foreach ($userData as $child) {
                $child->image = (!empty($child->image)) ? url($child->image) : '';
            }

            if (!$userData->isEmpty()) {         

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile retrieved successfully',
                    'data' => $userData
                ],200);         

            }else{

                return response()->json([
                    'status' => 'error',
                    'message' => 'Profile not found',
                    'data' => (object)[]
                ], 404);
            } 
            

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the profile'
            ], 500);

        }
    }

    public function getChildrensByParentId(Request $request){

        try{

            $validator = Validator::make($request->post(), [
                'parent_id' => 'required'
            ]);

            if ($validator->fails()) {  

                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);

            }          

            $userData = Child::where('parent_id', $request->parent_id)->get();
           
            foreach ($userData as $child) {
                $child->image = (!empty($child->image)) ? url($child->image) : '';
            }
            
            if (!$userData->isEmpty()) {         
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Children data retrieved successfully',
                    'data' => $userData
                ],200);         

            }else{

                return response()->json([
                    'status' => 'error',
                    'message' => 'Children not found',
                    'data' => (object)[]
                ], 404);
            } 
            

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the profile'
            ], 500);

        }
    }

    public function guardianProfileUpdate(Request $request){

        $validator = Validator::make($request->post(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id . '|unique:children,email',
            'phone' => 'required|numeric', 
            'image' => 'nullable|mimes:jpeg,jpg,bmp,png'  
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        if(empty($request['password'])){
            unset($request['password']);
        }
        $input = $request->all();

        

        if (!empty($request->user_id)) {
            $user = User::find($request->user_id);            

            if (!empty($user)) {

                if($file = $request->file('image')){
                    if (!empty($user['image'])) {
                        @unlink($user['image']);
                    }
                    $input['image'] = $this->fileMove($file,'users');
                }

                $user->update($input);

                $user->image = (!empty($user->image))? url($user->image): '';
                return response()->json([
                    'status' => 'success',
                    'message' => 'Guardian Updated Successfully!',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found!',
                    'data' => (object)[]
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Update Guardian!',
                'data' => (object)[]
            ], 200);
        }
    }

    public function childrenProfileUpdate(Request $request){

        $validator = Validator::make($request->post(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:children,username,' . $request->user_id . '|unique:users,username',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'date_of_birth' => 'required',
            'province_id' => 'required', 
            'school_id' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,bmp,png'
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        if(empty($request['password'])){
            unset($request['password']);
        }
        $input = $request->all();

        

        if (!empty($request->user_id)) {
            $children = Child::find($request->user_id);
            if (!empty($children)) {

                if($file = $request->file('image')){
                    if (!empty($children['image'])) {
                        @unlink($children['image']);
                    }
                    $input['image'] = $this->fileMove($file,'users');
                }
                
                $children->update($input);
                $children->image = (!empty($children->image))? url($children->image): '';
                return response()->json([
                    'status' => 'success',
                    'message' => 'Children Updated Successfully!',
                    'data' => $children
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Children not found!',
                    'data' => (object)[]
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Update Children!',
                'data' => (object)[]
            ], 200);
        }
    }

    public function multipleChildrenProfileUpdate(Request $request){


        $childrenData = $request->post('children');    
     
        // $childrenData = json_decode($childrenData, true); 
        

        if (!is_array($childrenData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data format, expected an array of children data.',
                'data' => (object)[]
            ], 200);
        }

        $updatedChildren = [];
        $errors = [];
        $newChildren = []; 

        foreach ($childrenData as $key => $childData) {
            $validator = Validator::make($childData, [              
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required|unique:children,username,' . $childData['user_id'] . '|unique:users,username',
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'date_of_birth' => 'required',
                'province_id' => 'required',
                'school_id' => 'required',
                // 'image' => 'nullable|mimes:jpeg,jpg,bmp,png'
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'user_id' => $childData['user_id'] ?? 0,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            $input = $childData;
            // $input['date_of_birth'] = Carbon::createFromFormat('Y/m/d', $childData['date_of_birth'])->format('Y-m-d');
            $input['date_of_birth'] = date('Y-m-d',strtotime($childData['date_of_birth']));
            
            $child = Child::find($childData['user_id']);

            if($childData['user_id'] == 0){                

                /* INSERT */           

                // if ($request->hasFile("children.$key.image")) {
                //     // if($file = $request->file('image')){                       
                //     //     $input['image'] = $this->fileMove($file,'users');
                //     // }

                //     if ($request->hasFile('children.' . array_search($childData, $childrenData) . '.image')) {
                //         $file = $request->file('children.' . array_search($childData, $childrenData) . '.image');       
                    
                //         $input['image'] = $this->fileMove($file,'users');
                //     }
                // }

                // if ($request->hasFile("children.$key.image") && $request->file("children.$key.image")->isValid()) {
                //     $file = $request->file("children.$key.image");
                //     $input['image'] = $this->fileMove($file, 'users');
                // }
                
                $insertData = [
                    'parent_id' => $request->parent_id,
                    'firstname' => $childData['firstname'],
                    'lastname' => $childData['lastname'],
                    'email' => $childData['email'],
                    'username' => $childData['username'],
                    'phone' => $childData['phone'],
                    'date_of_birth' => $input['date_of_birth'],
                    'province_id' => (($childData['province_id'])?$childData['province_id']:0),
                    'school_id' => (($childData['school_id'])?$childData['school_id']:0),
                    'looking_sponsor' => $childData['looking_sponsor'],
                ];

                if (!empty($childData['password'])) {                 
                    $insertData['password'] = bcrypt($childData['password']);
                } else {                 
                    unset($insertData['password']);
                }
                
                $createChild = Child::create($insertData); 
                
                $child = Child::find($createChild->id);

                $newChildren[] = $createChild->id; // Collect new children data
              
                $updatedChildren[] = $child;

            }else{

                if (!empty($child) && $childData['user_id'] > 0) {

                    /* UPDATE */
                    if (empty($input['password'])) {
                        unset($input['password']); // Remove the password key from input if it's empty
                    } else {
                        // Optionally, hash the password here if it was provided and not empty
                        $input['password'] = $input['password'];
                    }

                    // if ($request->hasFile("children.$key.image")) {
                       
                    //     // if($file = $request->file('image')){
                    //     //     if (!empty($user['image'])) {
                    //     //         @unlink($user['image']);
                    //     //     }
                    //     //     $input['image'] = $this->fileMove($file,'users');
                    //     // }

                    //     if ($request->hasFile('children.' . array_search($childData, $childrenData) . '.image')) {
                    //         $file = $request->file('children.' . array_search($childData, $childrenData) . '.image');
            
                    //         if (!empty($child->image)) {
                    //             @unlink($child['image']);
                    //         }

                    //         $input['image'] = $this->fileMove($file,'users');
                    //     }
                        
                    // }else{
                    //     $child->image = $child->image;
                    // }

                    // if ($request->hasFile("children.$key.image")) {
                    //     $files = $request->file("children.$key.image");


                    //     // Handle single file
                    //     if (!is_array($files)) {
                    //         $files = [$files];  // Convert to array for consistent handling
                    //     }


                    //     // Loop through each file
                    //     foreach ($files as $file) {
                    //         if ($file->isValid()) {
                    //             // Delete existing image if exists
                    //             if (!empty($child->image)) {
                    //                 @unlink(public_path($child->image));
                    //             }

                    //             // Store the new image
                    //             $input['image'] = $this->fileMove($files, 'users');
                    //         }
                    //     }


                    //     // if (!empty($child->image)) {
                    //     //     @unlink(public_path($child->image));
                    //     // }
                    //     // $input['image'] = $this->fileMove($file, 'users');
                    // }    

                    // $input['image'] = (!empty($input['image']))?$input['image']:$child->image;
                    $child->update($input);
                    // $child->image = $child->image ? url($child->image) : '';   
                                
                    // Check payment status for the child
                    $payment = Payment::select('status')->where('child_id', $child->id)->first();
                    if(!empty($payment)){
                        $paymentStatus = $payment->status ?? 'pending';
                        if($paymentStatus == 'pending'){
                            $newChildren[] =  $child->id; // Collect new children data
                        }
                    }else{
                        $newChildren[] =  $child->id; // Collect new children data
                    }                    

                    $updatedChildren[] = $child;

                } else {
                    $errors[] = [
                        'user_id' => $childData['user_id'],
                        'errors' => ['Child not found']
                    ];
                }
            }
        }
        

        if (count($errors) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some children could not be updated.',
                'errors' => $errors,
                'data' => $updatedChildren
            ], 200);
        }

        if (count($newChildren) > 0) {

            $payment_url = $this->getPayfastPaymentUrlForMultipleChild($newChildren);

            return response()->json([
                'status' => 'pending',
                'message' => 'Children Payment Pending',
                'data' => $payment_url
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Children Updated Successfully!',
            'data' => $updatedChildren
        ], 200);
    }

    public function getFees()
    {
        $fees = Fee::pluck('fees')->first();
        if (!empty($fees)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Fees retrieved successfully',
                'data' => [
                    'fees' => $fees
                ]
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'No fees found',
                'data' => (object)[]
            ], 404);
        }
    }
    
    public function getChildPayment(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->post(), [
                'user_id' => 'required',
                'role' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(), // Returns first error message
                    'data' => (object)[]
                ], 200);
            }

            // Retrieve child data
            $child = Child::select('id', 'parent_id')->find($request->user_id);

            if ($child) {
                // Check payment status for the child
                $payment = Payment::select('status')->where('child_id', $child->id)->first();
                $paymentStatus = $payment->status ?? 'pending';
                $paymentURL = $this->getPayfastPaymentUrl($child->id);

                // Construct response data
                $childData = [
                    'child_id' => $child->id,
                    'status' => $paymentStatus,
                    'payment_url' => $paymentStatus === 'pending' ? $paymentURL : null // Payment URL only if pending
                ];

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment data retrieved successfully',
                    'data' => $childData
                ], 200);
            }

            // Child not found
            return response()->json([
                'status' => 'error',
                'message' => 'Child data not found',
                'data' => (object)[]
            ], 404);

        } catch (Exception $e) {
            // Error handling
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the Payment',
                'error' => $e->getMessage() // Include exception message for debugging
            ], 500);
        }
    }

    public function getChildPaymentNew(Request $request){

        try{

            $validator = Validator::make($request->post(), [
                'user_id' => 'required',
                'role' => 'required'
            ]);

            if ($validator->fails()) {  

                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => (object)[]
                ], 200);

            }

            // Define $userData based on the role
            if ($request->role == 'guardian') {
                $userData = User::select('id', 'role')->where('id', $request->user_id)->where('role', 'guardian')->first();
            } else {
                $userData = Child::select('id', 'parent_id')->where('id', $request->user_id)->first();
            }          
           
            if ($userData) {         

                $newChildren = []; 
                $paymentURL = "";

                $child = $userData;

                if ($request->role == 'guardian') {
                  
                    $getChildData = Child::select('id', 'parent_id')->where('parent_id', $request->user_id)->get();

                    if(!empty($getChildData)){
                        foreach ($getChildData as $key => $value) {

                            $checkChildData = Payment::select('status')->where('child_id', $value->id)->first();
                            if(!empty($checkChildData)){

                                /* CHECK IF PAYMENT PENDING */
                                if($checkChildData->status == "pending"){
                                    $newChildren[] =  $value->id;
                                }
                                
                            }else{
                                $newChildren[] =  $value->id;
                            }
                        }
                    }

                    if (count($newChildren) > 0) {

                        $payment_url = $this->getPayfastPaymentUrlForMultipleChild($newChildren);
            
                        return response()->json([
                            'status' => 'pending',
                            'message' => 'Children Payment Pending',
                            'data' => $payment_url
                        ], 200);

                    }else{

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Children Payment Paid',
                            'data' => []
                        ], 200);
                    }

                }else{

                    $checkUserData = Payment::select('status')->where('child_id', $child->id)->get()->first();

                    if(!empty($checkUserData)){
                   
                        if($checkUserData->status == "pending"){

                            $paymentURL = $this->getPayfastPaymentUrl($child->id);

                            $childData = array(
                                'child_id' => $child->id,
                                'status' => $checkUserData->status,
                                'payment_url' => $paymentURL
                            );

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Payment data retrieved successfully',
                                'data' => $childData
                            ],200);         
            
                        }else{

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Payment Paid',
                                'data' => []
                            ], 200);
                        }

                    }else{

                        $paymentURL = $this->getPayfastPaymentUrl($child->id);

                        $childData = array(
                            'child_id' => $child->id,
                            'status' => 'pending',
                            'payment_url' => $paymentURL
                        );

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Payment data retrieved successfully',
                            'data' => $childData
                        ],200);         
                    }
                }    

            }else{

                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found',
                    'data' => (object)[]
                ], 404);
            } 
            

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the Payment'
            ], 200);

        }
    }

    public function getPayfastPaymentUrl($user_id){

        $payfastPaymentUrl = "";
        try{

            $fees = Fee::pluck('fees')->first();

            $userData = Child::select('id','parent_id','firstname','lastname','email')->where('id', $user_id)->first();

            if($userData){
                $fee = (isset($fees)) ? $fees : '';
                $amount = str_replace(',', '', $fee);
                $amount = floatval($amount);
                $formattedAmount = number_format($amount, 2, '.', '');

                $name = (isset($userData->firstname) && !empty($userData->firstname)) ? $userData->firstname : '';

                $email = (isset($userData->email) && !empty($userData->email)) ? $userData->email : '';

                $mPaymentId = $user_id.(string) Str::uuid();
                $userId = (isset($userData->id) && !empty($userData->id)) ? $userData->id : '';
                $parentId = (isset($userData->parent_id) && !empty($userData->parent_id)) ? $userData->parent_id : '';


                $paymentData = [
                    'totAmtToPay' => $formattedAmount,
                    'name' => $name,
                    'email' => $email,
                    'mPaymnentId' => $mPaymentId,
                    'child_id' => $userId,
                    'parent_id' => $parentId,
                    // 'payment_method' => $request->payment_method,
                ];           

                $payfastPaymentUrl = getPaymentUrl($paymentData);            
                return $payfastPaymentUrl;
            }
        }catch (Exception $e) {
            return $payfastPaymentUrl;
        }
    }

    public function commonSiginSignup(Request $request){

        $validator = Validator::make($request->post(), [
            'action_type' => 'required', /* signin or signup */
            'user_id' => 'required' /* child id */
        ]);

        if ($validator->fails()) {  

            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);

        }       

        if($request->action_type == 'signin'){

            // Create a new Request object with email and password
            $loginRequest = Request::create('/api/login', 'POST', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $response = $this->authController->login($loginRequest);

            // Extract the original data from the response object
            $originalData = $response->getData();

            if($originalData->status == "success"){

                $childData = [
                    'parent_id' => $originalData->data->id
                ];

                $userData = Child::where('id', $request->user_id)->update($childData);
                
                /* GET ALL CHILDS BY PARENT ID */
                $parentId = $originalData->data->id;
                $getChildData = Child::where('parent_id', $parentId)->where('status','active')->select('id','firstname','lastname','email','phone')->get();

                /* GET FEES */
                $fees = Fee::pluck('fees')->first();

                $checkUserData = Payment::select('status')->where('child_id', $request->id)->get()->first();
                
                $paymentURL = "";
              
                if(!empty($checkUserData)){

                    /* CHECK IF PAYMENT PENDING */
                    if($checkUserData->status == "pending"){
                        $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                        $childData = array(  
                            'parent_id' => $parentId,
                            'amount' => $fees,                      
                            'status' => $checkUserData->status,
                            'payment_url' => $paymentURL,
                            'childs' => $getChildData
                        );
                    }

                }else{

                    $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                    $childData = array(
                        'parent_id' => $parentId,
                        'amount' => $fees,  
                        'status' => 'pending',
                        'payment_url' => $paymentURL,
                        'childs' => $getChildData
                    );
                }  

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment data retrieved successfully',
                    'data' => $childData
                ],200);
            }      

            return response()->json($originalData,200);  

        }else if($request->action_type == 'signup'){
         
            // Create a new Request object with signup data
            $signupRequest = Request::create('/api/register', 'POST', [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'terms' => $request->terms,
            ]);

            $response = $this->authController->register($signupRequest);
            
            // Extract the original data from the response object
            $originalData = $response->getData();

            if($originalData->status == "success"){

                $childData = [
                    'parent_id' => $originalData->data->id
                ];
                $userData = Child::where('id', $request->user_id)->update($childData);

                /* GET ALL CHILDS BY PARENT ID */
                $parentId = $originalData->data->id;
                $getChildData = Child::where('parent_id', $parentId)->where('status','active')->select('id','firstname','lastname','email','phone')->get();

                /* GET FEES */
                $fees = Fee::pluck('fees')->first();

                $checkUserData = Payment::select('status')->where('child_id', $request->id)->get()->first();
                
                $paymentURL = "";
              
                if(!empty($checkUserData)){

                    /* CHECK IF PAYMENT PENDING */
                    if($checkUserData->status == "pending"){
                        $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                        $childData = array(  
                            'parent_id' => $parentId,
                            'amount' => $fees,                      
                            'status' => $checkUserData->status,
                            'payment_url' => $paymentURL,
                            'childs' => $getChildData
                        );
                    }

                }else{

                    $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                    $childData = array(
                        'parent_id' => $parentId,
                        'amount' => $fees,  
                        'status' => 'pending',
                        'payment_url' => $paymentURL,
                        'childs' => $getChildData
                    );
                }  

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment data retrieved successfully',
                    'data' => $childData
                ],200);
            }

            return response()->json($originalData,200);         

        }

    }

    /* GET PAYFAST PAYMENT URL FOR MULTIPLE CHILD */
    public function getPayfastPaymentUrlForMultipleChild($user_ids){
        $payfastPaymentUrl = "";
        try {
            // Fetch the fee
            $fee = Fee::pluck('fees')->first();
            $amount = str_replace(',', '', $fee);
            $amount = floatval($amount);
    
            // Get data for all children and calculate the total amount
            $totalAmount = 0;
            $childrenData = [];
            
            foreach ($user_ids as $user_id) {
                $userData = Child::select('id', 'parent_id', 'firstname', 'lastname', 'email')
                    ->where('id', $user_id)->first();
                
                if ($userData) {
                    $totalAmount += $amount; // Sum up the fees
                    $childrenData[] = $userData;
                }
            }
    
            // If there are children data, generate the payment URL
            if (!empty($childrenData)) {
                $formattedAmount = number_format($totalAmount, 2, '.', '');
                
                // Use the first child's data for name and email
                $name = $childrenData[0]->firstname;
                $email = $childrenData[0]->email;
    
                // Unique payment ID with the first childÃ¢ÂÂs ID and a UUID
                $mPaymentId = $childrenData[0]->id . (string) Str::uuid();
                $parentId = $childrenData[0]->parent_id;
    
                // Prepare the payment data
                $paymentData = [
                    'totAmtToPay' => $formattedAmount,
                    'name' => $name,
                    'email' => $email,
                    'mPaymnentId' => $mPaymentId,
                    'child_ids' => implode(',', array_column($childrenData, 'id')),
                    'parent_id' => $parentId,
                ];
    
                // Generate the payment URL
                $payfastPaymentUrl = getPaymentUrlForMultiple($paymentData);
                return $payfastPaymentUrl;
            }
        } catch (Exception $e) {
            return $payfastPaymentUrl;
        }
    }

    /* GET ACTIVE CATEGORIES */
    public function getCMSPage($id)
    {
        $getCms = CmsPages::select('id','page_name','page_content')->where('id',$id)->get();        

        if (!$getCms->isEmpty()) {

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $getCms
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active cms page found',
                'data' => (object)[]
            ], 404);
        }
        
    }
}
