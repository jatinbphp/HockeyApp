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
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu])
                    </div>
                    <div class="card-body table-responsive">
                        <input type="hidden" id="route_name" value="{{ route('skill-review.index') }}">
                        <input type="hidden" id="score_status_update" value="{{ route('skill-review.update_status')}}">
                        <table id="skillReviewTable" class="table table-bordered table-striped datatable-dynamic">
                            <thead>
                                <tr>
                                    <th>Skill</th>                    
                                    <th>Student Name</th>                    
                                    <th>Province</th>                    
                                    <th>Score</th>                    
                                    <th>Time Duration(seconds)</th>                    
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