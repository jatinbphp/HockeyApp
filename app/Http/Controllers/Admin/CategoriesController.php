<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Categories;
use DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request){
        $data['menu'] = 'Category';

        if($request->ajax()){
            return Datatables::of(Categories::all())
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return formatCreatedAt($row->created_at);
            })           
            ->editColumn('status', function($row){
                $row['table_name'] = 'categories';
                return view('admin.common.status-buttons', $row);
            })
            ->addColumn('action', function($row){
                $row['section_name'] = 'category';
                $row['section_title'] = 'Category';
                return view('admin.common.action-buttons', $row);
            })
            ->make(true);
        }

        return view('admin.category.index',$data);
    }

    public function create(){
        $data['menu'] = 'Category';
        return view('admin.category.create',$data);
    }

    public function store(CategoryRequest $request){
        $inputs = $request->all();
        $category = Categories::create($inputs);

        \Session::flash('success','Category has been inserted successfully!');
        return redirect()->route('category.edit', ['category' => $category->id]);
    }

    public function edit($id){
        $data['menu'] = 'Category';
        $data['category'] = Categories::where('id',$id)->findorFail($id);
        return view('admin.category.edit',$data);
    }

    public function update(CategoryRequest $request,$id){
        $inputs = $request->all();
        $category = Categories::findOrFail($id);
        $category->update($inputs);


        \Session::flash('success','Category has been updated successfully!');
        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        if(!empty($category)){
            $category->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
