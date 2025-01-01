<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use App\Models\School;
use App\Models\Payment;
use App\Http\Requests\ChildRequest;
use App\Http\Requests\ChildModalRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables; 
use Carbon\Carbon;
use Carbon\CarbonInterval;

class ChildController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Children'; 

        if($request->ajax()){

            $parentIdFilter = $request->parent_id ?? 0;

            $childrenQuery = Child::with('parent')
            ->when($parentIdFilter > 0, function ($query) use ($parentIdFilter) {
                $query->where('parent_id', $parentIdFilter);
            })->get();

            return Datatables::of($childrenQuery)

            // return Datatables::of(Child::with('parent')->get())
            ->addIndexColumn()
            ->editColumn('parent_name', function($row) {
                return ucfirst($row->parent_full_name);
            })   
            ->editColumn('full_name', function($row) {
                return ucfirst($row->full_name);
            })            
            ->editColumn('username', function($row) {
                return $row->username;
            }) 
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })  
            ->editColumn('payment_status', function($row) {

                $payment = Payment::select('status','payment_date')->where('child_id', $row->id)->orderBy('payment_date', 'desc')->first();

                if($payment){

                    $paymentStatus = $payment->status ?? 'pending';
                    $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
                    $todayDate = Carbon::now();

                    // Check for 1-year renewal period
                    $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

                    if($paymentStatus == 'pending'){
                        return "Pending";
                    }else if($paymentStatus == 'Paid'){
                        
                        if($oneYearRenewal){
                            return "Expired";
                        }else{
                            return "Paid";
                        }

                    }else{
                        return "-";
                    }
                }
            })  
            ->editColumn('plan_expire_date', function($row) {
                $payment = Payment::select('status','payment_date')->where('child_id', $row->id)->orderBy('payment_date', 'desc')->first();

                if($payment){
                    return $payment->payment_date;
                }
                return "-";
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
        $data['parentData'] =  User::where('role', 'guardian')
        ->orderBy('firstname', 'asc')
        ->get()
        ->mapWithKeys(function ($user) {
            return [$user->id => "{$user->firstname} {$user->lastname}"];
        });
        $data['provinceData'] = getActiveProvince();
        $data['schoolData'] = getActiveSchool();
        return view('admin.children.create',$data);
    }

    public function store(ChildRequest $request)
    {
        $input = $request->all();

        $lookingSponsor = 0;
        if(isset($request->looking_sponsor) && $request->looking_sponsor === 'on'){
            $lookingSponsor = 1;
        }

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/childrens'), $imageName);    
            $inputs['image'] = 'uploads/childrens/' . $imageName;
        }

        $dob = date('Y-m-d',strtotime($request->date_of_birth));
        $ageGroup = getAgeGroup($dob);

        $input['age_group'] = $ageGroup;
        $input['date_of_birth'] = $dob;
        $input['looking_sponsor'] = $lookingSponsor;
        $child = Child::create($input);     

      
        \Session::flash('success', 'Children saved successfully.');
        return redirect()->route('children.edit', ['child' => $child->id]);  

    }
 
    public function edit($id){
        $data['menu'] = 'Children';
        $children = Child::find($id);
        $data['parentData'] =  User::where('role', 'guardian')
        ->orderBy('firstname', 'asc')
        ->get()
        ->mapWithKeys(function ($user) {
            return [$user->id => "{$user->firstname} {$user->lastname}"];
        });
        $data['provinceData'] = getActiveProvince();
        $data['schoolData'] = School::where('province_id', $children->province_id)->pluck('name', 'id');  
        $data['children'] = Child::where('id',$id)->findorFail($id);
        return view('admin.children.edit',$data);
    }

    public function update(ChildRequest $request,$id){

        if(empty($request['password'])){
            unset($request['password']);
        }

        $input = $request->all();
        
        $user = Child::findorFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/childrens'), $imageName);

            $input['image'] = 'uploads/childrens/' . $imageName;

            if (!empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        } else {
            $input['image'] = $user->image;
        }


        $lookingSponsor = 0;
        if(isset($request->looking_sponsor) && $request->looking_sponsor === 'on'){
            $lookingSponsor = 1;
        }

        $dob = date('Y-m-d',strtotime($request->date_of_birth));
        $ageGroup = getAgeGroup($dob);

        $input['age_group'] = $ageGroup;
        $input['date_of_birth'] = $dob;
        $input['looking_sponsor'] = $lookingSponsor;
        
        $user->update($input);

        \Session::flash('success','Children has been updated successfully!');
        return redirect()->route('children.index');
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

    public function saveChildrenFromPopup(ChildModalRequest $request){

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

        $dob = date('Y-m-d',strtotime($request->child_dob));
        $ageGroup = getAgeGroup($dob);

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
            'gender' => $input['gender'],
            'age_group' => $ageGroup,
        ]);     

        return response()->json(['success'=>'Children saved successfully.']);

    }
}
