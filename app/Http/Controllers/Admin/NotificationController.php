<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables; 

class NotificationController extends Controller
{
    
    public function index(Request $request){
        $data['menu'] = 'Notification'; 

        if($request->ajax()){
            return Datatables::of(Notification::all())
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'notification';
                return view('admin.common.status-buttons', $row);
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
        $data['users'] = User::where('role', 'drivers')->get();
        $data['province'] = Province::where('status','active')->get();

        // $data['customers'] = User::where('role', 'customers')->get();
        return view("admin.notification.create",$data);
    }

    public function store(NotificationRequest $request)
    {
        $input = $request->all();
        $notification = Notification::create($input);

        \Session::flash('success', 'Notification has been inserted successfully!');
        return redirect()->route('notifications.index');
    }
}
