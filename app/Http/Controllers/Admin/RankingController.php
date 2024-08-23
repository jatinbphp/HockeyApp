<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Province;
use App\Models\School;
use App\Models\Skill;
use DataTables;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $data['menu'] = "Rankings";

        $scores = Score::with(['child', 'skills'])
            ->where(['status' => 'accept'])
            ->when(!empty($request->province_id) && $request->province_id != 0, function ($query) use ($request) {
                return $query->where('province_id', $request->province_id);
            })
            ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('school_id', $request->school_id);
                });
            })
            ->when(!empty($request->skill_id) && $request->skill_id != 0, function ($query) use ($request) {
                return $query->whereHas('skills', function ($query) use ($request) {
                    $query->where('skill_id', $request->skill_id);
                });
            })
            ->get();
    
        $groupedScores = $scores->groupBy('skill_id');
        $rankedScores = collect();

        foreach ($groupedScores as $skillId => $skillScores) {
            $totalCount = $skillScores->count(); 
            $sortedScores = $skillScores->sortByDesc('score')->values();

            foreach ($sortedScores as $index => $score) {
                $rank = $index + 1;
                $score->ranking = "{$rank}/{$totalCount}";
                $rankedScores->push($score);
            }
        }
        
        if ($request->ajax()) {
            return Datatables::of($rankedScores)
                ->addIndexColumn()
                ->editColumn('student_id', function ($row) {
                    return $row->child->firstname . ' ' . $row->child->lastname;
                })
                ->editColumn('skill_id', function ($row) {
                    return $row->skills->name;
                })
                ->editColumn('created_at', function ($row) {
                    return formatCreatedAt($row->created_at);
                })
                ->addColumn('ranking', function ($row) {
                    return $row->ranking;
                })
                ->make(true);
        }
    
        $data['province'] = Province::where('status', 'active')->pluck('name', 'id');
        $data['school'] = School::where('status', 'active')->pluck('name', 'id');
        $data['skill']  = Skill::where('status','active')->pluck('name','id');
    
        return view('admin.ranking.index', $data);
    }
}
