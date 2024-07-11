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

    public function store(Request $request){

        echo "<pre>";
        print_r($request);
        exit;

        Child::updateOrCreate([
            'id' => $request->child_id
        ],
        [
            'firstname' => $request->firstname, 
            'lastname' => $request->lastname
        ]);        
     
        return response()->json(['success'=>'Children added successfully.']);

    }

    public function edit($id){
        $children = Child::find($id);
        return response()->json($children);
    }

    public function update(ChildRequest $request,$id){

        if(empty($request['password'])){
            unset($request['password']);
        }

        $input = $request->all();

        $user = Child::findorFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/children'), $imageName);

            $input['image'] = 'uploads/children/' . $imageName;

            if (!empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        } else {
            $input['image'] = $user->image;
        }
        
        $user->update($input);

        \Session::flash('success','User has been updated successfully!');
        return redirect()->route('children.index');
    }

    public function destroy($id)
    {
        $users = Child::findOrFail($id);
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
