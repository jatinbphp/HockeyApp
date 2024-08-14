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
use App\Models\User;
use App\Models\Categories;
use App\Models\Province;
use App\Models\Skill;
use App\Models\Score; 
use App\Models\Child;
use App\Models\Sponsors;
use App\Models\School;
use DOMDocument;

class MainController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', [
            'except' => ['login','register','getActiveSchool','getActiveProvince','getSponsors','getActiveSkill','getChildrenProfile','getChildrensByParentId','submitScore','guardianProfileUpdate','childrenProfileUpdate']
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

        return response()->json([
            'status' => 'success',
            'message' => 'Score Submited Successfully!',
            'data' => $score
        ],200);
    }


    public function getActiveRankings(){

        $getRanking = Score::select('skill_id', DB::raw('SUM(score) as total_score'))
        ->where('status', 'accept')
        ->groupBy('skill_id')
        ->get();
        
        if (!$getRanking->isEmpty()) {

            // Transform the sponsors data to include full image URL
            $rankings = $getRanking->map(function ($ranking) {
                $ranking->skill_name = getSkillName($ranking->skill_id);
                return $ranking;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $rankings
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active sponsors found',
                'data' => (object)[]
            ], 404);
        }
 
    }

    public function getActiveRankingsById($id){

        $getRanking = Score::where('id', $id)
        ->where('status', 'accept')
        ->get();
        
        if (!$getRanking->isEmpty()) {

            // Transform the sponsors data to include full image URL
            $rankings = $getRanking->map(function ($ranking) {
                $ranking->skill_name = getSkillName($ranking->skill_id);
                return $ranking;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $rankings
            ],200);         

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'No active sponsors found',
                'data' => (object)[]
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
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $input = $request->all();

        if (!empty($request->user_id)) {
            $user = User::find($request->user_id);

            if (!empty($user)) {
                $user->update($input);
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
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'status' => 'error',
                'message' => implode(',', $validator->errors()->all()),
                'data' => (object)[]
            ], 200);
        }

        $input = $request->all();

        if (!empty($request->user_id)) {
            $children = Child::find($request->user_id);

            if (!empty($children)) {
                $children->update($input);
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
}
