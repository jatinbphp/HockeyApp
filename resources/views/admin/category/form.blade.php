{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'name', 'labelText' => 'Category Name', 'isRequired' => true])

            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'id' => 'name']) !!}

            @include('admin.common.errors', ['field' => 'name'])
        </div>
    </div>        

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($category) ? $category->status : 'active'
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
