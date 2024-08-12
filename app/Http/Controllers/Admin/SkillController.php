<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class SkillController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Skill';

        if($request->ajax()){
            return Datatables::of(Skill::all())
            ->addIndexColumn()
            ->editColumn('featured_image', function($row) {
                $url= (!empty($row->featured_image))? asset($row->featured_image):''; 
                return $url; 
            }) 
            ->editColumn('name', function($row) {
                return ucfirst($row->name);
            }) 
            ->editColumn('category_id', function($row) {
                return getCategoryName($row->category_id);
            }) 
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'skills';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'skill';
                $row['section_title'] = 'Skill'; 
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.skill.index',$data);
    }

    public function create(){
        $data['menu'] = 'Skill';
        $data['skillData'] = getActiveCategory();
        return view('admin.skill.create',$data);
    }

    public function store(SkillRequest $request){
        $inputs = $request->all();

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/skill'), $imageName);    
            $inputs['featured_image'] = 'uploads/skill/' . $imageName;
        }

        $skill = Skill::create($inputs);

        \Session::flash('success','Skill has been inserted successfully!');
        return redirect()->route('skill.edit', ['skill' => $skill->id]);
    }

    public function edit($id){
        $data['menu'] = 'Skill';
        $data['skill'] = Skill::findorFail($id);
        $data['skillData'] = getActiveCategory();
        return view('admin.skill.edit',$data);
    }

    public function update(SkillRequest $request,$id){
        $inputs = $request->all();
        $skill = Skill::findOrFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/skill'), $imageName);

            $inputs['featured_image'] = 'uploads/skill/' . $imageName;

            if (!empty($skill->featured_image) && file_exists(public_path($skill->featured_image))) {
                unlink(public_path($skill->featured_image));
            }
        } else {
            $inputs['featured_image'] = $skill->featured_image;
        }

        $skill->update($inputs);

        \Session::flash('success','Skill has been updated successfully!');
        return redirect()->route('skill.index');
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        if(!empty($skill)){
            $skill->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
 