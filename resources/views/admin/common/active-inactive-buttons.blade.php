<!-- <div class="status-buttons">
    @foreach (\App\Models\Common::$status as $key => $value)
        @php $checked = $checkedKey === $key ? 'checked' : ''; @endphp
        <label>
            {!! Form::radio('status', $key, null, ['class' => 'flat-red', $checked]) !!} 
            <span style="margin-right: 10px">{{ $value }}</span>
        </label>
    @endforeach
</div> -->

<div class="status-select">
    {!! Form::select('status', \App\Models\Common::$status, $checkedKey, ['class' => 'form-control select2']) !!}
</div>