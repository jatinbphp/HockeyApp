<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Http\Requests\FeesRequest;

class FeesController extends Controller
{

    public function index()
    {
        $data['menu'] = "Fees";
        $data['fees'] = Fee::first();
        return view('admin.fees.create',$data);
    }

    public function store(FeesRequest $request)
    {
        $fee = Fee::first();
        $input=$request->all();
        
        if(!empty($fee)){
            $fee->update($input);
            \Session::flash('success', 'Fees have been updated successfully!');
            return redirect()->route('fees.index');
        }else{
            Fee::create($input);
            \Session::flash('success', 'Fees have been inserted successfully!');
            return redirect()->route('fees.index');
        }
           
    }
}
