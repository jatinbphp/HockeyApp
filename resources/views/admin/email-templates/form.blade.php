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
    
    @if($template->template_name_slug=="welcome_register_mail")
        <div class="col-md-12">
            <p class="text-muted">
                <strong>Available Variables:</strong><br>
                <code>@{{firstname}}</code> - User's first name<br>
                <code>@{{lastname}}</code> - User's last name<br>
            </p>
        </div>
    @elseif($template->template_name_slug=="skills_test_mail")
        <div class="col-md-12">
            <p class="text-muted">
                <strong>Available Variables:</strong><br>
                <code>@{{firstname}}</code> - Student first name<br>
                <code>@{{lastname}}</code> - Student last name<br>
                <code>@{{skill_name}}</code> - Name of the skill<br>
                <code>@{{score}}</code> - Total score<br>
                <code>@{{duration}}</code> - Total duration<br>
            </p>
        </div>
    @endif

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
