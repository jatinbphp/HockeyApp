<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Province;
use App\Http\Requests\ParentRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class ParentController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Guardian'; 

        if($request->ajax()){
            return Datatables::of(User::where('role','guardian'))
            ->addIndexColumn()
            ->editColumn('fullname', function($row) {
                return ucfirst($row->firstname). ' '.ucfirst($row->lastname);
            })  
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'users';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'parent';
                $row['section_title'] = 'Parent';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.parent.index',$data);
    }

    public function create(){
        $data['menu'] = 'Guardian';
        return view('admin.parent.create',$data);
    }

    public function store(ParentRequest $request){
        
        $inputs = $request->all();

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/users'), $imageName);    
            $inputs['image'] = 'uploads/users/' . $imageName;
        }

        $inputs['role'] = 'guardian';
        $inputs['status'] = 'inactive';
        $parent = User::create($inputs);

        \Session::flash('success', 'User has been inserted successfully!');
        return redirect()->route('parent.edit', ['parent' => $parent->id]);

    }

    public function edit($id){
        $data['menu'] = 'Guardian';
        $data['provinceData'] = getActiveProvince();
        $data['schoolData'] = getActiveSchool();
        $data['parent'] = User::where('id',$id)->findorFail($id);
        return view('admin.parent.edit',$data);
    }

    public function update(ParentRequest $request,$id){

        if(empty($request['password'])){
            unset($request['password']);
        }

        $input = $request->all();

        $user = User::findorFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/users'), $imageName);

            $input['image'] = 'uploads/users/' . $imageName;

            if (!empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        } else {
            $input['image'] = $user->image;
        }
        
        $user->update($input);

        \Session::flash('success','User has been updated successfully!');
        return redirect()->route('parent.index');
    }

    public function destroy($id)
    {
        $users = User::findOrFail($id);
        if(!empty($users)){
            if (!empty($users['image']) && file_exists($users['image'])) {
                unlink($users['image']);
            }
            $users->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
