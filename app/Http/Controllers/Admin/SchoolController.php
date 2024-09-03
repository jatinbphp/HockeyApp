<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Province;
use App\Http\Requests\SchoolRequest;
use DataTables;

class SchoolController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'School';

        if($request->ajax()){
            return Datatables::of(School::all())
            ->addIndexColumn()
            ->editColumn('name', function($row) {
                return ucfirst($row->name);
            }) 
            ->editColumn('town', function($row) {
                return ucfirst($row->town);
            }) 
            ->editColumn('province_id', function($row) {
                return ucfirst(getProvinceName($row->province_id));
            })  
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'schools';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'school';
                $row['section_title'] = 'School';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.school.index',$data);
    }

    public function create(){
        $data['menu'] = 'School';
        $data['provinceData'] = getActiveProvince();
        return view('admin.school.create',$data);
    }

    public function store(SchoolRequest $request){
        $inputs = $request->all();
        $school = School::create($inputs);

        \Session::flash('success','School has been inserted successfully!');
        return redirect()->route('school.edit', ['school' => $school->id]);
    }

    public function edit($id){
        $data['menu'] = 'School';
        $data['provinceData'] = getActiveProvince();
        $data['school'] = School::where('id',$id)->findorFail($id);
        return view('admin.school.edit',$data);
    }

    public function update(SchoolRequest $request,$id){
        $inputs = $request->all();
        $school = School::findOrFail($id);
        $school->update($inputs);


        \Session::flash('success','School has been updated successfully!');
        return redirect()->route('school.index');
    }

    public function show($id) {

        $school = School::findOrFail($id);
        $required_columns = ['id','name','town','province_id','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $school->toArray(),
            'type' => 'school',
            'required_columns' => $required_columns
        ]);
    }

    public function destroy($id)
    {
        $school = School::findOrFail($id);

        if ($school->children()->exists()) {
            return 0; 
        }
    
        $school->delete();
        return 1; 
        
    }
}
