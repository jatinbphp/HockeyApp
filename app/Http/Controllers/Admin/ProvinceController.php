<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Http\Requests\ProvinceRequest;
use DataTables;

class ProvinceController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Province';

        if($request->ajax()){
            return Datatables::of(Province::all())
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'provinces';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'province';
                $row['section_title'] = 'Province';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.province.index',$data);
    }

    public function create(){
        $data['menu'] = 'Province';
        return view('admin.province.create',$data);
    }

    public function store(ProvinceRequest $request){
        $inputs = $request->all();
        $province = Province::create($inputs);

        \Session::flash('success','Province has been added Successfully!');
        return redirect()->route('province.edit', ['province' => $province->id]);
    }

    public function edit($id){
        $data['menu'] = 'Province';
        $data['province'] = Province::where('id',$id)->findorFail($id);
        return view('admin.province.edit',$data);
    }

    public function update(ProvinceRequest $request, $id){
        $inputs = $request->all();
        $province = Province::findorFail($id);
        $province->update($inputs);

        \Session::flash('success','Province has been updated successfully!');
        return redirect()->route('province.index');
    }

    public function show($id) {

        $province = Province::findOrFail($id);
        $required_columns = ['id','name','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $province->toArray(),
            'type' => 'province',
            'required_columns' => $required_columns
        ]);
    }

    public function destroy($id){
    
        $province = Province::findorFail($id);

        if ($province->children()->exists() || $province->school()->exists() ) {
            return 0; 
        }
    
        $province->delete();
        return 1; 
        
    }
}
