{!! Form::hidden('redirects_to', URL::previous()) !!}
@if(isset($profile_update))
    @include('admin.common.password-alert')
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'firstname', 'labelText' => 'First Name', 'isRequired' => true])

            {!! Form::text('firstname', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'id' => 'firstname']) !!}

            @include('admin.common.errors', ['field' => 'firstname'])
        </div>
    </div>   

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'lastname', 'labelText' => 'Last Name', 'isRequired' => true])

            {!! Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name', 'id' => 'lastname']) !!}

            @include('admin.common.errors', ['field' => 'lastname'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'username', 'labelText' => 'Username', 'isRequired' => true])

            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Enter Username', 'id' => 'username']) !!}

            @include('admin.common.errors', ['field' => 'username'])
        </div>
    </div>   

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'email', 'labelText' => 'Email', 'isRequired' => true])

            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email', 'id' => 'email']) !!}

            @include('admin.common.errors', ['field' => 'email'])
        </div>
    </div>  

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'password', 'labelText' => 'Password', 'isRequired' => true])

            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter password', 'id' => 'password']) !!}

            @include('admin.common.errors', ['field' => 'password'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'password_confirmation', 'labelText' => 'Confirm Password', 'isRequired' => true])

            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Enter confirm password', 'id' => 'password_confirmation']) !!}

            @include('admin.common.errors', ['field' => 'password_confirmation'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'image', 'labelText' => 'Image', 'isRequired' => false])
            <div class="">
                <div class="fileError">
                    {!! Form::file('image', ['class' => '', 'id'=> 'image','accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                </div>
                
                @if(!empty($profile_update['image']) && file_exists(public_path($profile_update['image'])))
                <img src="{{asset($profile_update['image'])}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayImage">
                @else
                    <img src=" {{url('assets/dist/img/no-image.png')}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;padding: 20px;" width="150" id="DisplayImage">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($users) ? $users->status : 'active'
            ])
        </div>
    </div>
</div>
