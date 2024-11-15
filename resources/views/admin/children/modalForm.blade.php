<div class="modal fade" id="childModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open([ 'id' => 'childForm', 'class' => 'form-horizontal','files'=>true]) !!}
                <!-- <form method="post" id="childForm" name="childForm" class="form-horizontal"> -->
                <div class="row">
                    {{ Form::hidden('parent_id', Request::segment(3), array('id' => 'parent_id')) }}

                    <input type="hidden" name="child_id" id="child_id">
                    <input type="hidden" name="modal_redirect_url" id="modal_redirect_url" value="{{ route('children.store') }}">
                   
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_firstname') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'child_firstname', 'labelText' => 'First Name', 'isRequired' => true])

                            {!! Form::text('child_firstname', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'id' => 'child_firstname']) !!}

                            @include('admin.common.errors', ['field' => 'child_firstname'])
                        </div>
                    </div>
               
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_lastname') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'child_lastname', 'labelText' => 'Last Name', 'isRequired' => true])

                            {!! Form::text('child_lastname', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name', 'id' => 'child_lastname']) !!}

                            @include('admin.common.errors', ['field' => 'child_lastname'])
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_username') ? ' has-error' : '' }}">
                        @include('admin.common.label', ['field' => 'child_username', 'labelText' => 'Username', 'isRequired' => true])

                        {!! Form::text('child_username', null, ['class' => 'form-control', 'placeholder' => 'Enter Username', 'id' => 'child_username']) !!}

                        @include('admin.common.errors', ['field' => 'child_username'])
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_email') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'child_email', 'labelText' => 'Email', 'isRequired' => true])

                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email', 'id' => 'child_email']) !!}

                            @include('admin.common.errors', ['field' => 'child_email'])
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_password') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'child_password', 'labelText' => 'Password', 'isRequired' => true])

                            {!! Form::password('child_password', ['class' => 'form-control', 'placeholder' => 'Enter password', 'id' => 'child_password']) !!}

                            @include('admin.common.errors', ['field' => 'child_password'])
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_password') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'password_confirmation_child', 'labelText' => 'Confirm Password', 'isRequired' => true])

                            {!! Form::password('password_confirmation_child', ['class' => 'form-control', 'placeholder' => 'Enter confirm password', 'id' => 'password_confirmation_child']) !!}

                            @include('admin.common.errors', ['field' => 'password_confirmation_child'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_dob') ? ' has-error' : '' }}">
                        @include('admin.common.label', ['field' => 'child_dob', 'labelText' => 'Birthdate', 'isRequired' => true])

                        {!! Form::text('child_dob', null, ['class' => 'form-control datepicker', 'placeholder' => 'Select Birthdate', 'id' => 'child_dob']) !!}

                        @include('admin.common.errors', ['field' => 'child_dob'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_phone') ? ' has-error' : '' }}">
                        @include('admin.common.label', ['field' => 'child_phone', 'labelText' => 'Phone', 'isRequired' => true])

                        {!! Form::text('child_phone', null, ['class' => 'form-control', 'placeholder' => 'Phone', 'id' => 'child_phone']) !!}

                        @include('admin.common.errors', ['field' => 'child_phone'])
                        </div>
                    </div>
                          
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'province_id', 'labelText' => 'Select Province', 'isRequired' => true])

                            {!! Form::select('province_id', isset($provinceData) ? $provinceData : [], null, ['class' => 'form-control select2', 'placeholder' => 'Select Province', 'id' => 'province_id', 'style' => 'width:100%']) !!}

                            @include('admin.common.errors', ['field' => 'province_id'])
                        </div>
                    </div> 

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'school_id', 'labelText' => 'Select School', 'isRequired' => true])

                            {!! Form::select('school_id', isset($schoolData) ? $schoolData : [], null, ['class' => 'form-control select2', 'placeholder' => 'Select School', 'id' => 'school_id', 'style' => 'width:100%']) !!}

                            @include('admin.common.errors', ['field' => 'school_id'])
                        </div>
                    </div> 

                    <div class="col-md-6">
                        <div class="form-group">
                            @include('admin.common.label', ['field' => 'looking_sponsor', 'labelText' => 'Looking for a Sponsor', 'isRequired' => false])

                            <input type="checkbox" name="looking_sponsor" id="looking_sponsor" class="form-control">

                        </div>
                    </div> 

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('child_image') ? ' has-error' : '' }}">
                            @include('admin.common.label', ['field' => 'child_image', 'labelText' => 'Image', 'isRequired' => false])
                            <div class="">
                                <div class="fileError">
                                    {!! Form::file('child_image', ['class' => '', 'id'=> 'image', 'accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                                </div>
                                
                                <img src="{{ url('assets/dist/img/no-image.png') }}" 
                                    alt="User Image" 
                                    style="border: 1px solid #ccc; margin-top: 5px;" 
                                    width="150" 
                                    id="childImage">
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                    </div>
                </div>
                <!-- </form> -->
                 {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>   


 