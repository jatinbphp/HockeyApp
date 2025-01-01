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
            ->whereHas('child', function ($query) {
                $query->where('status', 'active') // Ensure the child is active
                      ->where('deleted_at', null); // Ensure the child is not deleted
            })
            ->when(!empty($request->province_id) && $request->province_id != 0, function ($query) use ($request) {
                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('province_id', $request->province_id);
                });
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
            ->when(!empty($request->age_group) && $request->age_group != 0, function ($query) use ($request) {
                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('age_group', $request->age_group);
                });
            })
            ->when(!empty($request->gender) && $request->gender != 0, function ($query) use ($request) {
                return $query->whereHas('child', function ($query) use ($request) {
                    $query->where('gender', $request->gender);
                });
            })
            ->selectRaw('student_id, skill_id, MAX(score) as max_score')
            ->whereYear('created_at', date('Y'))
            ->groupBy('student_id', 'skill_id') 
            ->get();

        // Calculate rankings within each group
        $rankedScores = $scores->groupBy('skill_id')->flatMap(function ($group) {
            $sorted = $group->sortByDesc('max_score')->values();
            $totalCount = $sorted->count();

            return $sorted->map(function ($score, $index) use ($totalCount) {
                // $score->ranking = ($index + 1) . '/' . $totalCount;
                $score->ranking = ($index + 1);
                return $score;
            });
        });

        // Datatables response
        if ($request->ajax()) {
            return Datatables::of($rankedScores)
                ->addIndexColumn()
                ->editColumn('student_id', function ($row) {
                    $firstname = $row->child->firstname ?? '';
                    $lastname = $row->child->lastname ?? '';
                    $username = $row->child->username ?? '';

                    if ($firstname || $lastname) {
                        return trim($firstname . ' ' . $lastname);
                    } else {
                        return $username;
                    }
                })
                ->editColumn('age_group', function ($row) {
                    return $row->child->age_group ?? '';
                })
                ->editColumn('gender', function ($row) {
                    return $row->child->gender ?? '';
                })
                ->editColumn('skill_id', function ($row) {
                    return $row->skills->name ?? '';
                })
                ->editColumn('score', function ($row) {
                    return $row->max_score ?? 0;
                })             
                ->addColumn('ranking', function ($row) {
                    return $row->ranking;
                })
                ->make(true);
        }
    
        $data['province'] = Province::where('status', 'active')->orderBy('name', 'asc')->pluck('name', 'id');
        $data['school'] = School::where('status', 'active')->orderBy('name', 'asc')->pluck('name', 'id');
        $data['skill']  = Skill::where('status','active')->orderBy('position', 'asc')->pluck('name','id');
    
        return view('admin.ranking.index', $data);
    }
}
