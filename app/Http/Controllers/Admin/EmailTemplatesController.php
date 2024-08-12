<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class EmailTemplatesController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Email Template';

        if($request->ajax()){
            return Datatables::of(EmailTemplate::all())
            ->addIndexColumn()
            ->editColumn('template_name', function($row) {
                return ucfirst($row->template_name);
            })  
             ->editColumn('template_subject', function($row) {
                return ucfirst($row->template_subject);
            })  
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'email_templates';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'email-templates';
                $row['section_title'] = 'Email Template';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.email-templates.index',$data);
    }

    public function create(){
        $data['menu'] = 'Email Template';
        return view('admin.email-templates.create',$data);
    }

    public function store(Request $request){
        
        $inputs = $request->all();
        $template = EmailTemplate::create($inputs);

        \Session::flash('success', 'Template has been inserted successfully!');
        return redirect()->route('email-templates.index', ['email_template' => $template->id]);

    }

    public function edit($id){
        $data['menu'] = 'Email Template';
        $data['template'] = EmailTemplate::where('id',$id)->findorFail($id);
        return view('admin.email-templates.edit',$data);
    }

    public function update(Request $request,$id){
        $inputs = $request->all();
        $template = EmailTemplate::findOrFail($id);
        $template->update($inputs);

        \Session::flash('success','Template has been updated successfully!');
        return redirect()->route('email-templates.index');
    }

    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        if(!empty($template)){
            $template->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
 