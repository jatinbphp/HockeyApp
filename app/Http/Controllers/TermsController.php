<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;

class TermsController extends Controller
{
    public function index(Request $request){
        $data['pages'] = Pages::where('id',1)->findorFail(1);
        return view('terms',$data);
    }

    public function policy(Request $request){
        $data['pages'] = Pages::where('id',2)->findorFail(2);
        return view('policy',$data);
    }
} 
