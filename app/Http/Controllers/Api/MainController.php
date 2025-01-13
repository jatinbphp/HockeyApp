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
use Carbon\CarbonInterval;



class MainController extends Controller
{
    protected $authController;

    public function __construct(AuthController $authController){

        // Set the injected AuthController to a class property
        $this->authController = $authController;

        $this->middleware('auth:api', [
            'except' => ['login','register','getActiveSchool','getActiveProvince','getSponsors','getActiveSkill','getChildrenProfile','getChildrensByParentId','submitScore','guardianProfileUpdate','childrenProfileUpdate','getActiveSkillById','getGuardianProfile','multipleChildrenProfileUpdate','getActiveRankings','getActiveRankingsById','getChildPayment','getPayfastPaymentUrl','commonSiginSignup','getCMSPage','getSchoolByProvinceId','getGlobalRankings','getChildPaymentNew']
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
                'data' => []
            ], 404);
        }
        
    }

    /* GET ACTIVE School */
    public function getActiveSchool()
    {
        $activeSchool = School::select('id','name','province_id')->where('status', "active")->get();
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
                'data' => []
            ], 404);
        }
        
    }


    public function getSchoolByProvinceId(Request $request)
    {
        $provinceId = $request->province_id;
        if(!empty($request->child_id) && $request->child_id > 0){
            $getProvince = Child::select('province_id')->where('id',$request->child_id)->first();    
            $provinceId = ((!empty($getProvince->province_id))?$getProvince->province_id:0);
        }

        $activeSchool = School::select('id','name')->where('status', "active")->where('province_id',$provinceId)->get();
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
                'data' => []
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
                'data' => []
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
                'data' => []
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
                'data' => []
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
                'data' => []
            ], 404);
        }

    }

    public function submitScoreOLD(Request $request){

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
                'data' => []
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
                'data' => []
            ], 200);
        }

        $getProvince = Child::select('id','province_id')->where('id',$request->student_id)->first();      
        $getSkillData = Skill::select('id','score_field_active','time_field_active')->where('id',$request->skill_id)->first();      

        // Save video if provided
        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $video->move(public_path('uploads/video'), $videoName);
            $videoPath = 'uploads/video/' . $videoName;
        }   

        $childTime = 0;
        $grandTotal = 0;
        $finalTime = 0;

        if($getSkillData->id == 7 || $getSkillData->id == 8){

            if($getSkillData->time_field_active == 0){
                $grandTotal = $this->calculatePoints($request->score);
            }

        }else if($getSkillData->id == 9){

            if($getSkillData->time_field_active == 0){
                $grandTotal = $this->calculateFullRoundPoints($request->score);
            }

        }else{

            if($getSkillData->time_field_active == 0){
                $childTime = 0;
                $grandTotal = $request->score;
            }else{
                 // Convert time_duration to HH:MM:SS format
                $defaultSeconds = 60;
                $childTime = $request->time_duration ?? 0;
                $finalTime = ($defaultSeconds - $childTime);
                $calTotal = ($finalTime + $request->score);
                $grandTotal = ($calTotal > 0)?$calTotal:0;
            }     
        }
    

        $score = Score::create([
            'skill_id' => $request->skill_id,          
            'student_id' => $request->student_id,           
            'province_id' => ((!empty($getProvince->province_id))?$getProvince->province_id:0),
            'score' => $grandTotal,  
            'time_duration' => $finalTime,              
            'submited_score' => $request->score,       
            'submited_time' => $request->time_duration ?? 0,  
            'video' => $videoPath, // Store video path in the database if applicable         
        ]);

        $student = Child::find($score->student_id);
        $skill = Skill::find($score->skill_id);
        $templateDetails = EmailTemplate::find(3);

        if (!empty($student)) {
            $placeholders = [
                '{{firstname}}' => ucfirst($student->firstname),
                '{{lastname}}' => ucfirst($student->lastname),
                '{{score}}' => $grandTotal ?? "",
                '{{duration}}' => $childTime ?? "",
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


    function calculatePoints($rotations) {
        $pointsPerRotation = 10; // Example: 10 points per full rotation
        
        $fullRotations = floor($rotations); // Get full rotations
        $partialRotation = $rotations - $fullRotations; // Get the fractional part
        
        $totalPoints = ($fullRotations * $pointsPerRotation) + ($partialRotation * $pointsPerRotation);
        
        return $totalPoints;
    }
    
    

    function calculateFullRoundPoints($rotations) {
        $pointsPerRotation = 5; // 5 points per full rotation
    
        $fullRotations = floor($rotations); // Get full rotations (no half-points considered)
        $totalPoints = $fullRotations * $pointsPerRotation;
        
        return $totalPoints;
    }
    

    public function getActiveRankings(){  

        // $getRanking = Score::with('skills')->where('status', 'accept')->get()->groupBy('skill_id');
        $getRanking = Score::with(['skills' => function ($query) {
            $query->orderBy('position', 'asc');
        }])
        ->where('status', 'accept')
        ->whereYear('created_at', date('Y'))
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
                'data' => []
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
                'data' => []
            ], 200);

        }

        $children_scores = Score::with('child')
        ->whereHas('child') // Ensure only scores with existing child records are retrieved
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
        ->when(!empty($request->gender), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('gender', $request->gender);
            });
        })
        ->when(!empty($request->age_group), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('age_group', $request->age_group);
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
        ->where('status', 'accept')
        ->whereYear('created_at', date('Y'))
        ->selectRaw('student_id, MAX(score) as highest_score') // Group by student_id and get the max score
        ->groupBy('student_id') 
        ->orderByDesc('highest_score') // Order by score in descending order
        ->get();

        
        // Assign rankings to all children based on their total scores
        $rankings = [];
        $rankingNo = 1;

        foreach ($children_scores as $score) {
            $rankings[] = [
                'ranking_no' => $rankingNo, // Ranking number
                'parent_id' => $score->child->parent_id, // Child ID
                'child_id' => $score->child->id, // Child ID
                'child_name' => $score->child 
                    ? $score->child->firstname . ' ' . $score->child->lastname 
                    : 'Unknown', // Child's name or default
                'age_group' => $score->child?->age_group ?? 'N/A', // Child's age group
                'score' => $score->highest_score ?? 0, // Total score
            ];
            $rankingNo++;
        }

        // Extract the top 10 children
        $top_10_children = collect($rankings)->take(3)->values();

        // If guardian is logged in, handle additional children
        if ($request->role === 'guardian' && !empty($request->user_id)) {
            // Filter rankings to get all children belonging to the guardian
            $guardian_children = collect($rankings)->filter(function ($child) use ($request) {
                return Score::where('student_id', $child['child_id'])
                    ->whereHas('child', function ($query) use ($request) {
                        $query->where('parent_id', $request->user_id);
                    })
                    ->exists();
            });

            // Check for missing children in the top 10 list
            $guardian_children->each(function ($child) use (&$top_10_children) {
                $is_in_top_10 = $top_10_children->contains('child_id', $child['child_id']);
                if (!$is_in_top_10) {
                    $top_10_children->push($child);
                }
            });
        }else{
            // If child is logged in, handle their inclusion
            if ($request->role === 'children' && !empty($request->user_id)) {
                // Find the logged-in child's ranking
                $logged_in_child = collect($rankings)->firstWhere('child_id', $request->user_id);

                if ($logged_in_child && !$top_10_children->contains('child_id', $logged_in_child['child_id'])) {
                    // Add the logged-in child to the end of the top 10 list
                    $top_10_children->push($logged_in_child);
                }
            }
        }

        if (!empty($rankings)) {
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'all_children' => $rankings, // All children data
                'data' => $top_10_children, // Top 10 children data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No active rankings found',
                'data' => [],
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
                    'data' => []
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
                    'data' => []
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
                    'data' => []
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
                    'data' => []
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
                    'data' => []
                ], 200);

            }
        
            $userData = Child::with(['school' => function ($query) {
                $query->select('id', 'name'); // Include only the required fields
            }])->where('id', $request->user_id)->get();
            
            foreach ($userData as $child) {
                $child->image = (!empty($child->image)) ? url($child->image) : '';
                $child->school_name = ($child->school && !empty($child->school->name)) ? $child->school->name : "";
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
                    'data' => []
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
                    'data' => []
                ], 200);

            }          

            $userData = Child::with(['school' => function ($query) {
                $query->select('id', 'name'); // Include only the required fields
            }])->where('parent_id', $request->parent_id)->get();
           
            foreach ($userData as $child) {
                $child->image = (!empty($child->image)) ? url($child->image) : '';
                $child->school_name = ($child->school && !empty($child->school->name)) ? $child->school->name : "";

                /* CHECK CHILD PAYMENT */
                $payment = Payment::select('status','payment_date')->where('child_id', $child->id)->orderBy('payment_date', 'desc')->first();

                if($payment){

                    $paymentStatus = $payment->status ?? 'pending';
                    $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                    $todayDate = Carbon::now();

                    // Check for 1-year renewal period
                    $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                    $paymentStatus = ($paymentStatus === 'pending' || $oneYearRenewal) ? "pending" : "paid";
                    $child->payment_status = $paymentStatus;
                }
               
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
                    'data' => []
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
                'data' => []
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
                    'data' => []
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Update Guardian!',
                'data' => []
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
                'data' => []
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

                $dob = date('Y-m-d',strtotime($request->date_of_birth));
                $input['age_group'] = getAgeGroup($dob);
                
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
                    'data' => []
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Update Children!',
                'data' => []
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
                'data' => []
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
            $dob = date('Y-m-d',strtotime($childData['date_of_birth']));
            $input['age_group'] = getAgeGroup($dob);

            $input['date_of_birth'] = date('Y-m-d',strtotime($childData['date_of_birth']));
            
            $child = Child::find($childData['user_id']);

            if($childData['user_id'] == 0){   
               
                $insertData = [
                    'parent_id' => $request->parent_id,
                    'firstname' => $childData['firstname'],
                    'lastname' => $childData['lastname'],
                    'email' => $childData['email'],
                    'username' => $childData['username'],
                    'phone' => $childData['phone'],
                    'date_of_birth' => $input['date_of_birth'],
                    'gender' => $childData['gender'],
                    'age_group' => $input['age_group'],
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

                    // $input['image'] = (!empty($input['image']))?$input['image']:$child->image;
                    $child->update($input);
                    // $child->image = $child->image ? url($child->image) : '';   
                                
                    // Check payment status for the child
                    $payment = Payment::select('status','payment_date')->where('child_id', $child->id)->orderBy('payment_date', 'desc')->first();

                    if($payment){

                        $paymentStatus = $payment->status ?? 'pending';
                        $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                        $todayDate = Carbon::now();

                        // Check for 1-year renewal period
                        $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                        if($paymentStatus === 'pending' || $oneYearRenewal){
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

            // Get the first validation error message
            $firstErrorMessage = $errors[0]['errors'][0] ?? 'Some children could not be updated.';

            return response()->json([
                'status' => 'error',
                'message' => $firstErrorMessage,
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
                'data' => []
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
                    'data' => []
                ], 200);
            }

            // Retrieve child data
            $child = Child::select('id', 'parent_id')->find($request->user_id);

            if ($child) {
                // Check payment status for the child
                $payment = Payment::select('status','payment_date')->where('child_id', $child->id)->orderBy('payment_date', 'desc')->first();

                if($payment){

                    $paymentStatus = $payment->status ?? 'pending';
                    $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                    $todayDate = Carbon::now();

                    // Check for 1-year renewal period
                    $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                    // Generate payment URL if pending or renewal period expired
                    $paymentURL = ($paymentStatus === 'pending' || $oneYearRenewal) ? $this->getPayfastPaymentUrl($child->id) : null;
                    $paymentStatus = ($paymentStatus === 'pending' || $oneYearRenewal) ? "pending" : "paid";
                    // Construct response data
                    $childData = [
                        'child_id' => $child->id,
                        'status' => $paymentStatus,
                        'payment_url' => $paymentURL
                    ];

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment data retrieved successfully',
                        'data' => $childData
                    ], 200);

                }else{

                    $paymentURL = $this->getPayfastPaymentUrl($child->id);

                    $childData = [
                        'child_id' => $child->id,
                        'status' => 'pending',
                        'payment_url' => $paymentURL
                    ];

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment data retrieved successfully',
                        'data' => $childData
                    ], 200);

                }
                
            }

            // Child not found
            return response()->json([
                'status' => 'error',
                'message' => 'Child data not found',
                'data' => []
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

    public function getChildPaymentNew(Request $request)
    {
        try {
            
            $validator = Validator::make($request->post(), [
                'user_id' => 'required',
                'role' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => implode(',', $validator->errors()->all()),
                    'data' => []
                ], 200);
            }

            $fees = Fee::pluck('fees')->first();
            $fee = (isset($fees)) ? $fees : '';
            $amount = str_replace(',', '', $fee);
            $amount = floatval($amount);
            $formattedAmount = number_format($amount, 2, '.', '');

            // Fetch user/child data based on role
            $userData = ($request->role === 'guardian')
                ? User::select('id', 'role')->where('id', $request->user_id)->where('role', 'guardian')->first()
                : Child::select('id', 'parent_id')->where('id', $request->user_id)->first();

            if (!$userData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User or child not found',
                    'data' => []
                ], 404);
            }

            if ($request->role === 'guardian') {

                $children = Child::select('id', 'parent_id')->where('parent_id', $request->user_id)->get();
                $pendingChildren = [];

                foreach ($children as $child) {

                    $payment = Payment::select('status','payment_date')->where('child_id', $child->id)->orderBy('payment_date', 'desc')->first();

                    if($payment){

                        $paymentStatus = $payment->status ?? 'pending';
                        $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                        $todayDate = Carbon::now();

                        // Check for 1-year renewal period
                        $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                        if($paymentStatus === 'pending' || $oneYearRenewal){
                            $pendingChildren[] = $child->id;
                        }

                    }else{
                        $pendingChildren[] = $child->id;
                    }
                }

                if (!empty($pendingChildren)) {

                    $unpaidChildList = Child::select('id', 'parent_id', 'firstname', 'lastname')
                    ->whereIn('id', $pendingChildren)
                    ->get();

                    $unpaidChildData = [];
                    $totalAmount = "0";

                    if($unpaidChildList){
                        foreach ($unpaidChildList as $key => $value) {
                            $unpaidChildData[] = [
                                'full_name' => $value->firstname." ".$value->lastname,
                                'amount' => $formattedAmount 
                            ];

                            $totalAmount += $formattedAmount;
                        }
                    }                   

                    $paymentURL = $this->getPayfastPaymentUrlForMultipleChild($pendingChildren);

                    $childData = array(
                        'unpaid_child' => $unpaidChildData,
                        'total_amount' => number_format($totalAmount, 2, '.', ''),
                        'status' => 'pending',
                        'payment_url' => $paymentURL
                    );

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Children\'s payment is still pending. Please complete the payment to continue.',
                        'data' => [$childData]
                    ], 200);

                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'All children payments are paid.',
                    'data' => []
                ], 200);

            }else{

                $payment = Payment::select('status','payment_date')->where('child_id', $request->user_id)->orderBy('payment_date', 'desc')->first();

                $unpaidChildList = Child::select('id', 'parent_id', 'firstname', 'lastname')
                ->where('id', $request->user_id)
                ->first();

                $unpaidChildData = [
                    'full_name' => $unpaidChildList->firstname." ".$unpaidChildList->lastname,
                    'amount' => $formattedAmount 
                ];

                $totalAmount = $formattedAmount;

                $checkPaidStatus = "0";

                if($payment){

                    $paymentStatus = $payment->status ?? 'pending';
                    $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                    $todayDate = Carbon::now();

                    // Check for 1-year renewal period
                    $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                    if($paymentStatus === 'pending' || $oneYearRenewal){
                        
                        $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                        $childData = array(
                            'unpaid_child' => [$unpaidChildData],
                            'total_amount' => $totalAmount,
                            'status' => 'pending',
                            'payment_url' => $paymentURL
                        );

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Payment is still pending. Please complete the payment to continue.',
                            'data' => [$childData]
                        ],200);     

                    }

                    $checkPaidStatus = "1";

                }else{
                    
                    $paymentURL = $this->getPayfastPaymentUrl($request->user_id);

                    $childData = array(
                        'unpaid_child' => [$unpaidChildData],
                        'total_amount' => $totalAmount,
                        'status' => 'pending',
                        'payment_url' => $paymentURL
                    );

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment is still pending. Please complete the payment to continue.',
                        'data' => [$childData]
                    ],200);     

                    $checkPaidStatus = "0";
                }

                if($checkPaidStatus = "1"){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payments are paid.',
                        'data' => []
                    ], 200);
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the payment: ' . $e->getMessage(),
                'data' => []
            ], 500);
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
                'data' => []
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

                $checkUserData = Payment::select('status','payment_date')->where('child_id', $request->id)->get()->first();
                
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
                'data' => []
            ], 404);
        }
        
    }


    public function getGlobalRankingsOLD(Request $request){

        $skills = Skill::pluck('id');

        $children_scores = Score::with('child')
        ->whereHas('child') // Ensure only scores with existing child records are retrieved
        ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('school_id', $request->school_id);
            });
        })
        ->when(!empty($request->gender), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('gender', $request->gender);
            });
        })
        ->when(!empty($request->age_group), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('age_group', $request->age_group);
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
        ->selectRaw('student_id, skill_id, MAX(score) as max_score')
        ->groupBy('student_id', 'skill_id') // Group by student and skill to get max score per skill
        ->get();

        $grouped_scores = $children_scores->groupBy('student_id');

        $completed_children = [];
        $incomplete_children = [];
        $highest_scores = []; // To store highest scores for each skill
        
        foreach ($grouped_scores as $child_id => $scores) {
            $child = $scores->first()->child; // Access the child record (firstname, age_group)
            $scored_skills = $scores->pluck('skill_id')->toArray();
            $missing_skills = $skills->diff($scored_skills);
            $total_score = $scores->sum('max_score'); // Sum of max scores for the child
        
            // Update the highest score per skill
            foreach ($scores as $score) {
                $skill_id = $score['skill_id'];
                $highest_scores[$skill_id] = max($highest_scores[$skill_id] ?? 0, $score['max_score']);
            }
        
            $child_data = [
                'child_name' => $child->firstname .' '.$child->lastname,
                'age_group' => $child->age_group,
                'score' => $total_score,
            ];
        
            if ($missing_skills->isNotEmpty()) {
                $child_data['missing_skills'] = $missing_skills;
                $child_data['completed_skills'] = $scored_skills;
                $incomplete_children[$child_id] = $child_data;
            } else {
                $child_data['completed_skills'] = $scored_skills;
                $completed_children[$child_id] = $child_data;
            }
        }
        
        // Sort completed children by total_score in descending order
        $sorted_completed_children = collect($completed_children)
            ->sortByDesc('score')
            // ->take(10) // Take only the top 10
            ->values()
            ->map(function ($child, $index) {
                $child['ranking_no'] = $index + 1; // Assign rank
                return $child;
            });
        
        // Calculate the overall highest score
        $overall_highest_score = !empty($highest_scores) ? max($highest_scores) : 0;
        
        return response()->json([
            'status' => 'success',
            'data' => $sorted_completed_children,
            // 'incomplete_children' => $incomplete_children,
            // 'highest_scores_per_skill' => $highest_scores,
            // 'overall_highest_score' => $overall_highest_score,
        ]);
     
    }


    public function getGlobalRankings(Request $request){

        $skills = Skill::pluck('id');

        $children_scores = Score::with('child')
        ->whereHas('child') // Ensure only scores with existing child records are retrieved
        ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('school_id', $request->school_id);
            });
        })
        ->when(!empty($request->gender), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('gender', $request->gender);
            });
        })
        ->when(!empty($request->age_group), function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('age_group', $request->age_group);
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
        ->where('status', 'accept')
        ->selectRaw('student_id, skill_id, MAX(score) as max_score')
        ->whereYear('created_at', date('Y'))
        ->groupBy('student_id', 'skill_id') // Group by student and skill to get max score per skill
        ->get();

        // Group scores by student
        $grouped_scores = $children_scores->groupBy('student_id');

        // Process each student's scores
        $all_children = [];
        $highest_scores = []; // To store highest scores for each skill

        foreach ($grouped_scores as $child_id => $scores) {
            $child = $scores->first()->child; // Access the child record (firstname, age_group)
            $scored_skills = $scores->pluck('skill_id')->toArray();
            $missing_skills = $skills->diff($scored_skills);
            $total_score = $scores->sum('max_score'); // Sum of max scores for the child

            // Update the highest score per skill
            foreach ($scores as $score) {
                $skill_id = $score['skill_id'];
                $highest_scores[$skill_id] = max($highest_scores[$skill_id] ?? 0, $score['max_score']);
            }

            // Only include children who have completed all skills
            if ($missing_skills->isEmpty()) {
                $all_children[$child_id] = [
                    'child_id' => $child->id,
                    'parent_id' => $child->parent_id,
                    'child_name' => $child->firstname . ' ' . $child->lastname,
                    'age_group' => $child->age_group,
                    'score' => $total_score,
                    'completed_skills' => $scored_skills,
                ];
            }
        }

        // Sort all children by total_score in descending order
        $ranked_children = collect($all_children)
            ->sortByDesc('score')
            ->values()
            ->map(function ($child, $index) {
                $child['ranking_no'] = $index + 1; // Assign rank
                return $child;
            });

        // Extract the top 10 children
        $top_10_children = $ranked_children->take(3);
        

        // Handle child login
        if ($request->role === 'children' && !empty($request->user_id)) {
            $logged_in_child = $ranked_children->firstWhere('child_id', $request->user_id);
            if ($logged_in_child && !$top_10_children->contains('child_id', $logged_in_child['child_id'])) {
                $top_10_children->push($logged_in_child);
            }
        }

        // Handle guardian login
        if ($request->role === 'guardian' && !empty($request->user_id)) {
            $guardian_children = $ranked_children->where('parent_id', $request->user_id);
            $guardian_children_not_in_top_10 = $guardian_children->reject(function ($child) use ($top_10_children) {
                return $top_10_children->contains('child_id', $child['child_id']);
            });

            foreach ($guardian_children_not_in_top_10 as $child) {
                $top_10_children->push($child);
            }
        }


        // Return the response
        return response()->json([
            'status' => 'success',
            'all_children' => $ranked_children, // Full list with rankings
            'data' => $top_10_children->values(), // Top 10 children including the logged-in child if applicable
        ]);
     
    }
}
