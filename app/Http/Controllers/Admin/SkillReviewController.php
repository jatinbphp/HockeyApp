<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class SkillReviewController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Skill Review'; 

        if($request->ajax()){
            return Datatables::of(Score::all())
            ->addIndexColumn()
             ->editColumn('skill_id', function($row) {
                return ucfirst(getSkillName($row->skill_id));
            })   
             ->editColumn('student_id', function($row) {
                return ucfirst(getChildrenName($row->student_id));
            })        
            ->editColumn('province_id', function($row) {
                return ucfirst(getProvinceName($row->province_id));
            })        
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            }) 
            ->editColumn('status', function($row){
                return view('admin.common.order-status-dropdown', ['score' => $row]);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'skill-review';
                $row['section_title'] = 'skill-review';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.skill-review.index',$data);
    }

    public function show($id) {

        $skillReview = Score::findOrFail($id);
        $required_columns = ['id', 'skill_id','student_id', 'province_id','score','time_duration','status','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $skillReview->toArray(),
            'type' => 'skill-review',
            'required_columns' => $required_columns
        ]);
    }

    public function updateOrderStatus(Request $request)
    {
        $score = Score::find($request->id);

        if ($score) {
            $score->update(['status' => $request->status]);
            return true;
        }

        return false;
    }
}
