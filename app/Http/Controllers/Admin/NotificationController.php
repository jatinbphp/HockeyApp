<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Province;
use App\Models\School;
use App\Models\Child;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NotificationRequest;
use DataTables; 

class NotificationController extends Controller
{
    
    public function index(Request $request){
        $data['menu'] = 'Notification'; 

        if($request->ajax()){
            return Datatables::of(Notification::all())
            ->addIndexColumn()
            ->editColumn('user_id', function($row) {
                if ($row->user_type == "guardian") {
                    return $row->user ? ucfirst($row->user->firstname) . ' ' . $row->user->lastname : '-';
                } else {
                    return $row->child ? ucfirst($row->child->firstname) . ' ' . $row->child->lastname : '-';
                }
            })
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })
            ->editColumn('message', function($row) {
                return strip_tags($row->message);
            })
            ->addColumn('province', function($row) {
                return $row->child->province->name ?? "-";
            })
            ->addColumn('school', function($row) {
                return $row->child && $row->child->school ? $row->child->school->name : "-";
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'notification';
                $row['section_title'] = 'Notification';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.notification.index',$data);
    }

    public function create()
    {
        $data['menu'] = 'Notification';
        $data['users'] = User::select(DB::raw("CONCAT(firstname, ' ', lastname) as name"), 'id')->where('role','guardian')->pluck('name', 'id');
        $data['provinces'] = Province::pluck('name','id');
        $data['schools'] = School::pluck('name','id');
        $data['children'] = Child::select(DB::raw("CONCAT(firstname, ' ', lastname) as name"), 'id')->pluck('name', 'id');

        return view("admin.notification.create",$data);
    }

    public function store(NotificationRequest $request)
    {
        $userType = $request->user_type;
        if ($userType == 'province') {
            $userIds = Child::whereIn('province_id', $request->user_id)->pluck('id');
        } elseif ($userType == 'School') {
            $userIds = Child::whereIn('school_id', $request->user_id)->pluck('id');
        } else {
            $userIds = $request->user_id;
        }

        foreach($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'message' => $request->message,
                'user_type'=>$userType,
            ]);
        }

        \Session::flash('success', 'Notification has been inserted successfully!');
        return redirect()->route('notification.index');
    }

    public function destroy($id){
        $notifications = Notification::find($id);
        if(!empty($notifications)){
            $notifications->delete();
            return 1;
        }
        return 0;
    }
}
