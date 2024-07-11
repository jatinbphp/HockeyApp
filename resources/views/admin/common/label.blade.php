{!! Form::label($field, $labelText . ' :', ['class' => 'control-label']) !!}
@if($isRequired)
    <span class="text-red">*</span>
@endif