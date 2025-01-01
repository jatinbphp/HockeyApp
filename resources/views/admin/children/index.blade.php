@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">

    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('children.index'), 'title' => 'Manage Children'],
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
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu,'addNewRoute' => route('children.create')])
                    </div>
                    <div class="card-body table-responsive">
                        <input type="hidden" id="route_name" value="{{ route('children.index') }}">
                        <table id="childTable" class="table table-bordered table-striped datatable-dynamic">
                            <thead>
                                <tr>
                                    <th>Parent Name</th>
                                    <th>Children Name</th>                                
                                    <th>Username</th>
                                    <th>Email</th>                           
                                    <th>Payment Status</th>                           
                                    <th>Payment Date</th>                           
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