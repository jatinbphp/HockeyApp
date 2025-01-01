@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    
    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('children.index'), 'title' => 'Manage children'],
            ['route' => route('children.index'), 'title' => $menu]
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
                    {!! Form::model($children,['url' => route('children.update',['child'=>$children->id]),'method'=>'patch','id' => 'companiesForm','class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.children.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'children.index', 'type' => 'update'])
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>

@endsection 

@section('jquery')
<script type="text/javascript">
    $(document).ready(function(){
       
    });
</script>
@endsection