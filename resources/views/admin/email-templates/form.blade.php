{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('template_name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'template_name', 'labelText' => 'Template Name', 'isRequired' => true])

            {!! Form::text('template_name', null, ['class' => 'form-control', 'placeholder' => 'Template Name', 'id' => 'template_name']) !!}

            @include('admin.common.errors', ['field' => 'template_name'])
        </div>
    </div>    

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('template_subject') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'template_subject', 'labelText' => 'Template Subject', 'isRequired' => true])

            {!! Form::text('template_subject', null, ['class' => 'form-control', 'placeholder' => 'Template Subject', 'id' => 'template_subject']) !!}

            @include('admin.common.errors', ['field' => 'template_subject'])
        </div>
    </div>        

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('template_message') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'template_message', 'labelText' => 'Template Message', 'isRequired' => true])

            {!! Form::textarea('template_message', null, ['class' => 'form-control', 'id' => 'template_message', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'template_message'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($province) ? $province->status : 'active'
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
