@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
        ],
        'active' => $menu
    ])

    <!-- Main content -->
    <section class="content">
        @include ('admin.common.error')
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu,'addNewRoute' => route('skill.create')])
                    </div>
                    <div class="card-body table-responsive">
                        <input type="hidden" id="route_name" value="{{ route('skill.index') }}">
                        <table id="skillTable" class="table table-bordered table-striped datatable-dynamic">
                            <thead>
                                <tr>
                                    <th></th>  
                                    <th>Featured Image</th>                               
                                    <th>Skill Name</th>                                
                                    <th>Category</th>                              
                                    <th>Status</th>                           
                                    <th>Created At</th>                           
                                    <th>Action</th>                           
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection