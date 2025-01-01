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
                    <div class="card-body">
                        <div class="row">
                            <!-- Start Date Input -->
                            <div class="col-md-5">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" class="form-control" name="start_date">
                            </div>

                            <!-- End Date Input -->
                            <div class="col-md-5">
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" class="form-control" name="end_date">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button id="paymentFilter" class="btn btn-primary w-100"><i class="fa fa-filter" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu])
                    </div>
                    <div class="card-body table-responsive">
                        <input type="hidden" id="route_name" value="{{ route('payment.index') }}">
                        <table id="paymentTable" class="table table-bordered table-striped datatable-dynamic">
                            <thead>
                                <tr>
                                    <th>Child Name</th>                    
                                    <th>Transaction Id</th>                    
                                    <th>Amount</th>                    
                                    <th>Payment Date</th>                   
                                    <th>Payment Status</th>  
                                                         
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