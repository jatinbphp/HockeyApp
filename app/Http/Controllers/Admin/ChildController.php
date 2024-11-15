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
            return Datatables::of(Child::where('parent_id', $request->parent_id))
            ->addIndexColumn()
            ->editColumn('firstname', function($row) {
                return ucfirst($row->firstname);
            }) 
            ->editColumn('lastname', function($row) {
                return ucfirst($row->lastname);
            }) 
            ->editColumn('username', function($row) {
                return $row->username;
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

    public function create($id){
        $data['menu'] = 'Children';
        return view('admin.children.create',$data);
    }

    public function store(ChildRequest $request)
    {
        $input = $request->all();

        $lookingSponsor = 0;
        if(isset($request->looking_sponsor)){
            $lookingSponsor = 1;
        }

        $user = $request->child_id ? Child::find($request->child_id) : null;

        if ($file = $request->file('child_image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/childrens'), $imageName);
            $input['image'] = 'uploads/childrens/' . $imageName;
    
            if ($user && !empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        } else {
            \Log::info('No File Received');
            $input['image'] = $user ? $user->image : null;
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
            'image' => $input['image'],
        ]);     

        return response()->json(['success'=>'Children saved successfully.']);

    }
 
    public function edit($id){
        $children = Child::find($id);

        // Check if child exists and if the image column is not empty
        if ($children && !empty($children->image)) {
            // Check if the image file exists in storage
            if (file_exists(public_path($children->image))) {
                $children->image = url($children->image);
            } else {
                $children->image = url('assets/dist/img/no-image.png');
            }
        } else {
            $children->image = url('assets/dist/img/no-image.png');
        }

        return response()->json($children);
    }

    public function show($id) {

        $users = Child::findOrFail($id);
        $required_columns = ['id','image', 'firstname','lastname', 'username','email','phone','date_of_birth','province_id','school_id','looking_sponsor','terms','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $users->toArray(),
            'type' => 'children',
            'required_columns' => $required_columns
        ]);
    }
 
    public function destroy($id)
    {
        Child::find($id)->delete();
        return response()->json(['success' => 'Children deleted successfully!']);
    }
}
