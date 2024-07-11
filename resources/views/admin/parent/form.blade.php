<ul class="nav nav-tabs" id="myTabs">
    <li class="nav-item">
        <a class="nav-link active" id="tab1" data-toggle="tab" href="#content1">Guardian Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(!isset($parent)) disabled @endif" id="tab2" data-toggle="tab" href="#content2">Children Information</a>
    </li>
</ul>

{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="tab-content mt-2">
    <div class="row tab-pane fade show active" id="content1">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card mb-4">
                <div class="card-body">

                    @if(isset($parent))
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
                                    
                                    @if(!empty($parent['image']) && file_exists(public_path($parent['image'])))
                                    <img src="{{asset($parent['image'])}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayImage">
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
                                    'checkedKey' => isset($parent) ? $parent->status : 'active'
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row tab-pane fade" id="content2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Manage Child</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-right" data-target="#childModel" id="createNewChilren" data-url="{{ route('children.create') }}">
                                <i class="fa fa-plus pr-1"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <input type="hidden" id="route_name" value="{{ route('children.index') }}">
                    <table id="childTable" class="table table-bordered table-striped datatable-dynamic" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Email</th>                           
                                <th>Status</th>                           
                                <th>Created At</th>                           
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @include('admin.children.modalForm')
                </div>
            </div>
        </div>
    </div>

</div>


@section('jquery')
<script type="text/javascript">

$(document).ready(function(){

    /* CHILDREN ADD/EDIT FROM MODAL POPUP */

    /* ADD NEW CHILDREN */
    $('#createNewChilren').click(function () {
        $('#saveBtn').val("create-children");
        $('#child_id').val('');
        $('#childForm').trigger("reset");
        $('#modelHeading').html("Create New Children");
        $('#childModel').modal('show');
    });

    /* EDIT CHILDREN */
    $('body').on('click', '.editProduct', function () {
        var child_id = $(this).data('id');
        $.get("{{ route('children.index') }}" +'/' + child_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Product");
            $('#saveBtn').val("edit-children");
            $('#childModel').modal('show');
            $('#child_id').val(data.id);
            $('#firstname').val(data.firstname);
            $('#lastname').val(data.lastname);
        })
    });

    // /* SAVE CHILDREN DATA BY AJAX REQUEST */
    // $('#saveBtn').click(function (e) {
    //     e.preventDefault();
    //     $(this).html('Sending..');
      
    //     $.ajax({
    //         data: $('#childForm').serialize(),
    //         url: "{{ route('children.store') }}",
    //         headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
    //         type: "POST",
    //         dataType: 'json',
    //         success: function (data) {
        
    //             $('#childForm').trigger("reset");
    //             $('#childModel').modal('hide');
    //             child_table.draw();
            
    //         },
    //         error: function (data) {
    //             console.log('Error:', data);
    //             $('#saveBtn').html('Save Changes');
    //         }
    //     });
    // });
   
});
</script>
@endsection
