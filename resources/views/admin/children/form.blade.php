
{!! Form::hidden('redirects_to', URL::previous()) !!}
    
@if(isset($parent))
    @include('admin.common.password-alert')
@endif 
<div class="row">

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'parent_id', 'labelText' => 'Select Parent', 'isRequired' => true])

            {!! Form::select('parent_id', isset($parentData) ? $parentData : [], $children->parent_id ?? null, ['class' => 'form-control select2', 'placeholder' => 'Select Parent', 'id' => 'parent_id', 'style' => 'width:100%']) !!}

            @include('admin.common.errors', ['field' => 'parent_id'])
        </div>
    </div> 

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
        <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
        @include('admin.common.label', ['field' => 'date_of_birth', 'labelText' => 'Birthdate', 'isRequired' => true])

        {!! Form::text('date_of_birth', null, ['class' => 'form-control datepicker', 'placeholder' => 'Select Birthdate', 'id' => 'date_of_birth']) !!}

        @include('admin.common.errors', ['field' => 'date_of_birth'])
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'gender', 'labelText' => 'Select Gender', 'isRequired' => true])
    
            {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['class' => 'form-control', 'placeholder' => 'Select Gender', 'id' => 'gender']) !!}
    
            @include('admin.common.errors', ['field' => 'gender'])
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        @include('admin.common.label', ['field' => 'phone', 'labelText' => 'Phone', 'isRequired' => true])

        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone', 'id' => 'phone']) !!}

        @include('admin.common.errors', ['field' => 'phone'])
        </div>
    </div>
    
          
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'province_id', 'labelText' => 'Select Province', 'isRequired' => true])

            {!! Form::select('province_id', isset($provinceData) ? $provinceData : [], null, ['class' => 'form-control select2 provinceId_filter', 'placeholder' => 'Select Province', 'id' => 'province_id', 'style' => 'width:100%']) !!}

            @include('admin.common.errors', ['field' => 'province_id'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'school_id', 'labelText' => 'Select School', 'isRequired' => true])

            {!! Form::select('school_id', isset($schoolData) ? $schoolData : [], null, ['class' => 'form-control select2 schoolId_filter', 'placeholder' => 'Select School', 'id' => 'school_id', 'style' => 'width:100%']) !!}

            @include('admin.common.errors', ['field' => 'school_id'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'looking_sponsor', 'labelText' => 'Looking for a Sponsor', 'isRequired' => false])

            <input type="checkbox" name="looking_sponsor" id="looking_sponsor" class="form-control" {{ old('looking_sponsor', isset($children) && $children->looking_sponsor) == 1 ? 'checked' : '' }}>

        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($children) ? $children->status : 'active'
            ])
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'image', 'labelText' => 'Image', 'isRequired' => false])
            <div class="">
                <div class="fileError">
                    {!! Form::file('image', ['class' => '', 'id'=> 'image','accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                </div>
                
                @if(!empty($children->image) && file_exists(public_path($children->image)))
                    <img src="{{asset($children->image)}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayImage">
                @else
                    <img src=" {{url('assets/dist/img/no-image.png')}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;padding: 20px;" width="150" id="DisplayImage">
                @endif
            </div>
        </div>
    </div>

   
</div>

@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

   
});
</script>
@endsection
