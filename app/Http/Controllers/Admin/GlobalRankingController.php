<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Province;
use App\Models\School;
use App\Models\Skill;
use DataTables;

class GlobalRankingController extends Controller
{
    public function index(Request $request)
    {
        $data['menu'] = "Global Rankings";

        $scores = Score::with(['child', 'skills'])
            ->where('status', 'accept')
            ->whereHas('child', function ($query) {
                $query->where('status', 'active')
                    ->whereNull('deleted_at');
            })
            ->when(!empty($request->province_id) && $request->province_id != 0, function ($query) use ($request) {
                $query->whereHas('child', fn($q) => $q->where('province_id', $request->province_id));
            })
            ->when(!empty($request->school_id) && $request->school_id != 0, function ($query) use ($request) {
                $query->whereHas('child', fn($q) => $q->where('school_id', $request->school_id));
            })
            ->when(!empty($request->gender), function ($query) use ($request) {
                $query->whereHas('child', fn($q) => $q->where('gender', $request->gender));
            })
            ->when(!empty($request->age_group), function ($query) use ($request) {
                $query->whereHas('child', fn($q) => $q->where('age_group', $request->age_group));
            })
            ->selectRaw('student_id, skill_id, MAX(score) as max_score')
            ->whereYear('created_at', date('Y'))
            ->groupBy('student_id', 'skill_id')
            ->get();

        $grouped_scores = $scores->groupBy('student_id');    
        $skills = Skill::pluck('id');   
           // Process each student's scores
        $all_children = [];
        $highest_scores = []; // To store highest scores for each skill

        foreach ($grouped_scores as $child_id => $scores) {
            $child = $scores->first()->child; // Access the child record (firstname, age_group)
            $scored_skills = $scores->pluck('skill_id')->toArray();
            $missing_skills = $skills->diff($scored_skills);
            $total_score = $scores->sum('max_score'); // Sum of max scores for the child

            // Update the highest score per skill
            foreach ($scores as $score) {
                $skill_id = $score['skill_id'];
                $highest_scores[$skill_id] = max($highest_scores[$skill_id] ?? 0, $score['max_score']);
            }

            // Only include children who have completed all skills
            if ($missing_skills->isEmpty()) {
                $all_children[$child_id] = [
                    'child_id' => $child->id,
                    'parent_id' => $child->parent_id,
                    'child_name' => $child->firstname . ' ' . $child->lastname,
                    'age_group' => $child->age_group,
                    'gender' => $child->gender,
                    'score' => $total_score,
                    'completed_skills' => $scored_skills,
                ];
            }
        }

        // Sort all children by total_score in descending order
        $ranked_children = collect($all_children)
            ->sortByDesc('score')
            ->values()
            ->map(function ($child, $index) {
                $child['ranking_no'] = $index + 1; // Assign rank
                return $child;
            });

        // dd($ranked_children);

        // Datatables response
        if ($request->ajax()) {
            return Datatables::of($ranked_children)
                ->addIndexColumn()
                ->editColumn('student_id', function ($row) {
                    return $row['child_name'];                  
                })
                ->editColumn('age_group', function ($row) {
                    return  $row['age_group'];
                })
                ->editColumn('gender', function ($row) {
                    return  $row['gender'];
                })               
                ->editColumn('score', function ($row) {
                    return $row['score'] ?? 0;
                })
                ->addColumn('ranking', function ($row) {
                    return $row['ranking_no'];
                })
                ->make(true);
        }
    
        $data['province'] = Province::where('status', 'active')->orderBy('name', 'asc')->pluck('name', 'id');
        $data['school'] = School::where('status', 'active')->orderBy('name', 'asc')->pluck('name', 'id');
        $data['skill']  = Skill::where('status','active')->orderBy('position', 'asc')->pluck('name','id');
    
        return view('admin.global-ranking.index', $data);
    }
}
