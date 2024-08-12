@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    
    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('parent.index'), 'title' => 'Manage Guardian'],
            ['route' => route('parent.index'), 'title' => $menu]
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
                    {!! Form::model($parent,['url' => route('parent.update',['parent'=>$parent->id]),'method'=>'patch','id' => 'companiesForm','class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.parent.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'parent.index', 'type' => 'update'])
                        </div>
                    {!! Form::close() !!}

                    @include('admin.children.modalForm')
                </div>
            </div>
        </div>
    </section>
</div>
@endsection