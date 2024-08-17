<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsors;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SponsorsRequest;
use DataTables;

class SponsorsController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Sponsors';

        if($request->ajax()){
            return Datatables::of(Sponsors::all())
            ->addIndexColumn()
            ->editColumn('image', function($row) {
                $url=asset($row->image); 
                return $url; 
            }) 
            ->editColumn('name', function($row) {
                return ucfirst($row->name);
            })            
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'sponsors';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'sponsors';
                $row['section_title'] = 'Sponsors'; 
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.sponsors.index',$data);
    }

    public function create(){
        $data['menu'] = 'Sponsors';
        return view('admin.sponsors.create',$data);
    }

    public function store(SponsorsRequest $request){
        $inputs = $request->all();

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/sponsors'), $imageName);    
            $inputs['image'] = 'uploads/sponsors/' . $imageName;
        }

        $sponsors = Sponsors::create($inputs);

        \Session::flash('success','Sponsor has been added Successfully!');
        return redirect()->route('sponsors.edit', ['sponsor' => $sponsors->id]);
    }

    public function edit($id){
        $data['menu'] = 'Sponsors';
        $data['sponsors'] = Sponsors::findOrFail($id);
        return view('admin.sponsors.edit',$data);
    }

    public function update(SponsorsRequest $request,$id){
        $inputs = $request->all();
        $sponsors = Sponsors::findOrFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/sponsors'), $imageName);

            $inputs['image'] = 'uploads/sponsors/' . $imageName;

            if (!empty($sponsors->image) && file_exists(public_path($sponsors->image))) {
                unlink(public_path($sponsors->image));
            }
        } else {
            $inputs['image'] = $sponsors->image;
        }

        $sponsors->update($inputs);

        \Session::flash('success','Sponsor has been updated successfully!');
        return redirect()->route('sponsors.index');

    }

    public function show($id) {

        $sponsors = Sponsors::findOrFail($id);
        $required_columns = ['id','image','name','description','website','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $sponsors->toArray(),
            'type' => 'sponsor',
            'required_columns' => $required_columns
        ]);
    }

    public function destroy($id)
    {
        $sponsors = Sponsors::findOrFail($id);
        if(!empty($sponsors)){
            $sponsors->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
