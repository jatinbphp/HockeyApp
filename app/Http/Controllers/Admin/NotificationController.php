<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Province;
use App\Models\School;
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
                if ($row->user_type == "users") {
                    $user = User::find($row->user_id);
                    if ($user) {
                        return $user->firstname . ' ' . $user->lastname;
                    }
                } elseif ($row->user_type == "School") {
                    $school = School::find($row->user_id);
                    if ($school) {
                        return $school->name;
                    }
                } elseif ($row->user_type == "province") {
                    $province = Province::find($row->user_id);
                    if ($province) {
                        return $province->name;
                    }
                }
            })
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })
            ->editColumn('message', function($row) {
                return strip_tags($row->message);
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
        $data['users'] = User::select(DB::raw("CONCAT(firstname, ' ', lastname) as name"), 'id')->pluck('name', 'id');
        $data['provinces'] = Province::select('*')->pluck('name','id');
        $data['schools'] = School::select('*')->pluck('name','id');

        return view("admin.notification.create",$data);
    }

    public function store(NotificationRequest $request)
    {
        $userIds = $request->user_id;
        $userType= $request->user_type;
    
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
        }else{
            return 0;
        }
    }
}
