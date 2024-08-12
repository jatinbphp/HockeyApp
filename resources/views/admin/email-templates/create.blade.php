@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">

    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('email-templates.index'), 'title' => 'Manage Templates'],
            ['route' => route('email-templates.index'), 'title' => $menu]
        ],
        'active' => 'Add'
    ])
 
    <section class="content">
        @include ('admin.common.error')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Add ' . $menu]) 
                    </div>
                    {!! Form::open(['url' => route('email-templates.store'), 'id' => 'usersForm', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.email-templates.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'email-templates.index', 'type' => 'create'])
                        </div>
                    {!! Form::close() !!}

                    
                </div>
            </div>
        </div>
    </section>
</div>
@endsection