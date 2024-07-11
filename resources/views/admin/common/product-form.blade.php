{!! Form::hidden('redirects_to', URL::previous()) !!}
@if(isset($user))
    @include('admin.common.password-alert')
@endif

<div class="row">   

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'name', 'labelText' => 'Name', 'isRequired' => true])

            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'name']) !!}

            @include('admin.common.errors', ['field' => 'name'])
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'email', 'labelText' => 'Email Address', 'isRequired' => true])
            
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email Address', 'id' => 'email']) !!}

            @include('admin.common.errors', ['field' => 'email'])
        </div>
    </div>
    
   
</div>

@section('jquery')
<script type="text/javascript">
$(document).ready(function(){

    if($("#company_ids").val()==''){
        updateDropdowns(['branch_ids']);
    }

    //get branch
    $('#company_ids').change(function(){
        updateDropdowns(['branch_ids']);

        var accountId = $(this).val();
        if (accountId) {
            showLoader();
            $.ajax({
                url: "{{ route('branches.by_company')}}",
                type: "POST",
                data: {
                    _token: '{{csrf_token()}}',
                    accountId : accountId,
                },
                success: function(response){    
                    hideLoader();
                    if (response && response.length > 0) {
                        response.forEach(function(branch) {
                            $('#branch_ids').append('<option value="' + branch.id + '">' + branch.full_name + '</option>');
                        });
                    }
                }
            });
        } 
    });
});
</script>
@endsection