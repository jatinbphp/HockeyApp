{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'name', 'labelText' => 'Skill Name', 'isRequired' => true])

            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Skill Name', 'id' => 'name']) !!}

            @include('admin.common.errors', ['field' => 'name'])
        </div>
    </div>   

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'category_id', 'labelText' => 'Select Category', 'isRequired' => true])

            {!! Form::select('category_id', isset($skillData) ? $skillData : [], null, ['class' => 'form-control', 'placeholder' => 'Select Category', 'id' => 'category_id']) !!}

            @include('admin.common.errors', ['field' => 'category_id'])
        </div>
    </div>        

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('short_description') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'short_description', 'labelText' => 'Short Description', 'isRequired' => true])

            {!! Form::textarea('short_description', null, ['class' => 'form-control', 'id' => 'short_description', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'short_description'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('long_description') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'long_description', 'labelText' => 'Long Description', 'isRequired' => true])

            {!! Form::textarea('long_description', null, ['class' => 'form-control', 'id' => 'long_description', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'long_description'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('instruction') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'instruction', 'labelText' => 'Instruction', 'isRequired' => true])

            {!! Form::textarea('instruction', null, ['class' => 'form-control', 'id' => 'instruction', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'instruction'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('score_instruction') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'score_instruction', 'labelText' => 'How to Score', 'isRequired' => true])

            {!! Form::textarea('score_instruction', null, ['class' => 'form-control', 'id' => 'score_instruction', 'rows' => 5]) !!}

            @include('admin.common.errors', ['field' => 'score_instruction'])
        </div>
    </div>  

    <div class="col-md-12">
        <div class="form-group{{ $errors->has('video_url') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'video_url', 'labelText' => 'Video URL', 'isRequired' => true])

            {!! Form::text('video_url', null, ['class' => 'form-control', 'placeholder' => 'Enter Video URL', 'id' => 'video_url']) !!}

            @include('admin.common.errors', ['field' => 'video_url'])
        </div>
    </div> 

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'image', 'labelText' => 'Featured Image', 'isRequired' => false])
            <div class="">
                <div class="fileError">
                    {!! Form::file('image', ['class' => '', 'id'=> 'image','accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                </div>
                
                @if(!empty($skill['featured_image']) && file_exists(public_path($skill['featured_image'])))
                <img src="{{asset($skill['featured_image'])}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayImage">
                @else
                    <img src=" {{url('assets/dist/img/no-image.png')}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;padding: 20px;" width="150" id="DisplayImage">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('icon_image') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'icon_image', 'labelText' => 'Icon Image', 'isRequired' => false])
            <div class="">
                <div class="fileError">
                    {!! Form::file('icon_image', ['class' => '', 'id'=> 'icon_image','accept'=>'image/*', 'onChange'=>'AjaxUploadIconImage(this)']) !!}
                </div>
                
                @if(!empty($skill['icon_image']) && file_exists(public_path($skill['icon_image'])))
                <img src="{{asset($skill['icon_image'])}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;" width="150" id="DisplayIconImage">
                @else
                    <img src=" {{url('assets/dist/img/no-image.png')}}" alt="User Image" style="border: 1px solid #ccc;margin-top: 5px;padding: 20px;" width="150" id="DisplayIconImage">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'status', 'labelText' => 'Status', 'isRequired' => false])
            
            @include('admin.common.active-inactive-buttons', [                
                'checkedKey' => isset($skill) ? $skill->status : 'active'
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
