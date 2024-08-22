<div class="row">
  
        <div class="col-md-3">
            <div class="form-group">
                @include('admin.common.label', ['field' => 'province_id', 'labelText' => 'Province', 'isRequired' => false])

                {!! Form::select("province_id", ['' => 'Please Select'] + ($province->toArray() ?? []), null, ["class" => "form-control select2", "id" => "province_id"]) !!}           
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                @include('admin.common.label', ['field' => 'school_id', 'labelText' => 'School', 'isRequired' => false])

                {!! Form::select("school_id", ['' => 'Please Select'] + ($school->toArray() ?? []), null, ["class" => "form-control select2", "id" => "school_id"]) !!}           
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