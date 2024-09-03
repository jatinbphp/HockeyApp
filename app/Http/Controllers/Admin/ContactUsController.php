<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class ContactUsController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Contact Us'; 

        if($request->ajax()){
            return Datatables::of(ContactUs::all())
            ->addIndexColumn()           
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            }) 
            ->addColumn('action', function($row){
                $row['section_name'] = 'contactus';
                $row['section_title'] = 'contact-us';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.contactUs.index',$data);
    }

    public function show($id) {

        $contactUs = ContactUs::findOrFail($id);
        $required_columns = ['id', 'fullname','phone', 'email','message','created_at'];

        return view('admin.common.show_modal', [
            'section_info' => $contactUs->toArray(),
            'type' => 'contact Us',
            'required_columns' => $required_columns
        ]);
    }

    public function destroy($id){

        $contact_us = ContactUs::findOrFail($id);
        if(!empty($contact_us)){
            $contact_us->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
