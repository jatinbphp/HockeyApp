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