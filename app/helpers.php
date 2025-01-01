<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\Province;
use App\Models\Categories;
use App\Models\Child;
use App\Models\Skill;

if (!function_exists('formatCreatedAt')) {
    function formatCreatedAt($createdAt){
        return Carbon::parse($createdAt)->format('Y-m-d H:i:s');
    }
}

/* GET ACTIVE PROVINCE DATA */
if (!function_exists('getActiveProvince')) {
    function getActiveProvince(){
        $province = [];

        $province = Province::where('status', 'active')           
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        return $province;
    }
}

/* GET ACTIVE PROVINCE NAME */
if (!function_exists('getProvinceName')) {
    function getProvinceName($id){
        // Assuming Province is the model name
        $province = Province::find($id);

        // Check if province is found
        if ($province) {
            return $province->name;
        } else {
            return null; // or some default value
        }
    }
}

/* GET ACTIVE SCHOOL NAME */
if (!function_exists('getSchoolName')) {
    function getSchoolName($id){
        // Assuming Province is the model name
        $school = School::find($id);

        // Check if school is found
        if ($school) {
            return $school->name;
        } else {
            return null; // or some default value
        }
    }
}

/* GET ACTIVE SCHOOL DATA */
if (!function_exists('getActiveSchool')) {
    function getActiveSchool(){
        $province = [];

        $province = School::where('status', 'active')           
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        return $province;
    }
}

/* GET ACTIVE CATEGORY DATA */
if (!function_exists('getActiveCategory')) {
    function getActiveCategory(){
        $category = [];

        $category = Categories::where('status', 'active')           
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        return $category;
    }
}

/* GET ACTIVE CATEGORY NAME */
if (!function_exists('getCategoryName')) {
    function getCategoryName($id){
        // Assuming Province is the model name
        $category = Categories::find($id);

        // Check if category is found
        if ($category) {
            return $category->name;
        } else {
            return null; // or some default value
        }
    }
}

/* GET ACTIVE CHILDREN NAME */
if (!function_exists('getChildrenName')) {
    function getChildrenName($id){
       
        $child = Child::find($id);

        // Check if child is found
        if ($child) {
            return $child->firstname.' '.$child->lastname;
        } else {
            return null; // or some default value
        }
    }
}


/* GET ACTIVE PROVINCE NAME */
if (!function_exists('getSkillName')) {
    function getSkillName($id){
      
        $skill = Skill::find($id);

        // Check if skill is found
        if ($skill) {
            return $skill->name;
        } else {
            return null; // or some default value
        }
    }
}

if(!function_exists('getPaymentUrl')){
    function getPaymentUrl($data){ 

        $payfastData = [
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
            'return_url' => route('payment.return-url'),
            'cancel_url' => route('payment.cancel-url'),
            'notify_url' => route('payment.notify-url'),
            'name_first' => $data['name'],
            'email_address' => $data['email'],
            'm_payment_id' => $data['mPaymnentId'],
            'amount' => $data['totAmtToPay'],
            'item_name' => 'Order#'.$data['mPaymnentId'],
            'custom_int1' => $data['child_id'],
            'custom_int2' => $data['parent_id'],
            'custom_str2' => "SINGLE_PAYMENT",
            // 'payment_method' => $data['payment_method']
        ];

        $payfastData['signature'] = generatePayfastSignature($payfastData,env('PAYFAST_PASSPHRASE'));

        $payfastUrl = (env('PAYFAST_TEST_MODE') == "true") ? 'https://sandbox.payfast.co.za/eng/process?' : 'https://www.payfast.co.za/eng/process?';
        $queryString = http_build_query($payfastData);

        return $payfastUrl.$queryString;
    }
}

if(!function_exists('getPaymentUrlForMultiple')){
    function getPaymentUrlForMultiple($data){ 

        $payfastData = [
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
            'return_url' => route('payment.return-url'),
            'cancel_url' => route('payment.cancel-url'),
            'notify_url' => route('payment.notify-url'),
            'name_first' => $data['name'],
            'email_address' => $data['email'],
            'm_payment_id' => $data['mPaymnentId'],
            'amount' => $data['totAmtToPay'],
            'item_name' => 'Order#'.$data['mPaymnentId'],
            'custom_int2' => $data['parent_id'],
            'custom_str1' => $data['child_ids'], // Store all child IDs in one field
            'custom_str2' => "MULITPLE_PAYMENT",
            // 'payment_method' => $data['payment_method']
        ];

        $payfastData['signature'] = generatePayfastSignature($payfastData,env('PAYFAST_PASSPHRASE'));

        $payfastUrl = (env('PAYFAST_TEST_MODE') == "true") ? 'https://sandbox.payfast.co.za/eng/process?' : 'https://www.payfast.co.za/eng/process?';
        $queryString = http_build_query($payfastData);

        return $payfastUrl.$queryString;
    }
}

if(! function_exists('generatePayfastSignature')){
    function generatePayfastSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    } 
}


if(! function_exists('getAgeGroup')){
    function getAgeGroup($dob)
    {
        // Extract the birth year
        $birthYear = date('Y', strtotime($dob));
        
        // Get the current year
        $currentYear = date('Y');
        
        // Calculate the child's age
        $age = $currentYear - $birthYear;

        // Define the age groups
        $ageGroups = [
            '0/9'  => 9,
            '0/11' => 11,
            '0/13' => 13,
            '0/14' => 14,
            '0/16' => 16,
            '0/19' => 19,
        ];

        // Find the appropriate age group
        foreach ($ageGroups as $group => $maxAge) {
            if ($age <= $maxAge) {
                return $group;
            }
        }

        // Return the highest group if age exceeds all defined groups
        return '0/19';
    }
}