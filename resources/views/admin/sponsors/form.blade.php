{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'name', 'labelText' => 'Sponsor Name', 'isRequired' => true])

            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Sponsor Name', 'id' => 'name']) !!}

            @include('admin.common.errors', ['field' => 'name'])
        </div>
    </div>        

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'description', 'labelText' => 'Description', 'isRequired' => true])

            {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'description'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'website', 'labelText' => 'Website URL', 'isRequired' => false])

            {!! Form::text('website', null, ['class' => 'form-control', 'placeholder' => 'Enter Website URL', 'id' => 'website']) !!}

            @include('admin.common.errors', ['field' => 'website'])
        </div>
    </div>    

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($sponsors) ? $sponsors->status : 'active'
            ])
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'image', 'labelText' => 'Image', 'isRequired' => false])
            <div class="">
                <div class="fileError">
                    {!! Form::file('image', ['class' => '', 'id'=> 'image','accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                </div>
                
                @if(!empty($sponsors['image']) && file_exists(public_path($sponsors['image'])))
                <img src="{{asset($sponsors['image'])}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayImage">
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
