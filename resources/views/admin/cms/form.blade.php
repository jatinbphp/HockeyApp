{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('page_name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'page_name', 'labelText' => 'CMS Page Name', 'isRequired' => true])

            {!! Form::text('page_name', null, ['class' => 'form-control', 'id' => 'page_name', 'readonly' => 'readonly']) !!}

            @include('admin.common.errors', ['field' => 'page_name'])
        </div>
    </div>   

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('page_content') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'page_content', 'labelText' => 'Content', 'isRequired' => true])

            {!! Form::textarea('page_content', null, ['class' => 'form-control', 'id' => 'page_content', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'page_content'])
        </div>
    </div>   
</div>


@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

   
});
</script>
@endsection
