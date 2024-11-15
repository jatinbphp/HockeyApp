{!! Form::hidden('redirects_to', URL::previous()) !!}

<div class="row">
    <div class="col-md-4">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            @include('admin.common.label', ['field' => 'fees', 'labelText' => 'Fees', 'isRequired' => true])

            {!! Form::text('fees', (isset($fees)) ? $fees['fees'] : null, ['class' => 'form-control', 'placeholder' => 'Enter Amount', 'id' => 'fees']) !!}

            @include('admin.common.errors', ['field' => 'fees'])
        </div>
    </div>   
</div>
