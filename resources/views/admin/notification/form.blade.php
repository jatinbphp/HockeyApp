{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'user_type', 'labelText' => 'Send Notification to', 'isRequired' => true])

            {!! Form::select('user_type', ['guardian' => 'Guardian','children' => 'Children' ,'School' => 'Schools', 'province' => 'Provinces'], null, ['class' => 'form-control', 'placeholder' => 'Select User Type', 'id' => 'user_type']) !!}

            @include('admin.common.errors', ['field' => 'user_type'])
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="user_id" id="user_id_label">Select Guaardian/Province/Schools/Children</label>
            
            {!! Form::select('user_id[]',  [], null, ['class' => 'form-control select2', 'placeholder' => 'Please Select', 'id' => 'user_id', 'multiple' => 'multiple']) !!}

            @include('admin.common.errors', ['field' => 'user_id'])
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
   $(document).ready(function () {
    let users = @json($users);
    let schools = @json($schools);
    let provinces = @json($provinces);
    let children  = @json($children);

    $('#user_type').change(function () {
        let userType = $(this).val();
        let options = '';
        let label = '';

        if (userType === 'guardian') {
            label = 'Select Users:';
        } else if (userType === 'School') {
            label = 'Select Schools:';
        } else if (userType === 'province') {
            label = 'Select Provinces:';
        } else if (userType === 'children') {
            label = 'Select Children:';
        }

        $('#user_id_label').html(label + '<span class="text-danger">*</span>');

        if (userType === 'guardian') {
            $.each(users, function (key, value) {
                options += `<option value="${key}">${value}</option>`;
            });
        } else if (userType === 'School') {
            $.each(schools, function (key, value) {
                options += `<option value="${key}">${value}</option>`;
            });
        } else if (userType === 'province') {
            $.each(provinces, function (key, value) {
                options += `<option value="${key}">${value}</option>`;
            });
        } else if (userType === 'children') {
            $.each(children, function (key, value) {
                options += `<option value="${key}">${value}</option>`;
            });
        }

        $('#user_id').html(options);
    });
});

</script>

@endsection
