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
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['url' => null, 'id' => 'ranking-filter-Form', 'class' => 'form-horizontal','files'=>true]) !!}
                            @include ('admin.global-ranking.filters', ['type' => 'globalranking'])
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu])
                    </div>
                    <div class="card-body table-responsive">
                        <input type="hidden" id="route_name" value="{{ route('globalranking.index') }}">
                        <table id="globalRankingTable" class="table table-bordered table-striped datatable-dynamic">
                            <thead>
                                <tr>
                                    <th>Child Name</th>
                                    <th>Age Group</th>
                                    <th>Gender</th>                                   
                                    <th>Score</th>
                                    <th>Ranking</th>                        
                                                   
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