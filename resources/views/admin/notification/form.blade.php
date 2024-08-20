{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'user_type', 'labelText' => 'Send to Notification', 'isRequired' => true])

            {!! Form::select('user_type', ['users' => 'Users', 'School' => 'Schools', 'province' => 'Provinces'], null, ['class' => 'form-control', 'placeholder' => 'Select User Type', 'id' => 'user_type']) !!}

            @include('admin.common.errors', ['field' => 'user_type'])
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="user_id" id="user_id_label">Select Users/Province/Schools</label>
            
            {!! Form::select('user_id[]',  [], null, ['class' => 'form-control select2', 'placeholder' => 'Select Driver/Customer', 'id' => 'user_id', 'multiple' => 'multiple']) !!}

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
        // Data for users, schools, and provinces
        let users = @json($users);
        let schools = @json($schools);
        let provinces = @json($provinces);

        // When the user_type dropdown changes
        $('#user_type').change(function () {
            let userType = $(this).val();
            let options = '';
            let label = '';

            // Populate the second dropdown based on the selected user_type
            if (userType === 'users') {
                label = 'Select Users:';
            } else if (userType === 'School') {
                label = 'Select Schools:';
            } else if (userType === 'province') {
                label = 'Select Provinces:';
            }

            // Update the label of the second dropdown with a red asterisk
            $('#user_id_label').html(label + '<span class="text-danger">*</span>');

            // Generate options for the second dropdown
            if (userType === 'users') {
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
            }

            // Update the options of the second dropdown
            $('#user_id').html(options);
        });
    });
</script>

@endsection
