@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
    
    @include('admin.common.header', [
        'menu' => $menu,
        'breadcrumb' => [
            ['route' => route('admin.dashboard'), 'title' => 'Dashboard'],
            ['route' => route('users.index'), 'title' => $menu]
        ],
    ])
 
    <section class="content">
        @include ('admin.common.error')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        @include('admin.common.card-header', ['title' => '' . $menu])
                    </div>
                    {!! Form::model($profile_update,['url' => route('profile_update.update',['profile_update'=>$profile_update->id]),'method'=>'patch','id' => 'profileUpdateForm','class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.profile-update.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'admin.dashboard', 'type' => 'update'])
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection