@if ($child_payment_status == "Paid")
    <div class="btn-group-horizontal" id="paid_remove_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-sm btn-success on_off_child_payment ladda-button" data-style="slide-left" id="remove" data-url="{{route('common.changePaymentStatus')}}" data-id="{{$id}}" data-parentid="{{$parent_id}}" type="button" data-type="pending" data-table_name="{{$table_name}}">
            <span class="ladda-label">On</span> 
        </button>
    </div>
    <div class="btn-group-horizontal" id="paid_add_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-sm btn-danger on_off_child_payment ladda-button" data-style="slide-left" data-id="{{$id}}" data-parentid="{{$parent_id}}" data-url="{{route('common.changePaymentStatus')}}" type="button" data-type="Paid" data-table_name="{{$table_name}}">
            <span class="ladda-label">Off</span>
        </button>
    </div>
@else
    <div class="btn-group-horizontal" id="paid_add_{{$id}}">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-sm btn-danger on_off_child_payment ladda-button" data-style="slide-left" data-id="{{$id}}" data-parentid="{{$parent_id}}" data-url="{{route('common.changePaymentStatus')}}" type="button" data-type="Paid" data-table_name="{{$table_name}}">
            <span class="ladda-label">Off</span>
        </button>
    </div>
    <div class="btn-group-horizontal" id="paid_remove_{{$id}}" style="display: none">
        <button @if(isset($login_user)) {{'disabled'}} @endif class="btn btn-sm btn-success on_off_child_payment ladda-button" id="remove" data-id="{{$id}}" data-parentid="{{$parent_id}}" data-style="slide-left" data-url="{{route('common.changePaymentStatus')}}" type="button" data-type="pending" data-table_name="{{$table_name}}">
            <span class="ladda-label">On</span>
        </button>
    </div>
@endif