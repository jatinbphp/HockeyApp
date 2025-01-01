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
            return Datatables::of(Skill::orderBy('position')->get())
            ->addIndexColumn()
            ->setRowAttr([
                'data-id' => function($row) {
                    return $row->id;
                },
            ])
            ->addColumn('drag', function() {
                $url = route('skill.reorder'); // Generate the URL for reordering
                return '<span class="drag-handle" data-url="' . $url . '"><i class="fa fa-arrows"></i></span>'; // Add drag handle icon with dynamic URL
            })
            ->editColumn('featured_image', function($row) {
                $defaultImage = url('assets/dist/img/no-image.png');
                $imagePath = public_path($row->featured_image);

                $url = (!empty($row->featured_image) && file_exists($imagePath)) 
                    ? asset($row->featured_image) 
                    : $defaultImage;
                
                    
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
            ->rawColumns(['drag']) // Specify columns containing HTML
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

        if ($file = $request->file('icon_image')) {
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();   
            $file->move(public_path('uploads/skill'), $imageName);    
            $inputs['icon_image'] = 'uploads/skill/' . $imageName;
        }

        $score_field_active = 0;
        if(isset($request->score_field_active)){
            $score_field_active = 1;
        }

        $time_field_active = 0;
        if(isset($request->time_field_active)){
            $time_field_active = 1;
        }

        $inputs['score_field_active'] = $score_field_active;
        $inputs['time_field_active'] = $time_field_active;

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


        if ($file = $request->file('icon_image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/skill'), $imageName);

            $inputs['icon_image'] = 'uploads/skill/' . $imageName;

            if (!empty($skill->icon_image) && file_exists(public_path($skill->icon_image))) {
                unlink(public_path($skill->icon_image));
            }
        } else {
            $inputs['icon_image'] = $skill->icon_image;
        }

        $score_field_active = 0;
        if(isset($request->score_field_active)){
            $score_field_active = 1;
        }

        $time_field_active = 0;
        if(isset($request->time_field_active)){
            $time_field_active = 1;
        }

        $inputs['score_field_active'] = $score_field_active;
        $inputs['time_field_active'] = $time_field_active;
        
        $skill->update($inputs);

        \Session::flash('success','Skill has been updated successfully!');
        return redirect()->route('skill.index');
    }

    public function show($id) {

        $skill = Skill::findOrFail($id);
        $required_columns = ['id','featured_image','name','category_id','short_description','long_description','instruction','score_instruction','video_url','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $skill->toArray(),
            'type' => 'skill',
            'required_columns' => $required_columns
        ]);
    }


    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);

        if ($skill->score()->exists()) {
            return 0; 
        }
    
        $skill->delete();
        return 1; 
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            Skill::where('id', $item['id'])->update(['position' => $item['position']]);
        }
        return response()->json(['status' => 'success']);
    }
}
 