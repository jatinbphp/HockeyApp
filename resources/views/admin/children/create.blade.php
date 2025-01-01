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
                    {!! Form::open(['url' => route('children.store'), 'id' => 'usersForm', 'class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            @include ('admin.children.form')
                        </div>
                        <div class="card-footer">
                            @include('admin.common.footer-buttons', ['route' => 'children.index', 'type' => 'create'])
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            
            $.ajax({
                url: '{{ route('school.getSchoolByProvinceId', '') }}/' + provinceId,
                method: 'GET',
                success: function(data) {
                    $('#school_id').empty().append('<option value="">Select School</option>');
                    $.each(data, function(key, value) {
                        $('#school_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });
    });
</script>
@endsection

@section('jquery');

@endsection