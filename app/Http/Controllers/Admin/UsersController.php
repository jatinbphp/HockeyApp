<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class UsersController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Admin'; 

        if($request->ajax()){
            return Datatables::of(User::where('role','admin'))
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
                $row['table_name'] = 'users';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'users';
                $row['section_title'] = 'User';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.users.index',$data);
    }

    public function create(){
        $data['menu'] = 'Admin';
        return view('admin.users.create',$data);
    }

    public function store(UserRequest $request){
        
        $inputs = $request->all();

        if ($file = $request->file('image')) {
            $inputs['image'] = $this->fileMove($file, 'users');
            // $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            // $file->move(public_path('uploads/users'), $imageName);    
            // $inputs['image'] = 'uploads/users/' . $imageName;
        }

        $inputs['role'] = 'admin';       
        $user = User::create($inputs);

        \Session::flash('success', 'User has been inserted successfully!');
        return redirect()->route('users.edit', ['user' => $user->id]);

    }

    public function edit($id){
        $data['menu'] = 'Admin';
        $data['users'] = User::where('id',$id)->findorFail($id);
        return view('admin.users.edit',$data);
    }

    public function update(UserRequest $request,$id){

        if(empty($request['password'])){
            unset($request['password']);
        }

        $input = $request->all();

        $user = User::findorFail($id);

        // Handle image upload
        if($file = $request->file('image')){
            // Delete old image file if exists
            if (!empty($user->image) && file_exists($user->image)) {
                unlink($user->image);
            }
            // Upload new image file
            $input['image'] = $this->fileMove($file, 'users');
        }

        // if ($file = $request->file('image')) {
        //     $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
        //     $file->move(public_path('uploads/users'), $imageName);

        //     $input['image'] = 'uploads/users/' . $imageName;

        //     if (!empty($user->image) && file_exists(public_path($user->image))) {
        //         unlink(public_path($user->image));
        //     }
        // } else {
        //     $input['image'] = $user->image;
        // }

        $user->update($input);

        \Session::flash('success','User has been updated successfully!');
        return redirect()->route('users.index');
    }

    public function show($id) {

        $users = User::findOrFail($id);
        $required_columns = ['id','image', 'firstname','lastname', 'username','email','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $users->toArray(),
            'type' => 'users',
            'required_columns' => $required_columns
        ]);
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

    public function verifyAccount($token)
    {
        $verifyUser = User::where('remember_token', $token)->first();
        $message = 'Sorry, your email cannot be identified.';

        if ($verifyUser) {
            if (is_null($verifyUser->email_verified_at)) {
                $verifyUser->email_verified_at = now();
                $verifyUser->status = 'active';
                $verifyUser->save();
                $message = "Your email is verified. You can now log in.";
            } else {
                $message = "Your email is already verified. You can now log in.";
            }
        }

        return redirect()->route('accountVerified')->with('success', $message);
    }

}
