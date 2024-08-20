<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use App\Models\Province;
use App\Models\EmailTemplate;
use App\Http\Requests\ParentRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use DataTables;

class ParentController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Guardian'; 

        if($request->ajax()){
            return Datatables::of(User::where('role','guardian'))
            ->addIndexColumn()
            ->editColumn('firstname', function($row) {
                return ucfirst($row->firstname);
            }) 
            ->editColumn('lastname', function($row) {
                return ucfirst($row->lastname);
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
        $guardianEmail = $inputs['email'] ?? "";

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/users'), $imageName);    
            $inputs['image'] = 'uploads/users/' . $imageName;
        }

        $inputs['role'] = 'guardian';
        $parent = User::create($inputs);

        $template_details = EmailTemplate::find(1);

        $placeholders = [
            '{{firstname}}' => ucfirst($inputs['firstname']),
            '{{lastname}}' => ucfirst($inputs['lastname']),
        ];

        $messageBody = str_replace(
            array_keys($placeholders), 
            array_values($placeholders), 
            $template_details->template_message
        );

        $mailData = [
            'salutation' => 'Hello ' . ucfirst($inputs['firstname']) . ' ' . ucfirst($inputs['lastname']) . ',',
            'body' => $messageBody,
            'subject' => $template_details->template_subject ?? "",
        ];

        Mail::to([$guardianEmail])->send(new RegistrationMail($mailData));


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

    public function show($id) {

        $users = User::findOrFail($id);
        $required_columns = ['id','image','firstname','lastname', 'username','email','phone','terms','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $users->toArray(),
            'type' => 'guardian',
            'required_columns' => $required_columns
        ]);
    }

    public function destroy($id){
        $users = User::with('children')->findOrFail($id);
        if(!empty($users)){
            $users->children()->delete();
            $users->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
