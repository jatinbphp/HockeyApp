@if ($errors->has($field))
    @foreach ($errors->get($field) as $error)
        <span class="text-danger">
            <strong>{{ $error }}</strong>
        </span>
    @endforeach
@endif