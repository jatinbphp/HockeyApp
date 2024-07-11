
@if($section_name=='users' || $section_name=='parent')

    <a href="{{ url($section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url($section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif


@if($section_name=='children')

   {{--  <a href="{{ url($section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a> --}}
    <a href="javascript:void(0)" data-toggle="tooltip" data-target="#childModel"  data-id="{{$id}}" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url($section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif

@if($section_name=='category')

    <a href="{{ url($section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url($section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif


@if($section_name=='province')

    <a href="{{ url($section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url($section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif

@if($section_name=='school')

    <a href="{{ url($section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url($section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif