<h3 class="card-title">{{ $title }}</h3>
@isset($addNewRoute)
	<div class="row">
	    <div class="col-md-12">
	        <a href="{{ $addNewRoute }}" class="btn btn-sm btn-info float-right"><i class="fa fa-plus pr-1"></i></i> Add New</a>
	    </div>
	</div>
@endisset