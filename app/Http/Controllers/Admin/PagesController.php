<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;
use DataTables; 

class PagesController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Pages'; 

        if($request->ajax()){
            return Datatables::of(Pages::all())
            ->addIndexColumn()
            ->editColumn('title', function($row) {
                return ucfirst($row->title);
            })            
            ->addColumn('action', function($row){
                $row['section_name'] = 'pages';
                $row['section_title'] = 'Pages';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.pages.index',$data);
    }

    public function edit($id){
        $data['menu'] = 'Pages';
       
        $data['pages'] = Pages::where('id',$id)->findorFail($id);
        return view('admin.pages.edit',$data);
    }

    public function update(Request $request,$id){
      
        $input = $request->all();

        $pages = Pages::findorFail($id);

        $pages->update($input);

        \Session::flash('success','Page has been updated successfully!');
        return redirect()->route('pages.index');
    }
}
