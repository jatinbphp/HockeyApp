<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPages;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class CmsPagesController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'CMS'; 

        if($request->ajax()){
            return Datatables::of(CmsPages::all())
            ->addIndexColumn()           
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            }) 
            ->addColumn('action', function($row){
                $row['section_name'] = 'cms_page';
                $row['section_title'] = 'cms_page';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.cms.index',$data);
    }

    public function edit($id){
        $data['menu'] = 'CMS';     
        $data['cms_page'] = CmsPages::where('id',$id)->findorFail($id);
        return view('admin.cms.edit',$data);
    }

    public function update(Request $request,$id){
        $inputs = $request->all();
        $cms = CmsPages::findOrFail($id);
        $cms->update($inputs);

        \Session::flash('success','Message has been updated successfully!');
        return redirect()->route('cms_page.index');
    }
}
 