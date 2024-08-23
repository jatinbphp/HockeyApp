@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">

    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('fees.index'), 'title' => $menu]
        ],
    ])
 
    <section class="content">
        @include ('admin.common.error')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Manage ' . $menu]) 
                    </div>
                    {!! Form::open(['url' => route('fees.store'), 'id' => 'feesForm', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.fees.form')
                        </div>
                        <div class="card-footer">
                            @if(empty($fees))
                                @include('admin.common.footer-buttons', ['route' => 'fees.index', 'type' => 'create'])
                            @else
                                @include('admin.common.footer-buttons', ['route' => 'fees.index', 'type' => 'update'])
                            @endif
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection