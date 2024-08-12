<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use App\Http\Requests\ChildRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables; 

class ChildController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Children'; 

        if($request->ajax()){
            return Datatables::of(Child::all())
            ->addIndexColumn()
            ->editColumn('fullname', function($row) {
                return ucfirst($row->firstname). ' '.ucfirst($row->lastname);
            })  
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'children';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'children';
                $row['section_title'] = 'Children';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.children.index',$data);
    }

    public function create(){
        $data['menu'] = 'Children';
        return view('admin.children.create',$data);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $lookingSponsor = 0;
        if(isset($request->looking_sponsor)){
            $lookingSponsor = 1;
        }

        Child::updateOrCreate([
            'id' => $request->child_id
        ],[
            'parent_id' => $request->parent_id, 
            'firstname' => $request->child_firstname, 
            'lastname' => $request->child_lastname,
            'username' => $request->child_username,
            'email' => $request->email,
            'password' => $request->child_password,
            'date_of_birth' => date('Y-m-d',strtotime($request->child_dob)),
            'phone' => $request->child_phone,
            'province_id' => $request->province_id,
            'school_id' => $request->school_id,
            'looking_sponsor' => $lookingSponsor,
        ]);     

        return response()->json(['success'=>'Children saved successfully.']);

    }
 
    public function edit($id){
        $children = Child::find($id);
        return response()->json($children);
    }
 
    public function destroy($id)
    {
        Child::find($id)->delete();
        return response()->json(['success' => 'Children deleted successfully!']);
    }
}
