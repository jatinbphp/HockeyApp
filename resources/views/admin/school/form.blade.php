{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'name', 'labelText' => 'School Name', 'isRequired' => true])

            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter School Name', 'id' => 'name']) !!}

            @include('admin.common.errors', ['field' => 'name'])
        </div>
    </div>  

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('town') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'town', 'labelText' => 'Town Name', 'isRequired' => true])

            {!! Form::text('town', null, ['class' => 'form-control', 'placeholder' => 'Enter Town', 'id' => 'town']) !!}

            @include('admin.common.errors', ['field' => 'town'])
        </div>
    </div>  

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'province_id', 'labelText' => 'Select Province', 'isRequired' => true])

            {!! Form::select('province_id', isset($provinceData) ? $provinceData : [], null, ['class' => 'form-control', 'placeholder' => 'Select Province', 'id' => 'province_id']) !!}

            @include('admin.common.errors', ['field' => 'province_id'])
        </div>
    </div>        

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($school) ? $school->status : 'active'
            ])
        </div>
    </div>
</div>


@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

   
});
</script>
@endsection
