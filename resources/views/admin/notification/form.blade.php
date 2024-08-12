{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'user_type', 'labelText' => 'Send to notification', 'isRequired' => true])

            {!! Form::select('user_type', ['users' => 'Users', 'province' => 'Provinces', 'School' => 'Schools'], null, ['class' => 'form-control select2', 'placeholder' => 'Select User Type', 'id' => 'user_type']) !!}

            @include('admin.common.errors', ['field' => 'user_type'])
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'user_id', 'labelText' => 'Select Users', 'isRequired' => true])

            {!! Form::select('user_id[]', $users, null, ['class' => 'form-control selectDropdown selectpicker', 'placeholder' => 'Select Users', 'id' => 'user_id', 'multiple' => 'multiple', 'data-live-search' => 'true']) !!}

            @include('admin.common.errors', ['field' => 'user_id'])
        </div>


        <div class="form-group">
            @include('admin.common.label', ['field' => 'province', 'labelText' => 'Select Category', 'isRequired' => true])

            {!! Form::select('province', $province, null, ['class' => 'form-control selectDropdown selectpicker', 'placeholder' => 'Select Category', 'id' => 'province', 'multiple' => 'multiple', 'data-live-search' => 'true']) !!}

            @include('admin.common.errors', ['field' => 'province'])
        </div>
      
    </div>

    <div class="col-md-12">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'message', 'labelText' => 'Message', 'isRequired' => true])

            {!! Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => 'Enter Message', 'id' => 'message', 'autocomplete' => 'off','rows' => 3]) !!}

            @include('admin.common.errors', ['field' => 'message'])
        </div>
    </div>
</div>


@section('jquery')
<script>
</script>
@endsection