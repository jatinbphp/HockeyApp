{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'message', 'labelText' => 'Consent Message', 'isRequired' => true])

            {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'message', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'message'])
        </div>
    </div>   
</div>


@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

   
});
</script>
@endsection
