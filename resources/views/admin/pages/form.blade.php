{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
   
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'title', 'labelText' => 'Title', 'isRequired' => true])

            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'id' => 'title']) !!}

            @include('admin.common.errors', ['field' => 'title'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'content', 'labelText' => 'Content', 'isRequired' => true])

            {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'content'])
        </div>
    </div> 
  
</div>


@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

   
});
</script>
@endsection
