<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\Province;

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