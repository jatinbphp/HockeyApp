<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
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
use App\Models\Fee;
use App\Models\EmailTemplate;
use DOMDocument;

class MainController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', [
            'except' => ['login','register','getActiveSchool','getActiveProvince','getSponsors','getActiveSkill','getChildrenProfile','getChildrensByParentId','submitScore','guardianProfileUpdate','childrenProfileUpdate','multipleChildrenProfileUpdate','getActiveRankings','getActiveRankingsById','getFees']
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
        $activeSkill = Skill::where('status', "active")->get();
        
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
            'time_duration' => 'required'
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $getProvince = Child::select('id','province_id')->where('id',$request->student_id)->first();      
       
        $score = Score::create([
            'skill_id' => $request->skill_id,          
            'student_id' => $request->student_id,           
            'province_id' => $getProvince->province_id,
            'score' => $request->score,           
            'time_duration' => $request->time_duration,           
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

        $getRanking = Score::with('skills')->where('status', 'accept')->get()->groupBy('skill_id');
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
            return $query->where('province_id', $request->province_id);
        })
        ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
            return $query->whereHas('child', function ($query) use ($request) {
                $query->where('school_id', $request->school_id);
            });
        })
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

            if($file = $request->file('image')){
                if (!empty($user['image'])) {
                    @unlink($user['image']);
                }
                $input['image'] = $this->fileMove($file,'users');
            }

            if (!empty($user)) {
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
            'username' => 'required',
            'email' => 'required|email|unique:children,email,' . $request->user_id . '|unique:users,email',
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

            if($file = $request->file('image')){
                if (!empty($children['image'])) {
                    @unlink($children['image']);
                }
                $input['image'] = $this->fileMove($file,'users');
            }

            if (!empty($children)) {
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

        if (!is_array($childrenData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data format, expected an array of children data.',
                'data' => (object)[]
            ], 200);
        }

        $updatedChildren = [];
        $errors = [];

        foreach ($childrenData as $childData) {
            $validator = Validator::make($childData, [              
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required',
                'email' => 'required|email|unique:children,email,' . $childData['user_id'] . '|unique:users,email',
                'phone' => 'required|numeric',
                'date_of_birth' => 'required',
                'province_id' => 'required',
                'school_id' => 'required',
                'image' => 'nullable|mimes:jpeg,jpg,bmp,png'
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'user_id' => $childData['user_id'] ?? null,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            $input = $childData;

            $child = Child::find($childData['user_id']);
            if ($child) {
                if (empty($input['password'])) {
                    unset($input['password']); // Remove the password key from input if it's empty
                } else {
                    // Optionally, hash the password here if it was provided and not empty
                    $input['password'] = $input['password'];
                }

                if ($request->hasFile('children.' . array_search($childData, $childrenData) . '.image')) {
                    $file = $request->file('children.' . array_search($childData, $childrenData) . '.image');
    
                    if (!empty($child->image)) {
                        @unlink($child['image']);
                    }

                    $input['image'] = $this->fileMove($file,'users');
                }

                $child->update($input);
                $child->image = $child->image ? url($child->image) : '';
                $updatedChildren[] = $child;
            } else {
                $errors[] = [
                    'user_id' => $childData['user_id'],
                    'errors' => ['Child not found']
                ];
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
 
}
