<div class="row">
  
    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'province_id', 'labelText' => 'Province', 'isRequired' => false])

            {!! Form::select("province_id", ['' => 'Please Select'] + ($province->toArray() ?? []), null, ["class" => "form-control select2 provinceId_filter", "id" => "province_id"]) !!}           
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'school_id', 'labelText' => 'School', 'isRequired' => false])

            {!! Form::select("school_id", [], null, ["class" => "form-control select2 schoolId_filter", "id" => "school_id"]) !!}           
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            @include('admin.common.label', ['field' => 'skill_id', 'labelText' => 'Skill', 'isRequired' => false])

            {!! Form::select("skill_id", ['' => 'Please Select'] + ($skill->toArray() ?? []), null, ["class" => "form-control select2", "id" => "skill_id"]) !!}           
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('age_group') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'age_group', 'labelText' => 'Age Group', 'isRequired' => false])
    
            {!! Form::select('age_group', ['0/9' => '0/9', '0/11' => '0/11', '0/13' => '0/13', '0/14' => '0/14', '0/16' => '0/16', '0/19' => '0/19'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Age Group', 'id' => 'age_group']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'gender', 'labelText' => 'Gender', 'isRequired' => false])
    
            {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Gender', 'id' => 'gender']) !!}
        </div>
    </div>    

    <div class="col-md-2" style="margin-top: 30px;">
        {!! Form::button('<i class="fa fa-times" aria-hidden="true"></i>', [
            'type' => 'button',
            'id' => 'clear-filter',
            'class' => 'btn btn-danger',
            'data-type' => $type
        ]) !!}
        
        {!! Form::button('<i class="fa fa-filter" aria-hidden="true"></i>', [
            'type' => 'button',
            'id' => 'apply-filter',
            'class' => 'btn btn-info',
            'data-type' => $type,
        ]) !!}
    </div>
</div>