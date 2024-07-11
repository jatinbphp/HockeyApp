@if(Session::has('success'))
    <div class="alert alert-success p-2">
        <button data-dismiss="alert" class="close">&times;</button>
        {{Session::get('success')}}
    </div>
@elseif(Session::has('danger'))
    <div class="alert alert-danger p-2">
        <button data-dismiss="alert" class="close">&times;</button>
        {{Session::get('danger')}}
    </div>
@elseif(Session::has('warning'))
    <div class="alert alert-warning p-2">
        <button data-dismiss="alert" class="close">&times;</button>
        {{Session::get('warning')}}
    </div>
@endif
