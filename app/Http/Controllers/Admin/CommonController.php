<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CommonController extends Controller
{
    public function changeStatus(Request $request){
        $updateInput = DB::table($request['table_name'])->where('id', $request['id'])->first();
        $updateInput->status = ($request['type'] == 'unassign') ? 'inactive' : 'active';
        DB::table($request['table_name'])->where('id', $request['id'])->update((array) $updateInput);
    }

    public function page_not_found(){
        $data['menu'] = '404';
        return view('admin.errors.404', $data);
    }   

    
}
