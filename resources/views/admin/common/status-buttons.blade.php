@if ($status == "active")
    <div class="btn-group-horizontal" id="assign_remove_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-success assign_unassign ladda-button" data-style="slide-left" id="remove" data-url="{{route('common.changestatus')}}" data-id="{{$id}}" type="button" data-type="unassign" data-table_name="{{$table_name}}">
            <span class="ladda-label">Active</span> 
        </button>
    </div>
    <div class="btn-group-horizontal" id="assign_add_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-danger assign_unassign ladda-button" data-style="slide-left" data-id="{{$id}}" data-url="{{route('common.changestatus')}}" type="button" data-type="assign" data-table_name="{{$table_name}}">
            <span class="ladda-label">In Active</span>
        </button>
    </div>
@else
    <div class="btn-group-horizontal" id="assign_add_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-danger assign_unassign ladda-button" data-style="slide-left" data-id="{{$id}}" data-url="{{route('common.changestatus')}}" type="button" data-type="assign" data-table_name="{{$table_name}}">
            <span class="ladda-label">In Active</span>
        </button>
    </div>
    <div class="btn-group-horizontal" id="assign_remove_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-success assign_unassign ladda-button" id="remove" data-id="{{$id}}" data-style="slide-left" data-url="{{route('common.changestatus')}}" type="button" data-type="unassign" data-table_name="{{$table_name}}">
            <span class="ladda-label">Active</span>
        </button>
    </div>
@endif