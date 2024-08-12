@if ($status == "on")
    <div class="btn-group-horizontal" id="on_remove_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-success on_off ladda-button" data-style="slide-left" id="remove" data-url="{{route('common.changestatus')}}" data-id="{{$id}}" type="button" data-type="off" data-table_name="{{$table_name}}">
            <span class="ladda-label">On</span> 
        </button>
    </div>
    <div class="btn-group-horizontal" id="on_add_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-danger on_off ladda-button" data-style="slide-left" data-id="{{$id}}" data-url="{{route('common.changestatus')}}" type="button" data-type="on" data-table_name="{{$table_name}}">
            <span class="ladda-label">Off</span>
        </button>
    </div>
@else
    <div class="btn-group-horizontal" id="on_add_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-danger on_off ladda-button" data-style="slide-left" data-id="{{$id}}" data-url="{{route('common.changestatus')}}" type="button" data-type="on" data-table_name="{{$table_name}}">
            <span class="ladda-label">Off</span>
        </button>
    </div>
    <div class="btn-group-horizontal" id="on_remove_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-success on_off ladda-button" id="remove" data-id="{{$id}}" data-style="slide-left" data-url="{{route('common.changestatus')}}" type="button" data-type="off" data-table_name="{{$table_name}}">
            <span class="ladda-label">On</span>
        </button>
    </div>
@endif