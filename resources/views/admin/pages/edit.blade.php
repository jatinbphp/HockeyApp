@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    
    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('pages.index'), 'title' => 'Manage Pages'],
            ['route' => route('pages.index'), 'title' => $menu]
        ],
        'active' => 'Edit' 
    ])
 
    <section class="content">
        @include ('admin.common.error')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => 'Edit ' . $menu])
                    </div>
                    {!! Form::model($pages,['url' => route('pages.update',['page'=>$pages->id]),'method'=>'patch','id' => 'companiesForm','class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.pages.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'pages.index', 'type' => 'update'])
                        </div>
                    {!! Form::close() !!}                   
                </div>
            </div>
        </div>
    </section>
</div>
@endsection