<a href="{{ route($route) }}" class="btn btn-sm btn-default"><i class="fa fa-arrow-left pr-1"></i> Back</a>

@if($type=="update")
<button class="btn btn-sm btn-info float-right" type="submit"><i class="fa fa-edit pr-1"></i> Update</button>
@elseif($type != "callOutClaimBtns")
<button class="btn btn-sm btn-info float-right" type="submit"><i class="fa fa-save pr-1"></i> Save</button>
@endif

@if($type=="callOutClaimBtns")
	<button class="btn btn-sm btn-info float-right callout-claim-frm-btn" id="updateClaimCallOut" type="submit"><i class="fa fa-edit pr-1"></i> Update Call-Out</button>
	<button class="btn btn-sm btn-info float-right mr-2 callout-claim-frm-btn" id="saveClaimCallOut" type="button"><i class="fa fa-save pr-1"></i> Save Call-Out</button>

	<button class="btn btn-sm btn-info float-right btn-danger callout-claim-frm-btn mr-2" id="closedClaimBtn" type="button"  onclick="changeClaimStatus(this);" data-id="{{$callOutId}}" data-url="{{route('controller.call-outs.change_claim_status')}}"><i class="fas fa-times pr-1"></i> Closed</button>
@endif