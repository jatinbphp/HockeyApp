<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\School;
use App\Models\Skill;
use App\Models\Sponsors;

class DashboardController extends Controller
{
    public function index(){
        $data['menu'] = 'Dashboard';

        $data['category'] = Categories::where('status', 'active')->count();
        $data['school'] = School::where('status', 'active')->count();
        $data['skill'] = Skill::where('status', 'active')->count();
        $data['sponsor'] = Sponsors::where('status', 'active')->count();
      
        return view('admin.dashboard',$data);
    }
}
